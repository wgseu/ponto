const gql = require('graphql-tag')

const query = gql`
  query {

    usuario {
      id
      nome
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
      setor_id
      nome
    }

  }
`

module.exports = { query }
