class Syncronizer {
  constructor(sender, data) {
    this.sender = sender
    this.data = data
  }

  other (client) {
    return client.id !== this.sender.id
  }

  allow (client) {
    return (client.type == 'user' && client.employee_id) || (client.type == 'device')
  }
}

module.exports = Syncronizer
