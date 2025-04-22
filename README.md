# Laravel Chamados

Este é um sistema de gerenciamento de **Chamados**, onde você pode **criar**, **editar**, **visualizar** e **excluir** chamados. O sistema permite que os chamados sejam registrados com categorias e situações e oferece métricas relacionadas a esses chamados.

## Funcionalidades

### CRUD para Chamados:

- **Criar Chamado**: Abertura de novos chamados com categorias e descrição.
- **Editar Chamado**: Alteração de dados do chamado, incluindo a situação e o prazo de solução.
- **Excluir Chamado**: Remoção de chamados existentes.
- **Visualizar Chamado**: Exibição dos chamados cadastrados.

### Gerenciamento de Situação:

O sistema permite alterar a situação dos chamados para:

- **Pendente**
- **Resolvido** (ao selecionar "Resolvido", o campo **Data de Solução** será preenchido automaticamente com a data atual).

### Métricas:

Exibição de métricas relacionadas aos chamados, como total de chamados e chamados resolvidos dentro do prazo.

---

## Requisitos

- **PHP** >= 8.3.16
- **Laravel** >= 12.x
- **React** >= 19.1.0
- **Yarn**

- **Composer** para gerenciamento de dependências
- **Banco de dados**: PostgreSQL

---

## Instalação Back-End

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/nome-do-repositorio.git
```

### 2. Instalar dependências
Navegue até o diretório do projeto e execute o comando para instalar as dependências:

```bash
cd nome-do-repositorio
composer install
```

### 3. Configurar o ambiente
Crie o arquivo .env a partir do .env.example:

```bash
cp .env.example .env
```

Configure as variáveis do banco de dados no arquivo .env

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=database-plss
DB_USERNAME=admin
DB_PASSWORD=admin
```

1 Rodar o comando para o Docker

```bash
docker compose up -d
```

2 Execute o comando para gerar a chave do aplicativo:

```
php artisan key:generate
```

3 Rodar o comando para as migrations

```
php artisan migrate
```

4 Rodar a seeder

```
php artisan db:seed --class=DatabaseSeeder
```

5 Rodar a aplicação
```
php artisan serve
```

## Instalação Front-end

1 Rodar o comando para baixar as dependências
```
yarn 
```

2 Subir aplicação

```
yarn start
```
