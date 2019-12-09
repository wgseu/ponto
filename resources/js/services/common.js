const fetch = require('node-fetch')
const ApolloClient = require('apollo-boost').default

const client = (headers = {}) => {
  return new ApolloClient({ uri: `${process.env.APP_URL}/graphql`, headers, fetch })
}

module.exports = {
  client,
}
