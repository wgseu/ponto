# Casos de Uso

1. [Dispositivos](#dispositivos)
    - 1.1. [Navegador](#navegador)
    - 1.2. [Electron](#electron)
    - 1.3. [Tablet](#tablet)
    - 1.4. [Smartphone](#smartphone)
    - 1.5. [Totem de auto atendimento](#totem)
2. [Plataformas](#plataformas)
    - 2.1. [Windows](#windows)
    - 2.2. [Android](#android)
    - 2.3. [iOS (iPhone e Mac)](#ios)
    - 2.4. [Linux](#linux)
    - 2.5. [Outros](#outros)

# 1. Dispositivos <a name="dispositivos"></a>

## 1.1 - Navegador <a name="navegador"></a>
- As vendas serão offline com fila de envio de pedido para o servidor
- Os pedidos podem ser reabertos para edição ou exclusão
- Todos os produtos serão carregados ao abrir a página
- Promoções serão carregadas para forçar a atualização dos produtos e pacotes afetados
- Composições e pacotes serão carregadas assim que o produto for montado
- Cadastros e relatórios serão exclusivamente online
- A interface deve mostrar um badge de offline no avatar e uma notificação fixa
- O usuário terá uma senha e pin para acesso
- O login com pin só funcionará em dispositivos autorizados
- A senha deve ser forte e *logins em novos dispositivos devem ser confirmados por e-mail
- A interface para mobile deve ser semelhante à do aplicativo
- A interface para desktop deve ser otimizada para lançamentos pelo teclado
- Manter no storage os pedidos não finalizados

## 1.2 - Electron <a name="electron"></a>
- Os dados do cupom devem ser carregados assim que o dispositivo for autorizado
- As informações das impressões serão enviadas por IPC quando offline
- Haverá conexão ao socket.io no electron para imprimir em background
- Será feito fetch do pedido ou serviço para imprimir
- O dispositivo será autorizado com JWT

## 1.3 - Tablet <a name="tablet"></a>
- Feito em React Native otimizado para venda em food trunk
- As vendas serão offline com fila de envio de pedido para o servidor
- Os pedidos podem ser reabertos para edição ou exclusão
- Suporte à impressão direta

## 1.4 - Smartphone <a name="smartphone"></a>
- Auto atendimento em mesas através da leitura de qrcode

## 1.5 - Totem de auto atendimento <a name="totem"></a>
- Venda balcão com interface diferenciada
- Só envia o pedido se aprovar o pagamento
- Imprime a senha e qrcode para acompanhar pelo aplicativo

# 2. Plataformas <a name="plataformas"></a>

## 2.1 - Windows <a name="windows"></a>
- Instalador 1 clique
- Impressão raw via spooler
- Suporte a TEF

## 2.2 - Android <a name="android"></a>
- Disponível na Play Store nos canais

## 2.3 - iOS (iPhone e Mac) <a name="ios"></a>
- Instalador zip e dmg
- Impressão via CUPS para Mac

## 2.4 - Linux <a name="linux"></a>
- Instalador deb, rpm e AppImage
- Impressão via CUPS

## 2.5 - Outros <a name="outros"></a>
- Acesso via navegador
