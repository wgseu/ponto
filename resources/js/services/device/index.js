const queries = require('./queries/dispositivo')
const queriesWithPrinter = require('./queries/dispositivo_impressoras')
const { client } = require('../common')

const fetch = access_token => {
  return client({ authorization: access_token ? `Device ${access_token}` : '' })
    .query({ query: queries.query })
}

const fetchWithPrinter = access_token => {
  return client({ authorization: access_token ? `Device ${access_token}` : '' })
    .query({ query: queriesWithPrinter.query })
}

module.exports = { fetch, fetchWithPrinter }
