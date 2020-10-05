# Adressen

## Sobre  
  
_Adressen (endereços)_ é um simples microserviço para gerenciamento de endereços de uma pessoa.  
  
## Instalação  

### Ambiente  
Para rodar a aplicação é necessário ter o *Docker* e *Docker Compose* instalados. Para instalar, basta seguir as instruções de instalação para o [Docker](https://docs.docker.com/get-docker/)  e [Docker Compose](https://docs.docker.com/compose/install/).

Após a instalação, execute os seguintes comandos para configurar o ambiente da aplicação:

> *Obs.: Caso o usuário do sistema operacional tenha um ID diferente de
> **1000**, altere a propriedade **UID** no arquivo **.env** para o valor correto. Isso é necessário para configurar corretamente as permissões dos arquivos da aplicação no sistema de arquivos.*

```sh
git clone https://github.com/hertz1/arquiteto-php.git
cd arquiteto-php
cp .env.example .env
docker-compose up -d
docker exec adressen php artisan key:generate
```

Após o download e construção das imagens o ambiente estará configurado.

### Banco de Dados
Para popular as tabelas e dados no banco de dados da aplicação, basta executar o seguinte comando:

```sh
docker exec adressen php artisan migrate --seed
```

## Visão Geral da Arquitetura

### Infraestrutura

A aplicação foi construída utilizando a abordagem arquitetural de microsserviços. Essa abordagem proporciona diversos benefícios, como escalabilidade, resiliência, implantações e atualizações mais ágeis, facilidade de desenvolvimento e manutenção, entre outros.

Conteinerização através do *Docker* permite a execução da aplicação da maneira fidedigna independente de sistema operacional e hardware. Por fim, orquestração através do *Docker Compose* facilita o gerenciamento dos serviços.

### API

Uma API permite integração com outros sistemas de maneira simples, requerendo apenas um *token* de autorização no formato JWT (*Json Web Token*). A documentação da API pode ser vista [neste link](https://app.swaggerhub.com/apis/danilo-azevedo/Adressen).

### Aplicação

A aplicação foi desenvolvida na linguagem de programação [PHP](https://www.php.net) com o framework [Laravel](https://laravel.com). Explicações mais detalhadas sobre os componentes da aplicação podem ser encontradas logo a seguir.

#### Controllers

*Controllers* agrupam lógicas de tratamento de requisição em uma classe. No geral, devem tratar apenas I/O, ou seja, ao receber um [requisição](#requests), devem devolver uma resposta adequada. Não devem conter regras de negócio nem acesso a serviços externos, como bancos de dados, servidores de e-mail, etc. Para isso, utilizamos os [Services](#services).

#### Requests

##### Validation & Authorization

Toda lógica de validação e autorização deve ser feita utilizando [`Form Requests`](https://laravel.com/docs/validation#form-request-validation) em conjunto com [`Policies`](https://laravel.com/docs/authorization#creating-policies).

#### Responses

As respostas da aplicação são definidas utilizando [`API Resources`](https://laravel.com/docs/eloquent-resources). São classes responsáveis por definir como uma determinada informação deve ser serializada na resposta.


#### Services

*Services* são objetos que possuem uma responsabilidade bem definida. Neles são definidas regras de negócio da aplicação e comunicação com serviços externos.

#### Models

*Models* representam um registro de uma determinada tabela no banco de dados. Também podem ser utilizados para definir regras de negócio específicas para um determinado *Model*.

#### Repositories

*Repositories* são classes cujo único objetivo é centralizar a comunicação da aplicação com o [Banco de Dados](#banco-de-dados). Como o [ORM](https://pt.wikipedia.org/wiki/Mapeamento_objeto-relacional) padrão do Laravel (Eloquent) utiliza [Active Record](https://pt.wikipedia.org/wiki/Active_record), em tese o uso de *repositories* não se faz necessário, porém é uma forma de centralizar *queries* mais complexas e aplicar uma separação de responsabilidades entre as classes.

### Banco de Dados

A modelagem do banco de dados foi feita de forma a suportar a estrutura de endereço de vários países. Por exemplo, no Brasil poderíamos representar a estrutura de um endereço na seguinte árvore:

```
|-- País
|   |-- Estado
|   |   |-- Município
|   |   |   |-- Bairro
|   |   |   |   |-- Cep
```

O modelo entidade-relacionamento poderia ser desenhado desta forma:
![MER](https://i.imgur.com/jzpG2km.png)
Porém, em outro país a estrutura de endereço pode ser completamente diferente. Para solucionar esse problema, ao invés de criar mais tabelas e *desnormalizar* a estrutura do banco de dados, implementamos uma estrutura de [_Nested Set Model_](https://en.wikipedia.org/wiki/Nested_set_model).
Dessa forma, desenhamos nossa arquitetura de forma hierárquica, no qual podemos definir uma estrutura de relacionamento entre localidades de forma a atender qualquer estrutura:

![MER](https://i.imgur.com/SJAZv6d.png)
O projeto já vem pré-configurado com as localidades brasileiras de UF e Município nesta estrutura, sendo possível estender com Municípios e Bairros de forma simples.

###### *Modelo baseado [neste artigo](https://danielcoding.net/multi-country-address-database-design/).*

### Cache

Uma parte importante de qualquer aplicação é o *cache*. Se bem configurado, a carga no banco de dados é drasticamente reduzida, a quantidade de requisições por segundo da aplicação é ampliada e é possível até reduzir custos de infraestrutura.

Para o gerenciamento de *cache* da aplicação é utilizado o [Redis](https://redis.io), que é um servidor de armazenamento em rede de dados de chave-valor. É altamente performático, sendo utilizado por diversas aplicações de alta demanda.

## Testando a aplicação

Ao executar as instruções contidas na seção [**Banco de Dados**](#banco-de-dados), a base será populada com vários endereços aleatórios. Entre eles, há usuário pré-definido com UUID `3c472ed8-87c8-4fee-a51a-c0401e9507f8`, o qual utilizaremos para testar a aplicação.

### API Token

O próximo passo é gerar um token válido para autenticar na aplicação. Como não há integração com um serviço de autenticação, utilizaremos o site [Online JWT Builder](http://jwtbuilder.jamiekurtz.com/) para construir o token. Ao abrir o site, é necessário alterar apenas os seguintes campos:
 
 - **Expiration**: Clique em **`in 20 minutes`** para definir que o token expire em 20 minutos após o horário atual.
 - **Subject**: Aqui definiremos o UUID do usuário do token. Neste caso, será o usuário pré-definido (`3c472ed8-87c8-4fee-a51a-c0401e9507f8`).
 - **Additional Claims**: Podemos remover todos os *claims*, para isso cliquem em **`clear all`**.
 - **Key**: Neste campo devemos informar a chave que será utilizada para encriptar o token. Podemos utilizar o valor pré-definido na variável `API_SECRET` no arquivo `.env` ou gerar outra chave. Caso utilize outra chave, é necessário atualizar o valor de `API_SECRET` com a nova chave. O algoritmo utilizado deve ser **`HS512`**.

Clique em *`Create Signed JWT`* e guarde o token gerado.

