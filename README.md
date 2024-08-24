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

Para documentar e testar a API, usei OpenAPI (anteriormente Swagger). A documentação inclui:

- **Endpoints**: Listagem dos endpoints disponíveis, como `/import` para iniciar uma importação e `/status` para verificar o status da importação.
- **Métodos HTTP**: Descrição dos métodos suportados para cada endpoint, como GET, POST, PUT, DELETE.
- **Parâmetros**: Detalhes sobre os parâmetros aceitos pelos endpoints, incluindo cabeçalhos, parâmetros de consulta e corpo da requisição.
- **Respostas**: Estrutura das respostas da API, incluindo códigos de status HTTP e exemplos de payloads de resposta.
- **Exemplos**: Exemplos de chamadas de API e respostas esperadas para facilitar o entendimento e o uso da API.

## Resultados

A solução desenvolvida permitiu a importação eficiente de produtos a partir de arquivos JSON, garantindo que dados duplicados não fossem processados. O processo foi otimizado para lidar com grandes volumes de dados e pode ser facilmente escalado para lidar com novas importações sem a necessidade de duplicar registros.

## Ponto de Melhoria

Implementação de um sistema de importação incremental que poderia continuar de onde parou caso uma importação anterior tivesse sido interrompida.

## Conclusão

Este projeto foi uma oportunidade de explorar e implementar técnicas de importação de dados em um ambiente de produção. Documentar cada passo do processo foi essencial para garantir que as decisões tomadas fossem bem fundamentadas e que o código final fosse de alta qualidade.
