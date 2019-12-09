const queries = require('./queries/usuario')
const queriesWithDevice = require('./queries/usuario')
const { client } = require('../common')

const fetch = access_token => {
  return client({ authorization: access_token ? `Bearer ${access_token}` : '' }).query({ query: queries.query })
}

const fetchWithDevice = (access_token, device_token) => {
  const auths = []
  if (access_token) {
    auths.push(`Bearer ${access_token}`)
  }
  if (device_token) {
    auths.push(`Device ${device_token}`)
  }
  return client({ authorization: auths.join(', ') })
    .query({ query: queriesWithDevice.query })
}

module.exports = { fetch, fetchWithDevice }
