# GUP - General User Profile

## Descrição do Projeto

O **GUP (General User Profile)** é uma simples aplicação web desenvolvida para reforçar técnicas fundamentais no desenvolvimento full-stack com Laravel e Vue.js.

A aplicação permite o gerenciamento de perfis de usuários, sendo o perfil ADMINISTRADOR o único com acesso total. A partir dele, é possível gerenciar outros perfis e usuários, atribuindo perfis específicos a cada usuário de forma simples e dinâmica.

### Arquitetura

A aplicação segue uma arquitetura moderna de separação de responsabilidades:

- **Backend**: API em Laravel
- **Frontend**: SPA em Vue.js 3
- **Banco de Dados**: PostgreSQL
- **Containerização**: Docker Compose para desenvolvimento

### Tecnologias Utilizadas

#### Backend

- **Laravel 12** - Framework PHP moderno
- **PHP 8.2+** - Linguagem de programação
- **Laravel Sanctum** - Autenticação de API
- **PostgreSQL** - Banco de dados relacional
- **Eloquent ORM** - Mapeamento objeto-relacional

#### Frontend

- **Vue.js 3** - Framework JavaScript reativo
- **TypeScript** - Tipagem estática
- **Vue Router** - Roteamento SPA
- **Pinia** - Gerenciamento de estado para Autenticação
- **TailwindCSS** - Framework CSS utilitário
- **Vite** - Build tool e bundler
- **Axios** - Cliente HTTP

## Configuração do Ambiente

### Requisitos

- Git
- Docker e Docker Compose
- PHP 8.2+
- Composer 2.8+
- NodeJS 22+
- npm 8+

1. **Clone o repositório, pois é o passo comum entre as duas opções:**

```bash
git clone https://github.com/danluan/conceituacao
cd conceituacao
```

### Opção 1: Configuração com Docker Compose (Recomendado)

- Docker e Docker Compose instalados

2. Crie um arquivo `.env` baseado no `.env.example`:

```bash
cp .env.example .env
```

3. Edite o arquivo conforme especificado abaixo:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=gup
DB_USERNAME=postgres
DB_PASSWORD=admin
```

4. **Inicie os serviços:**

```bash
docker compose up -d
```

Assim os serviços devem estar prontos para uso.

### Opção 2: Configuração Manual

#### Backend (Laravel)

1. **Navegue para o diretório do backend:**

```bash
cd backend
```

2. **Instale as dependências PHP:**

```bash
composer install
```

3. **Configure o banco de dados PostgreSQL:**
   - Crie um banco chamado `gup`
   - Configure as credenciais no arquivo `.env` (se necessário)

4. **Execute as migrations e seeders:**

```bash
php artisan migrate
php artisan db:seed
```

5. **Inicie o servidor de desenvolvimento:**

```bash
php artisan serve
```

#### Frontend (Vue.js)

1. **Em outro terminal, navegue para o diretório do frontend:**

```bash
cd frontend
```

2. **Instale as dependências Node.js:**

```bash
npm install
```

3. **Inicie o servidor de desenvolvimento:**

```bash
npm run dev
```

## 🔐 Credenciais de Teste

- **Email:** `admin@gup.com`
- **Senha:** `admin1234`
- **Perfil:** Administrador com acesso total ao sistema
