const gql = require('graphql-tag')

const query = gql`
  query {

    dispositivo {
      id
      setor_id
    }

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
