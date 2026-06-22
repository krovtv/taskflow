# KV Tech Organizer

Sistema de organização pessoal (Estudos, Projetos e Trabalho) construído em **Laravel 11**.

Inclui: autenticação, CRUD de tarefas por categoria, agenda semanal, dashboard com
estatísticas e sistema de notificações (lembretes de prazo) via **Laravel Notifications**
(canais `database` + `mail`).

---

## 1. O que vem neste pacote

Este pacote contém **apenas os arquivos específicos da aplicação** (Models, Controllers,
Migrations, Seeders, Views, Rotas, Notification e Command). Ele foi pensado para ser
sobreposto em um esqueleto novo do Laravel 11, evitando arquivos de framework
desatualizados.

```
app/
  Console/Commands/CheckTaskDeadlines.php
  Http/Controllers/ (Auth, Dashboard, Task, Agenda, Notification)
  Models/ (User, Task)
  Notifications/TaskDueSoonNotification.php
database/
  migrations/ (tasks, notifications)
  seeders/DatabaseSeeder.php
  factories/UserFactory.php
resources/views/ (layouts, auth, dashboard, tasks, agenda, notifications)
routes/
  web.php
  console.php
```

---

## 2. Pré-requisitos

* PHP >= 8.2 com extensões padrão (pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json)
* Composer 2.x
* MySQL ou MariaDB
* (Opcional) Node.js — **não é obrigatório**, pois o front-end usa Tailwind via CDN,
  já funcionando sem build step.

---

## 3. Passo a passo de instalação (do zero)

### 3.1. Criar o esqueleto do Laravel

```bash
composer create-project laravel/laravel kvtech-organizer "^11.0"
cd kvtech-organizer
```

### 3.2. Sobrepor os arquivos deste pacote

Extraia este pacote e copie as pastas por cima do projeto recém-criado
(substituindo/mesclando os arquivos):

```bash
# a partir da pasta onde você extraiu este pacote (kvtech/)
cp -r kvtech/app/.        kvtech-organizer/app/
cp -r kvtech/database/.   kvtech-organizer/database/
cp -r kvtech/resources/.  kvtech-organizer/resources/
cp -r kvtech/routes/.     kvtech-organizer/routes/
```

> No Windows (PowerShell), use `Copy-Item -Recurse -Force` para cada pasta.

### 3.3. Configurar o ambiente

```bash
cp .env.example .env      # se ainda não existir
php artisan key:generate
```

Edite o `.env` e configure o banco de dados:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kvtech_organizer
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco de dados (exemplo via CLI do MySQL):

```sql
CREATE DATABASE kvtech_organizer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.4. Rodar migrations + seeder

```bash
php artisan migrate --seed
```

Isso cria as tabelas (`users`, `tasks`, `notifications`, `sessions`, `cache`, `jobs`, etc.)
e popula um **usuário de teste** com tarefas de exemplo:

```
E-mail: teste@kvtech.com
Senha:  senha123
```

### 3.5. Subir o servidor

```bash
php artisan serve
```

Acesse: **http://localhost:8000**

---

## 4. Funcionalidades

### Autenticação
- `/registrar` — criação de conta
- `/login` — login
- Logout via botão na barra lateral
- Rotas protegidas pelo middleware `auth`

### Tarefas (`/tasks`)
- Criar, editar, listar, excluir
- Filtro por categoria (Estudos / Projetos / Trabalho) e por status
- Atualização rápida de status direto na listagem
- Identificação visual de tarefas atrasadas

### Agenda (`/agenda`)
- Visualização semanal das tarefas, navegável (semana anterior/próxima)
- Cada dia mostra as tarefas com prazo naquela data, coloridas por categoria

### Dashboard (`/dashboard`)
- Cards com totais (pendentes, em andamento, concluídas, atrasadas)
- Resumo por categoria
- Lista das próximas tarefas e das tarefas atrasadas

### Notificações (lembretes de prazo)
- Sino no topo com contador de não lidas e dropdown rápido
- Página completa em `/notificacoes`
- Comando Artisan que verifica tarefas próximas do vencimento:

```bash
php artisan tasks:check-deadlines --hours=24
```

- Já agendado em `routes/console.php` para rodar **a cada hora**
  (`Schedule::command('tasks:check-deadlines --hours=24')->hourly()`).

  Para o agendamento funcionar em produção, configure o cron do servidor:
  ```
  * * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
  ```
  Em desenvolvimento, você pode simular com:
  ```bash
  php artisan schedule:work
  ```

- As notificações usam os canais `database` (sino interno) e `mail`. Por padrão,
  o `.env` usa `MAIL_MAILER=log`, então os e-mails aparecem em `storage/logs/laravel.log`.
  Para usar Mailtrap, Gmail SMTP etc., ajuste as variáveis `MAIL_*` no `.env`.

---

## 5. Estrutura de dados

**tasks**
| Campo | Tipo | Descrição |
|---|---|---|
| user_id | FK | Proprietário da tarefa |
| title | string | Título |
| description | text (nullable) | Descrição detalhada |
| category | enum | estudos / projetos / trabalho |
| due_date | datetime | Data e hora do prazo |
| status | enum | pendente / em_andamento / concluido |
| notified_at | timestamp (nullable) | Controle de envio do lembrete |

---

## 6. Identidade visual

A interface usa a paleta da logo KV Tech: azul-marinho (`#070821`) como cor de base
(sidebar, cabeçalho da tela de login) e ciano (`#1ec2cf`) como cor de destaque
(botões, badges, indicadores ativos). O ícone do "monitor com balança" da logo é
referenciado de forma simplificada no topo da sidebar e na tela de login.

Caso você tenha o arquivo da logo em PNG/SVG, basta:
1. Colocar o arquivo em `public/images/logo.png`
2. Substituir o bloco `<svg>...</svg>` em
   `resources/views/layouts/app.blade.php` e `layouts/guest.blade.php` por:
   ```html
   <img src="{{ asset('images/logo.png') }}" class="w-9 h-9" alt="KV Tech">
   ```

---

## 7. Próximos passos sugeridos (evolução)

- Adicionar verificação de e-mail (`MustVerifyEmail`)
- Adicionar Vue/Inertia para uma agenda com drag-and-drop
- Exportar tarefas em PDF/Excel
- Notificações push via WebSockets (Laravel Reverb)
- Compartilhamento de tarefas entre usuários (times/equipes)
