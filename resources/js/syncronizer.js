class Syncronizer {
  constructor(sender, data) {
    this.sender = sender
    this.data = data
  }

  allow (client) {
    return client.type == 'user' && client.employee_id && this.sender.employee_id
  }
}


module.exports = Syncronizer
