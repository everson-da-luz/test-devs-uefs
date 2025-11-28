# Teste técnico - Projeto UEFS - Netra
Projeto utilizado para um teste técnico, não existe uma aplicação em produção que rode esse código. Esse projeto consiste em desenvolver uma API Restful utilizando PHP, Laravel, um sistema de gerenciamento de banco de dados (SGBD) e Docker.

## Instalação
Faça o clone desse repositório:
```
git clone https://github.com/everson-da-luz/test-devs-uefs.git
```
Acesse a pasta criada:
```
cd test-devs-uefs
```
Faça uma cópia do arquivo `.env-example`:
```
cp .env.example .env
```
**Observação:** Já modifiquei o arquivo `.env-example` para não precisar alterar o arquivo `.env`, nele coloquei a conexão com o banco de dados que será criado no `docker-compose.yml`.

Após isso basta buildar o projeto com o comando:
```
docker-compose build
```
Após o build finalizado, será criado uma nova imagem Docker chamada `test-devs-uefs-everson`.

Monte e suba os containers:
```
docker-compose up
```
ou
```
docker-compose up -d
```
**Observação:** Caso use o comando `docker-compose up -d`, no terminal será mostrado que finalizou, porém ainda está sendo executado alguns comandos por trás. Então é necessário esperar alguns segundos até finalizar de fato. Caso esteja usando o comando `docker-compose up` será possível ver tudo que está sendo feito.

Quando os containers estão sendo montados, é executado o arquivo `docker/entrypoint.sh`. Nesse arquivo coloquei comandos que serão executados cada vez que os containers subirem, como por exemplo criação da pasta `vendor`, geração da key do Laravel que será inserida no arquivo `.env` e permissões de pastas necessárias para rodar aplicações Laravel.

Após os containers subirem foi criado dois containers, um para a aplicação chamado `test-devs-uefs-everson-app` rodando na porta 80 e outro para o banco de dados chamado `test-devs-uefs-everson-db` rodando na porta 3306, bem como uma rede chamada `test-devs-uefs-everson-network`.

**ATENÇÃO:** Se você tem outras aplicações ou containers rodando nas portas **80** e **3306** as mesmas devem ser finalizadas para não conflitar com os containers criados para o teste.

Rode as migrations para criar as tabelas no banco de dados:
```
docker-compose exec app php artisan migrate
```

Rode as seeds para popular um usuário na tabela users:
```
docker-compose exec app php artisan db:seed
```
Esse último comando criará um usuário de e-mail `test@example.com` com a senha `123456`.

## Endpoints da API e como utilizar
Para testar a API utilizei o programa Insomnia (https://insomnia.rest). Adicionei um arquivo com os endpoints para serem importados no Insomnia e facilitar os testes, esse arquivo encontra-se na pasta `docs/collection-uefs-everson.yaml`.

Segue as possíveis rotas da API:

### Auth (Autenticação)
#### Logar e autenticar na API (POST)
```
http://localhost/api/login
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - email
 - password

**ATENÇÃO**: Já deixei um usuário criado `test@example.com` no banco de dados, a senha dele é `123456`.

#### Deslogar da API (POST)
```
http://localhost/api/logout
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

### Users (Usuários)
#### Obtém todos usuários (GET)
```
http://localhost/api/users
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Obtém um usuário por ID (GET)
```
http://localhost/api/users/{id}
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Criar um usuário (POST)
```
http://localhost/api/users
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - name
 - email
 - password

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Editar um usuário (PUT)
```
http://localhost/api/users/{id}
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - name
 - email
 - password

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Excluir um usuário (DELETE)
```
http://localhost/api/users/{id}
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

### Posts (Postagens)
#### Obtém todas as postagens (GET)
```
http://localhost/api/posts
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Obtém uma postagem por ID (GET)
```
http://localhost/api/posts/{id}
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Criar uma postagem (POST)
```
http://localhost/api/posts
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - users_id
 - title
 - slug
 - content

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Editar uma postagem (PUT)
```
http://localhost/api/posts/{id}
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - users_id
 - title
 - slug
 - content

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Excluir uma postagem (DELETE)
```
http://localhost/api/posts/{id}
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Adicionar uma tag a uma postagem (POST)
```
http://localhost/api/posts/tag
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - posts_id
 - tags_id

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Remover uma tag de uma postagem (DELETE)
```
http://localhost/api/posts/tag
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - posts_id
 - tags_id

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.


### Tags (Tags)
#### Obtém todas as tags (GET)
```
http://localhost/api/tags
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Obtém uma tag por ID (GET)
```
http://localhost/api/tags/{id}
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Criar uma tag (POST)
```
http://localhost/api/tags
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - name

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Editar uma tag (PUT)
```
http://localhost/api/tags/{id}
```
Deve ser passado no `Body` os seguintes dados como `JSON`:
 - name

Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

#### Excluir uma tag (DELETE)
```
http://localhost/api/tags/{id}
```
Enviar o token gerado na autenticação, enviando-o como `Auth` `Bearer Token`.

## Testes
Criei tanto testes unitários na pasta `tests/Unit` quanto testes de integração na pasta `tests/Feature`.  
Para rodar os testes utilizar o seguinte comando:
```
docker-compose exec app php artisan test
```

## Visão sobre o projeto
Criei uma imagem Docker com base na imagem `php:8.4-apache`, a qual instala o PHP 8.4 e o Apache como servidor.  
Criei um middleware para verificar a existência do token e validar se o mesmo foi gerado. Também desenvolvi uma controller para cada contexto da aplicação, separando bem as responsabilidades de negócio.  
Também desenvolvi migrations, seeds, factories e testes.

De banco de dados instalei o MySQL 8.4, e criei 4 tabelas:
- users
- posts
- tags
- posts_tags

Vou deixar o diagrama ER na pasta `docs/er-uefs-everson.png`.  
Criei models bem estruturadas, com responsabilidades únicas e com os relacionamentos definidos.