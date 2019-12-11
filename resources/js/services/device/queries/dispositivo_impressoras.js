const gql = require('graphql-tag')

const query = gql`
  query {

    dispositivo {
      id
      setor_id
      nome
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
