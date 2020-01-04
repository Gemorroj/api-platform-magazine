# api platform simple magazine backend

### Requires
- 7.3+ (Symfony 5)
- Mysql/Postgres/ some other `dbal` databases


### Installation
- install web-server (document root directory is `public/`), RDBMS, composer
- create `.env.local` file with same data from `.env`, configure it
- create private/public keys for jwt
- install composer dependencies `composer insatll`
- create database (`bin/console doctrine:database:create` and `bin/console doctrine:schema:update --force`)
- create user `bin/console app:user-create <email> <password>`
- start web-server
- create POST request with json `{"email": "<email>", "password": "<password>"}`, remember token
- use swagger with remembered bearer token
