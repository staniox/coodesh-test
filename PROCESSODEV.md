# Documentação do Processo de Desenvolvimento

## Introdução

Este documento detalha o processo de desenvolvimento e as decisões tomadas para a realização da atividade de importação de produtos a partir de arquivos JSON. São abordadas as etapas de investigação, as hipóteses consideradas, as soluções implementadas e os resultados obtidos.

## Objetivo

O objetivo deste projeto foi desenvolver uma funcionalidade que importasse produtos a partir de arquivos JSON disponibilizados por uma URL externa. A importação deveria ser feita de forma eficiente, garantindo que produtos duplicados não fossem importados e que os dados fossem persistidos corretamente em um banco de dados MongoDB.

## Hipóteses e Decisões Iniciais

### Estruturação do Processo de Importação

- **Hipótese**: A importação poderia ser feita em lotes de 100 produtos por vez, para otimizar o desempenho.
- - **Decisão**: A importação foi implementada em lotes de 100 produtos por vez, conforme descrito nos requisitos do teste, que indicavam que apenas 100 produtos seriam importados por execução e o restante ficaria para a próxima execução da cron.

### Persistência dos Dados

- **Hipótese**: Um modelo de histórico de importação seria necessário para rastrear quais arquivos foram importados e evitar duplicações.
- **Decisão**: Criei o modelo `ImportHistory` para armazenar informações sobre cada arquivo importado, incluindo a data e o status da importação.

### Manuseio de Caracteres Especiais

- **Hipótese**: Os códigos de produtos poderiam conter caracteres especiais que precisariam ser filtrados.
- **Decisão**: Implementei uma função para remover caracteres não numéricos dos códigos de produtos, garantindo que apenas os números fossem armazenados.

## Implementação

### Estruturação do Código

- **ProductImportService**: Serviço responsável por gerenciar todo o processo de importação, desde o download dos arquivos até a inserção dos produtos no banco de dados.
- **ProductService**: Serviço responsável por gerenciar a lógica de negócios relacionada aos produtos, como a verificação de duplicatas e a inserção dos produtos no banco de dados.
- **ApiStatusService**: Serviço responsável por gerenciar o status da importação e garantir que o progresso seja registrado corretamente. Inclui funcionalidades para verificar e atualizar o status da importação.

### Importação Incremental

Para garantir que produtos já importados não fossem processados novamente, implementei um mecanismo de controle de duplicatas com base no campo `code` de cada produto. A lógica foi a seguinte:

- Antes de inserir um novo produto, verifiquei se já existia um registro com o mesmo `code`.
- Se o `code` já existisse, o produto não foi importado novamente.
- Registrei o progresso da importação no modelo `ImportHistory`, incluindo o nome do arquivo, a data de importação e o status.

### Tratamento de Erros

Durante o desenvolvimento, identifiquei a necessidade de tratar possíveis erros durante a importação, como falhas na leitura de arquivos ou JSONs malformados. Para isso, implementei as seguintes medidas:

- **Logging de Erros**: Todos os erros foram registrados em logs para facilitar o diagnóstico de problemas.
- **Validação de JSON**: Implementei uma validação do JSON para garantir que apenas produtos válidos fossem processados.

### Documentação da API

Para documentar e testar a API, foi utilizado OpenAPI (anteriormente Swagger). A documentação está acessível no endpoint `/api/documentation` e cobre os seguintes aspectos:

- **Endpoints**:
    - **`GET /`**: Retorna o status atual da API.
    - **`GET /test_mongo`**: Testa a conexão com o banco de dados MongoDB e retorna uma mensagem de confirmação ou erro.
    - **`GET /products`**: Lista todos os produtos.
    - **`GET /products/{code}`**: Retorna um produto específico baseado no código fornecido.
    - **`PUT /products/{code}`**: Atualiza um produto específico baseado no código fornecido.
    - **`DELETE /products/{code}`**: Remove um produto específico baseado no código fornecido.

- **Métodos HTTP**:
    - **GET**: Para recuperar dados dos endpoints.
    - **PUT**: Para atualizar dados existentes.
    - **DELETE**: Para remover dados.

- **Parâmetros**:
    - **Cabeçalhos**: Informações adicionais para as requisições, se necessário.
    - **Parâmetros de Consulta**: Detalhes sobre qualquer parâmetro adicional incluído na URL, se aplicável.
    - **Corpo da Requisição**: Dados enviados com a requisição, especialmente para métodos PUT.

- **Respostas**:
    - **Estrutura das Respostas**: Inclui códigos de status HTTP e exemplos de payloads de resposta para cada endpoint.

- **Exemplos**:
    - **Chamadas de API**: Exemplos de como fazer chamadas para cada endpoint.
    - **Respostas Esperadas**: Exemplos das respostas que a API retornará para ajudar no entendimento e uso da API.

### Utilização de Docker e Containers

Para facilitar o desenvolvimento e a implantação, o projeto foi containerizado utilizando Docker. O Docker permite criar ambientes isolados e reproduzíveis, o que é crucial para garantir que o aplicativo funcione da mesma forma em diferentes ambientes de desenvolvimento e produção.

Os principais componentes do projeto são executados em containers Docker, incluindo:

- **Aplicação Laravel**: Executa o servidor da aplicação e os serviços necessários.
- **Banco de Dados MongoDB**: Utiliza um container separado para gerenciar o banco de dados, configurado para trabalhar com o serviço da aplicação.
- **Nginx**: Utiliza um container para servir como servidor web reverso, gerenciando as requisições HTTP e direcionando-as para o container da aplicação Laravel. O Nginx também é responsável por fornecer a camada de segurança e otimização de desempenho para o aplicativo.

Utilizando Docker Compose, é possível gerenciar a configuração e o ciclo de vida dos containers de forma eficiente, garantindo que todos os serviços necessários sejam iniciados e configurados corretamente com um simples comando.

## Resultados

A solução desenvolvida permitiu a importação eficiente de produtos a partir de arquivos JSON, garantindo que dados duplicados não fossem processados. O processo foi otimizado para lidar com grandes volumes de dados e pode ser facilmente escalado para lidar com novas importações sem a necessidade de duplicar registros.

## Ponto de Melhoria

Implementação de um sistema de importação incremental que poderia continuar de onde parou caso uma importação anterior tivesse sido interrompida.

## Conclusão

Este projeto foi uma oportunidade de explorar e implementar técnicas de importação de dados em um ambiente de produção. Documentar cada passo do processo foi essencial para garantir que as decisões tomadas fossem bem fundamentadas e que o código final fosse de alta qualidade.
