const queries = require('./queries/info')
const { client } = require('../common')

const fetch = access_token => {
  return client({ authorization: access_token ? `Device ${access_token}` : '' }).query({ query: queries.query })
}

module.exports = { fetch }
