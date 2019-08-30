# Especificação

1. [Introdução](#introducao)
    - 1.1. [Público alvo](#alvo)
    - 1.2. [Finalidade](#finalidade)
2. [Funcionalidades](#funcionalidades)
    - 2.1. [Mesas](#mesas)
    - 2.2. [Comandas](#comandas)
    - 2.3. [Balcão](#balcao)
    - 2.4. [Entrega](#entrega)
    - 2.4. [Site Delivery](#site_delivery)
    - 2.5. [Módulo TEF](#tef)
    - 2.6. [Módulo fiscal](#fiscal)
3. [Usabilidade](#usabilidade)
4. [Tecnologias](#tecnologias)

# 1. Introdução <a name="introducao"></a>

## 1.1 - Público alvo <a name="alvo"></a>
- PDV - Micro empresas que realizam vendas em balcão
- Mesas, Comandas, Balcão e Entrega - Restaurantes e relacionados
- Tudo + Entrega online - Restaurantes e relacionados

## 1.2 - Finalidade <a name="finalidade"></a>
- PDV - Realizar vendas rápidas offline com acompanhamento web
- Padrão - Fazer vendas offline em mesas, comandas, balcão e para entrega
- Entrega online - Permitir que os clientes façam pedidos online

# 2. Funcionalidades <a name="funcionalidades"></a>
Para todas as vendas
  * Pagar apenas um produto do item e marcar como pago
  * Cancelar apenas um produto do item
  * Informações de preparo do pedido
  * Informações de preparo do item
  * Múltiplos pagamentos

## 2.1 - Mesas <a name="mesas"></a>
- Lista de mesas separadas por estado
- Listar produtos da mesa selecionada
- Impressão da conta da mesa
- Juntar com outra mesa
- Mudar para outra mesa
- Mudar para venda delivery / avulsa
- Mover produtos de uma mesa para outra
- Pagar todas as comandas da mesa

## 2.2 - Comandas <a name="comandas"></a>
- Lista de comandas separadas por estado
- Comanda pré-paga (Só adiciona se tiver saldo)
- Mudar a comanda de mesa
- Mudar para venda delivery / avulsa
- Criar comanda dinâmica, gerada ao abrir
- Nomear comanda com cadastro de cliente avançado

## 2.3 - Balcão <a name="balcao"></a>
- Campo de busca ou digitação do código de barras
- Processamento das outras vendas, exceto delivery mas inclui viagem

## 2.4 - Entrega <a name="entrega"></a>
- Realizar várias vendas simultâneas
- Suporte à identificador de chamadas para identificar o cliente
- Possibilidade de não gerenciar a entrega
- Mudar uma entrega para viagem e vice-versa
- Agendar uma entrega para outro dia ou horário

## 2.4 - Site Delivery <a name="site_delivery"></a>
- Permitir o cliente fazer o próprio cadastro e pedido
- Permitir o cliente acompanhar os pedidos dele
- Permitir o cliente ver os cupons dele

## 2.5 - Módulo TEF <a name="tef"></a>
- Permitir realizar pagamentos sem informar a bandeira do cartão

## 2.6 - Módulo fiscal <a name="fiscal"></a>
- Emissão de NFC-e normal e em contingência
- Gerenciamento das notas
- Download das notas da busca
- Envio das notas da busca para o contador por E-mail
- Envio da nota para o cliente por E-mail

# 3. Usabilidade <a name="usabilidade"></a>
- Não possuir caixas de diálogo para realizar operações com agilidade
- Informar de alterações não salvas e se deseja sair mesmo assim
- Manter o usuário informado da operação que ele está fazendo
- Não mostrar caixa de diálogo em caso de operação realizada com sucesso
- Não criar animações em operações de agilidade
- Criar abas ou mini drawer quando tiver muitos campos (evitar utilizar scroll)
- Usar loading com shimmer
- Desabilitar os campos ao enviar dados

# 4. Tecnologias <a name="tecnologias"></a>
- PHP 7 para api online
- GraphQL para consulta à api
- Electron para impressão, balança, TEF, Contingência NFC-e, Identificador
- Vue.js com Quasar para aplicativo web
- React Native com React Native Elements para Android e iOS
- Docker para criar as imagens dos servidores
- Kubernates para orquestrar as imagens
- Gitlab para CI/CD
- MySQL como banco de dados
- Bucket para disponibilizar downloads
