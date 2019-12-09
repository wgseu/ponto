const gql = require('graphql-tag')

const query = gql`
  query {
    dispositivo {
      id
      setor_id
    }
  }
`

module.exports = { query }
