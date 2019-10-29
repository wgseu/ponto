# GrandChef - API
API GraphQL do GrandChef para o aplicativo web e mobile.

## Tecnologias

GrandChef usa vários projetos de código aberto para funcionar apropriadamente:

* [PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
* [Nginx] - nginx [engine x] is an HTTP and reverse proxy server, a mail proxy server, and a generic TCP/UDP proxy server.
* [MySQL] - MySQL is an open-source relational database management system.
* [Composer] - Composer is an application-level package manager for the PHP.
* [Docker] - Docker is a software technology providing operating-system-level virtualization also known as containers.
* [Node.js] - Node.js is an open-source, cross-platform JavaScript run-time environment for executing JavaScript code server-side

## Dependências
- Git
- make
- Docker
- PHP 7.3
  - Extensões: mbstring, sqlite, mysql, pdo, gd, xml, curl
- xdebug
- Yarn

## Instalação

Instale as dependências e inicie o servidor.
```sh
git clone git@gitlab.com:grandchef/product/api.git GrandChef.Product.API
cd GrandChef.Product.API
cp .env.example .env
yarn
make start
make update
make migrate
```

   [PHP]: <http://www.php.net/>
   [Nginx]: <https://nginx.org/>
   [MySQL]: <https://dev.mysql.com/downloads/mysql/>
   [Composer]: <https://getcomposer.org/>
   [Docker]: <https://www.docker.com/>
   [Node.js]: <http://nodejs.org>

Antes de fazer commit, rodar os comandos abaixo com sucesso
```sh
make test
make check
```

## Deployment

Cria uma imagem do docker
```sh
docker build -t gcr.io/upheld-setting-221119/grandchef-product:beta .
```

Envia nova versão para repositório
```sh
docker push gcr.io/upheld-setting-221119/grandchef-product:beta
```

Roda o container e aguarda
```sh
docker run -it -e CONTAINER_ROLE=app gcr.io/upheld-setting-221119/grandchef-product:beta
```
