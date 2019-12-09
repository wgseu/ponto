const gql = require('graphql-tag')

const query = gql`
  query {

    usuario {
      id
      proprietario
      prestador {
        id
        funcao {
          permissoes
        }
      }
    }

    dispositivo {
      id
    }

  }
`

module.exports = { query }
