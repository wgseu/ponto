const http = require('http')
const SocketIO = require('socket.io')
const Service = require('./services')
const Syncronizer = require('./syncronizer')
const PrinterRounter = require('./printing/router')

class Server {
  constructor () {
    this.server = http.createServer()
    this.io = SocketIO(this.server, { path: '/ws' })
    this.clients = new Map()
    this.devices = new Map()
    this.deviceToken = null
    this.printers = []
  }

  print (sender, info) {
    if (sender.employee_id) {
      const router = new PrinterRounter(this, sender)
      router.send(info)
    }
  }

  sync (sender, info) {
    const syncronizer = new Syncronizer(sender, info)
    this.broadcast('sync', info, client => {
      return syncronizer.allow(client)
    })
  }

  async auth (client, info) {
    try {
      if (info.type === 'user') {
        if (!info.access_token) {
          throw new Error('No authentication token given')
        }
        const response = info.device_token ?
          await Service.User.fetchWithDevice(info.access_token, info.device_token) :
          await Service.User.fetch(info.access_token)
        const { data: { usuario, dispositivo } } = response
        client.type = info.type
        client.owner = usuario.proprietario
        client.client_id = usuario.id
        client.employee_id = usuario.prestador && usuario.prestador.id
        client.permissions = (usuario.prestador && usuario.prestador.permissions) || []
        client.device_id = dispositivo && dispositivo.id
      } else if (info.type === 'device') {
        const response = this.printers.length > 0 ?
          await Service.Device.fetch(info.device_token) :
          await Service.Device.fetchWithPrinter(info.device_token)
        const { data: { dispositivo, impressoras: { data: impressoras } } } = response
        client.type = info.type
        client.device = dispositivo
        client.printers = []
        // usa o token do dispositivo para baixar a lista de impressoras
        this.deviceToken = info.device_token
        this.devices.set(dispositivo.id, client)
        this.allPrinters = impressoras || this.allPrinters
        this.assignPrinters()
        console.log('impressoras:', this.printers.length)
      }
    } catch (error) {
      this.removeClient(client)
      this.resetClient(client.socket)
      client.socket.emit('authFailed')
      console.error(error)
    }
  }

  broadcast (event, data, check) {
    this.clients.forEach(client => {
      if (check(client, data, event)) {
        client.socket.emit(event, data)
      }
    })
  }

  assignPrinters () {
    let list = []
    this.devices.forEach((client, device_id) => {
      client.printers = this.allPrinters.filter(printer => printer.dispositivo_id == device_id)
      list = list.concat(client.printers)
    })
    this.printers = list
  }

  start (callback) {
    this.server.listen(3000, callback)
    this.io.on('connection', (socket) => {
      this.onConnection(socket)
    })
  }

  onConnection (socket) {
    this.resetClient(socket)
    socket.on('disconnect', () => {
      this.onDisconnect(socket)
    })
    socket.on('auth', info => {
      this.auth(this.clients.get(socket.id), info)
    })
    socket.on('sync', info => {
      this.sync(this.clients.get(socket.id), info)
    })
    socket.on('print', info => {
      this.print(this.clients.get(socket.id), info)
    })
  }

  onDisconnect (socket) {
    const client = this.clients.get(socket.id)
    this.removeClient(client)
    console.log('removeClient:', this.clients.size)
  }

  resetClient (socket) {
    const client = {
      id: socket.id,
      socket: socket,
      type: 'user'
    }
    this.clients.set(socket.id, client)
    console.log('resetClient:', this.clients.size)
  }

  removeClient (client) {
    this.clients.delete(client.id)
    if (client.device) {
      this.devices.delete(client.device.id)
      this.assignPrinters()
    }
  }

  close (callback) {
    this.server.close(callback)
  }
}

module.exports = Server
