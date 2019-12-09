const gql = require('graphql-tag')

const query = gql`
  query {
    impressoras {
      data {
        id
        setor_id
        dispositivo_id
        modo
      }
    }
  }
`

module.exports = { query }
