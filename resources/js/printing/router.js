class Router {
  constructor (server, sender) {
    this.server = server
    this.sender = sender
  }

  send (data) {
    let packages = []
    if (data.type == 'service') {
      packages = this.split(data)
    } else {
      const printer = this.route(data, null)
      if (printer) {
        packages = [{
          data,
          socket: this.server.devices.get(printer.dispositivo_id).socket
        }]
      }
    }
    packages.forEach(pkg => {
      pkg.socket.emit('print', pkg.data)
    })
  }

  /**
   * Split services into devices for printing
   */
  split (data) {
    const devices = new Map()
    return data.sectors.reduce((packages, sector_id) => {
      const printer = this.route(data, sector_id)
      if (!printer) {
        return packages
      }
      if (devices.has(printer.dispositivo_id)) {
        const pkg = devices.get(printer.dispositivo_id)
        pkg.data.sectors.push(sector_id)
      } else {
        const socket = this.server.devices.get(printer.dispositivo_id).socket
        const pkg = { data: { ...data, sectors: [sector_id] }, socket }
        devices.set(printer.dispositivo_id, pkg)
        packages.push(pkg)
      }
      return packages
    }, [])
  }

  /**
   * Get printer to send info
   *
   * @param object info
   * @param number sector_id
   *
   * @return object printer
   */
  route (info, sector_id) {
    let modos = ['caixa', 'terminal']
    if (info.type == 'delivery') {
      modos = ['caixa', 'terminal', 'estoque']
    } else if (info.type == 'service') {
      modos = ['caixa', 'servico']
    }
    return this.server.printers.reduce((selected, printer) => {
      // sempre imprime
      if (!selected) {
        return printer
      }
      const printerIndex = modos.indexOf(printer.modo)
      const selectedIndex = modos.indexOf(selected.modo)
      // escolhe uma impressora mais adequada mesmo em outros dispositivos
      if (printerIndex > selectedIndex) {
        return printer
      }
      // mesmo tipo de impressão
      if (printerIndex == selectedIndex) {
        // usa se mesmo setor ou dispositivo
        if (
          selected.setor_id != sector_id &&
          (
            printer.setor_id == sector_id ||
            printer.dispositivo_id == this.sender.device_id
          )
        ) {
          return printer
        }
        // dá prioridade para impressora do mesmo setor e dispositivo
        if (
          selected.setor_id == sector_id &&
          printer.setor_id == sector_id &&
          printer.dispositivo_id == this.sender.device_id
        ) {
          return printer
        }
      }
      // mantém a seleção
      return selected
    }, null)
  }
}

module.exports = Router
