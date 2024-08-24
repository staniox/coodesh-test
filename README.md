# Backend Challenge 20230105

## Descrição

Este projeto é uma API REST desenvolvida para consumir e processar os dados do Open Food Facts, permitindo que nutricionistas da empresa Fitness Foods LC revisem rapidamente as informações nutricionais dos alimentos.

## Tecnologias Utilizadas

- **Linguagem:** PHP 8.2
- **Framework:** Laravel 11
- **Banco de Dados:** MongoDB
- **Outras Tecnologias:** Docker, Docker Compose

## Documentação do Processo de Desenvolvimento

Para mais detalhes sobre o processo de desenvolvimento e detalhes da documentação da API, consulte a [Documentação do Processo de Desenvolvimento](PROCESSODEV.md).


## Como Instalar e Usar o Projeto

### Passos para Instalação

1. Clone o repositório do projeto

Este passo cria uma cópia local do repositório GitHub, que contém todo o código e arquivos necessários para o projeto.

```bash
 git clone https://github.com/staniox/coodesh-test
```
2. Acesse o diretório do projeto

Navegue até o diretório do projeto que você acabou de clonar para executar os 
Comandos seguintes.

```bash
 cd coodesh-test
 ```
3. Copie o arquivo de exemplo .env para criar um arquivo de configuração real

O arquivo .env é usado para configurar as variáveis de ambiente da aplicação, como conexões de banco de dados e outras configurações sensíveis.

```bash
 cp .env.example .env
```
4. Construa as imagens Docker

Esse 
Comando constrói as imagens Docker definidas no arquivo docker-compose.yml, preparando o ambiente para execução.

```bash
 docker-compose build
 ```
5. Inicie os containers em segundo plano

Esse 
Comando inicia os containers Docker necessários para rodar a aplicação, como o servidor da aplicação e o banco de dados MongoDB.

```bash
 docker-compose up -d
 ```
6. Execute as migrações e seeders do banco de dados

Isso cria as tabelas no banco de dados e preenche com dados iniciais necessários para a aplicação funcionar corretamente.

```bash
 docker-compose exec app php artisan migrate --seed -q
 ```
7. Gere uma chave de aplicação do Laravel

A chave de aplicação é utilizada para criptografia de dados, e este 
Comando a gera e armazena no arquivo .env.

```bash
 docker-compose exec app php artisan key:generate
 ```
8. Ajuste as permissões do diretório da aplicação

Modifica as permissões do diretório /var/www dentro do container para garantir que o servidor web tenha acesso apropriado.

```bash
 docker-compose exec app chmod -R 755 /var/www
 ```
9. Ajuste as permissões do banco de dados SQLite

Isso garante que o arquivo do banco de dados SQLite tenha permissões de leitura, escrita e execução para todos os usuários.

```bash
 docker-compose exec app chmod -R 777 /var/www/database/database.sqlite
 ```
10. Modifique as permissões do diretório `/var/www` dentro do container para garantir que o servidor web tenha acesso apropriado:

   ```bash
   docker-compose exec app chmod -R 755 /var/www
   ```
11. Execute os testes automatizados

Executa os testes unitários da aplicação para validar que as funcionalidades estão funcionando corretamente.

```bash
 docker-compose exec app php artisan test
 ```
12. Inicialize a réplica set do MongoDB

Configura o MongoDB com uma réplica set utilizando um script customizado, necessário para o funcionamento da aplicação.

```bash
 docker exec -it mongo mongosh docker-entrypoint-initdb.d/init-replica-set.js
 ```
13. Importe os produtos do Open Food Facts

Executa o 
Comando Artisan que importa manualmente os produtos para o banco de dados, útil se você quiser realizar a importação antes que o cron job automático ocorra.

```bash
 docker-compose exec app php artisan products:import
```

### Endpoints da API

- **`GET /`**: Retorna o status atual da API.
- **`GET /test_mongo`**: Testa a conexão com o banco de dados MongoDB.
- **`GET /products`**: Lista todos os produtos.
- **`GET /products/{code}`**: Retorna um produto específico pelo código.
- **`PUT /products/{code}`**: Atualiza um produto específico pelo código.
- **`DELETE /products/{code}`**: Remove um produto específico pelo código.
- **`GET /api/documentation`**: Acessa a documentação da API gerada pelo Swagger/OpenAPI.


## Variáveis de Ambiente

A aplicação utiliza algumas variáveis de ambiente para configurar o ambiente de execução. Abaixo estão as variáveis e suas funções:

- **`IMPORT_TIME`**: Define a hora em que a importação dos produtos do Open Food Facts deve ser realizada. O formato é `HH:MM` (por exemplo, `12:00` para executar a importação ao meio-dia).

- **`MONGO_URI`**: Define a URL de conexão com o banco de dados MongoDB. No ambiente Docker, o valor padrão é `mongodb://mongo:27017`, que aponta para o container MongoDB.

- **`MONGO_DATABASE`**: Define o nome do banco de dados MongoDB a ser utilizado. O valor padrão é `coodesh` e não é necessário alterar se você estiver usando a configuração padrão.


## Referência

This is a challenge by Coodesh

