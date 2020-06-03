# Ponto - API
API para ponto web e mobile

## Tecnologias

Ponto usa vários projetos de código aberto para funcionar apropriadamente:

* [PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
* [Nginx] - nginx [engine x] is an HTTP and reverse proxy server, a mail proxy server, and a generic TCP/UDP proxy server.
* [MySQL] - MySQL is an open-source relational database management system.
* [Composer] - Composer is an application-level package manager for the PHP.
* [Docker] - Docker is a software technology providing operating-system-level virtualization also known as containers.

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
git clone https://github.com/wgseu/ponto.git Ponto.API
cd Ponto.API
cp .env.example .env
yarn
make start
make update
```

Antes de fazer commit, rodar os comandos abaixo com sucesso
```sh
make test
make check
```

## Deployment

```

   [PHP]: <http://www.php.net/>
   [Nginx]: <https://nginx.org/>
   [MySQL]: <https://dev.mysql.com/downloads/mysql/>
   [Composer]: <https://getcomposer.org/>
   [Docker]: <https://www.docker.com/>
   [Node.js]: <http://nodejs.org>
