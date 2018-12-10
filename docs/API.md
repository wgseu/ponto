# GrandChef API

## Criação de pedido com pagamento e adição de itens
* Endpoint de criação ```/api/pedidos``` método ```POST```
* Endpoint de atualização ```/api/pedidos/{id}``` método ```PATCH``` onde ```{id}``` é o id do pedido existente
```javascript
Pedido: {
  tipo: 'Mesa|Comanda|Avulso|Entrega', // tipo de pedido
  mesaid: Number, // id da mesa se pedido para mesa ou para informar o local
  comandaid: Number, // id da comanda se pedido para mesa
  clienteid: Number, // id do cliente
  descricao: String, // Observação do pedido
  pessoas: Number, // Quantidade de pessoas na mesa
  cancelado: String, // Y para cancelar o pedido
  motivo: String, // Motivo do cancelamento
  estado: 'Agendado|Finalizado', // se está concluindo o pedido ou agendando, nulo cria um pedido aberto
  pedidoid: Number, // junta o pedido atual com esse informado
  localizacaoid: Number, // id do endereço para entrega
  dataagendamento: Date, // Data para agendamento do pedido
  itens: Item[], // Itens do pedido
  pagamentos: Pagamento[], // Pagamentos do pedido,
  pedidos: Number[], // lista de id de pedidos que serão associados
}
```
```javascript
Item: {
  servicoid: Number, // id do serviço prestado
  produtoid: Number, // id do produto vendido
  preco: Number, // preço vendido
  precovenda: Number, // preço original sem desconto
  quantidade: Number, // quantidade do produto
  detalhes: String, // observações do item,
  descricao: String, // descrição do pacote
  itemid: Number, // id do item principal
  formacoes: Formacao[], // formações do item
}
```
```javascript
Formacao: {
  tipo: 'Pacote|Composicao', // tipo de formação
  composicaoid: Number, // id do produto vendido
  pacoteid: Number, // preço vendido
  quantidade: Number, // quantidade de itens dessa formação
}
```
## Criação e lançamento de pagamentos
* Endpoint de criação ```/api/pagamentos``` método ```POST```
* Endpoint de atualização ```/api/pagamentos/{id}``` método ```PATCH``` onde ```{id}``` é o id do pagamento existente
```javascript
Pagamento: {
  lancado: Number, // valor lançado para o pedido ou conta
  moedaid: Number, // id da moeda que foi pago
  valor: Number, // valor na moeda em que foi pago
  pedidoid: Number, // id do pedido que será pago
  contaid: Number, // id da conta que será paga
  pagamentoid: Number, // id do pagamento principal
  formapagtoid: Number, // id da forma de pagamento
  cartaoid: Number, // id do cartão selecionado
  cheque: Cheque, // dados do cheque que será pago
  crediario: Conta, // dados da conta que será criada
  credito: Credito, // dados do crédito que será convertido ou usado,
  parcelas: Number, // quantidade de parcelas no cartão
  detalhes: String, // detalhes do pagamento
  estado: 'Aberto|Cancelado', // para pedido que será pago ou para cancelar o pagamento
  movimentacaoid: Number, // id do caixa selecionado, nulo pega o caixa do funcionário
  itens: Number[], // lista de id de itens que serão marcados como pagos
}
```
```javascript
Cheque: {
  clienteid: Number, // cliente que entregou o cheque
  valor: Number, // valor do cheque na moeda local
  bancoid: Number, // id do banco do cheque
  agencia: String, // número da agência que será debitado o cheque
  conta: String, // número da conta que será debitado o cheque
  numero: String, // Número da folha do cheque
  vencimento: Date, // data de vencimento do cheque
}
```
```javascript
Credito: {
  clienteid: Number, // cliente que movimentará o crédito
  valor: Number, // valor do crédito na moeda local
}
```
