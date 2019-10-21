# GrandChef - Site
Site do GrandChef que fornece API para tablets, gerenciamento e possibilita backup de documentos e outras mídias.

  - Publica o cardápio online
  - Permite o gerenciamento web do sistema
  - Possibilita a comunicação com tablets com acionamento de impressão
  - Permite fazer cópia de segurança de notas fiscais e outros documentos

# Novos recursos!

  - Integração com iFood

Você pode também:
  - Associar produtos e cartões do iFood com os do GrandChef
  - Visualizar gráficos de vendas detalhados
  - Baixar arquivos exportados para Excel

### Tecnologias

GrandChef usa vários projetos de código aberto para funcionar apropriadamente:

* [PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
* [Nginx] - nginx [engine x] is an HTTP and reverse proxy server, a mail proxy server, and a generic TCP/UDP proxy server.
* [MySQL] - MySQL is an open-source relational database management system.
* [Composer] - Composer is an application-level package manager for the PHP.
* [Docker] - Docker is a software technology providing operating-system-level virtualization also known as containers.
* [Start Bootstrap - Agency] - Agency is a one page agency portfolio theme for Bootstrap created by Start Bootstrap.
* [Gentelella Admin] - Gentelella Admin is a free to use Bootstrap admin template.
* [Twitter Bootstrap] - The most popular HTML, CSS, and JS library in the world.
* [node.js] - Node.js is an open-source, cross-platform JavaScript run-time environment for executing JavaScript code server-side
* [Gulp] - gulp is a toolkit for automating painful or time-consuming tasks in your development workflow
* [jQuery] - The Write Less, Do More, JavaScript Library.

### Dependências
- Git
- make
- msys
- Docker
- Yarn

### Instalação

Instale as dependências e inicie o servidor.
```sh
git clone git@gitlab.com:grandchef/product/api.git GrandChef.Product.API
cd GrandChef.Product.API
cp .env.example .env
yarn
make start
make update
make migrate
yarn dev
```

   [PHP]: <http://www.php.net/>
   [Nginx]: <https://nginx.org/>
   [MySQL]: <https://dev.mysql.com/downloads/mysql/>
   [Composer]: <https://getcomposer.org/>
   [Docker]: <https://www.docker.com/>
   [Start Bootstrap - Agency]: <https://github.com/BlackrockDigital/startbootstrap-agency>
   [Gentelella Admin]: <https://github.com/puikinsh/gentelella>
   [node.js]: <http://nodejs.org>
   [Twitter Bootstrap]: <http://twitter.github.com/bootstrap/>
   [Gulp]: <http://gulpjs.com>
   [jQuery]: <http://jquery.com>

### Deployment

Cria uma imagem do docker
```sh
docker build -t gcr.io/upheld-setting-221119/grandchef-product-api:v1.0 .
```

Envia nova versão para repositório
```sh
docker push gcr.io/upheld-setting-221119/grandchef-product-api:v1.0
```
