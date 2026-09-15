# TicketOPS — GitHub Copilot Instructions

## Project purpose

TicketOPS is a portfolio project designed to demonstrate professional Cloud/DevOps skills through a realistic Helpdesk application.

The project is intentionally more focused on infrastructure, automation, deployment, observability and operational practices than on complex application features.

Target balance:

* 70% DevOps / Cloud / Infrastructure
* 20% Helpdesk application
* 10% AI

The application should remain realistic and maintainable, but application complexity should not grow unnecessarily.

---

## Developer profile and learning approach

The developer is learning professional DevOps practices and is still learning Laravel.

When proposing or implementing unfamiliar Laravel, PHP, Docker, Kubernetes or infrastructure concepts:

* Explain what the important parts do.
* Prefer simple, incremental solutions.
* Avoid unnecessary abstractions.
* Explain why a professional solution is preferable when there are multiple valid approaches.
* Do not blindly agree with a proposed implementation. If another approach is technically better, explain why.
* Do not introduce additional technologies or dependencies unless they provide a clear benefit to the project.
* Prefer understanding and maintainability over speed of implementation.

The goal is not to finish the project as quickly as possible. The goal is to build something that demonstrates how a professional DevOps engineer would design, deploy and operate an application.

---

# Current technology stack

## Application

* Laravel 13
* PHP 8.4
* PostgreSQL
* Eloquent ORM
* Blade
* Bootstrap

## Containerization

* Docker
* Docker Compose
* Nginx
* PHP-FPM
* PostgreSQL

## Planned DevOps infrastructure

The following technologies are part of the long-term architecture, but should only be introduced when their corresponding stage is reached:

* Redis
* Kubernetes
* Azure
* Terraform
* GitHub Actions
* Jenkins
* Prometheus
* Grafana
* Loki
* Ansible

## AI

TicketOPS may integrate the OpenAI API for Helpdesk-related functionality such as ticket summaries.

AI is a supporting feature and must not become the core architecture of the application.

---

# Development environment

TicketOPS is developed using:

* Windows host
* WSL2
* Ubuntu 24.04
* Docker Desktop with WSL2 integration
* Visual Studio Code with Remote WSL

The project must live inside the WSL2 Linux filesystem.

Current project location:

`~/projects/TicketOPS`

Do not recommend moving the project back to the Windows filesystem.

## Why WSL2 Linux filesystem?

Docker containers run Linux-based services.

The project was originally stored on the Windows filesystem and mounted into Docker through a bind mount. This caused severe filesystem performance problems with Laravel and Composer.

Measured results:

| Operation               | Windows filesystem | WSL2 filesystem |
| ----------------------- | -----------------: | --------------: |
| `php artisan --version` |             ~7.3 s |         ~0.09 s |
| Laravel `/test`         |             ~4.2 s |         ~0.15 s |

The Linux filesystem is therefore the preferred development environment.

---

# Docker architecture

Current Docker Compose services:

* `nginx`
* `php`
* `postgres`

Architecture:

```text
Browser
   |
   v
Nginx :8080
   |
   v
PHP-FPM :9000
   |
   v
Laravel
   |
   v
PostgreSQL :5432
```

## Nginx

Nginx is the public entry point of the application.

The host exposes:

`localhost:8080`

Nginx serves Laravel from:

`/var/www/html/public`

PHP requests are forwarded to:

`php:9000`

Do not bypass Nginx for normal HTTP application access.

## PHP-FPM

PHP runs through PHP-FPM.

The PHP image is based on:

`php:8.4-fpm`

Installed PHP extensions currently include:

* pdo_pgsql
* zip
* intl
* bcmath

Composer is included in the PHP image.

## PostgreSQL

PostgreSQL runs in its own container.

Database:

`ticketops`

User:

`ticketops`

The PostgreSQL data is stored in the named Docker volume:

`postgres-data`

### Important

Never recommend:

```bash
docker compose down -v
```

unless the developer explicitly requests deletion of the database volume.

The database contains project data that must be preserved.

---

# Docker filesystem permissions

The development environment uses a bind mount between the WSL2 project and the containers.

The host WSL user uses:

* UID 1000
* GID 1000

PHP-FPM `www-data` is configured to use:

* UID 1000
* GID 1000

This prevents Laravel from having filesystem permission problems when writing to:

* `storage`
* `bootstrap/cache`

Do not solve permission problems with:

```bash
chmod -R 777
```

Prefer fixing ownership and UID/GID alignment.

The PHP container uses an entrypoint to prepare Laravel writable directories before starting PHP-FPM.

---

# Laravel architecture

The Laravel application is located under:

`src/`

Important directories include:

```text
src/
├── app/
├── database/
├── public/
├── resources/
├── routes/
└── ...
```

The application uses:

* Controllers
* Eloquent Models
* Migrations
* Seeders
* Blade views
* Route middleware

Prefer Laravel conventions instead of introducing custom frameworks or patterns.

---

# Authentication

TicketOPS currently uses manual Laravel authentication.

Authentication includes:

* Login
* Logout
* Session regeneration
* Authentication middleware
* Authenticated user information in the application header

Login route:

`GET /login`

Login submission:

`POST /login`

Logout:

`POST /logout`

Authenticated ticket routes use the `auth` middleware.

Do not introduce Breeze, Jetstream or another authentication package unless explicitly requested.

The current project intentionally uses manual authentication so the developer can understand how Laravel authentication works.

---

# Users and roles

Users have a relationship with a `Role`.

The `users` table contains:

* UUID `id`
* `name`
* `email`
* `password`
* `role_id`

The `User` model uses Laravel's hashed password cast.

Roles are intended to support different Helpdesk permissions such as:

* User
* Technician
* Admin

Role-based authorization should be introduced incrementally.

Do not assume that every authenticated user is an administrator.

---

# Tickets

Tickets are the central Helpdesk entity.

Tickets use UUIDs internally.

The visible ticket identifier follows this format:

```text
TKT-YYYY-NNNNNN
```

Example:

```text
TKT-2026-000001
```

The UUID remains the internal primary key.

The visible ticket number is a separate unique field.

## Ticket relationships

Tickets currently have relationships with:

* creator
* technician
* status
* priority
* category
* subcategory
* resolution type
* comments

Important fields include:

* `ticket_number`
* `title`
* `description`
* `created_by`
* `assigned_to`
* `status_id`
* `priority_id`
* `category_id`
* `subcategory_id`
* `resolution`
* `resolution_type_id`
* `resolved_at`
* `closed_at`

Tickets use soft deletes.

---

# Ticket creation

Ticket creation requires an authenticated user.

The creator must be obtained from the authenticated session:

```php
'created_by' => auth()->id(),
```

Do not accept `created_by` from the client request.

Initial ticket status is:

`Open`

Ticket creation should validate:

* title
* description
* priority
* category
* optional subcategory

After successful creation, the user is redirected to the ticket list and receives a success message.

---

# Ticket statuses

Current statuses include:

* Open
* In Progress
* Pending
* Resolved
* Closed
* Reopened

Status IDs must not be hardcoded in controllers when the status can be resolved by name or another stable application-level mechanism.

---

# Categories and subcategories

Categories and subcategories are database-backed.

Subcategories are loaded dynamically according to the selected category.

The endpoint is:

`GET /categories/{category}/subcategories`

Do not hardcode category or subcategory values into the frontend when the database is the source of truth.

---

# Database practices

PostgreSQL is the database of record.

Prefer:

* migrations for schema changes
* seeders for reproducible development data
* Eloquent relationships for application access
* validation before database writes
* foreign keys and database constraints

Avoid manually modifying the database as the permanent solution when the change should be represented in migrations or seeders.

DBeaver may be used for inspection, debugging and manual development operations.

---

# Routing

Keep routes explicit and readable.

Authenticated ticket routes should use the `auth` middleware.

Current ticket routes conceptually include:

```text
GET  /tickets
GET  /tickets/create
POST /tickets
```

Route names should be preserved when they already exist unless there is a clear reason to change them.

---

# Code quality rules

Prefer:

* clear names
* small methods
* Laravel conventions
* database constraints
* validation
* explicit relationships
* reusable layouts
* maintainable Blade templates

Avoid:

* unnecessary abstractions
* duplicated logic
* hardcoded database IDs
* client-controlled ownership fields
* unnecessary dependencies
* excessive controller complexity
* putting business logic directly into Blade views

When code is changed, preserve existing functionality unless the change explicitly requires otherwise.

---

# Security rules

Never expose:

* passwords
* password hashes
* API keys
* tokens
* `.env` contents
* database credentials

Do not commit secrets to Git.

Do not recommend committing:

```text
.env
```

Use environment variables for configuration and secrets.

Never use `chmod 777` as a generic solution.

Never accept ownership fields such as `created_by` directly from HTTP requests.

Use Laravel's authentication and authorization mechanisms.

---

# Git workflow

Use conventional commit-style messages where appropriate.

Examples:

```text
feat: add authentication and ticket creation
fix: correct ticket creation validation
refactor: simplify ticket filtering
docs: document WSL2 development environment
chore: update Docker configuration
```

Before committing:

```bash
git status
```

Review the files that will be committed.

Never commit secrets or temporary debugging code.

Temporary debugging such as:

```php
dd(...)
```

should be removed before committing.

---

# Documentation

Important architectural decisions should be documented in the repository.

Prefer documentation in:

```text
docs/
```

Architecture decisions should be recorded when they have long-term consequences.

The documentation should explain not only what was chosen, but why it was chosen when the decision is significant.

---

# DevOps priorities

The main purpose of TicketOPS is to demonstrate professional DevOps practices.

Future work should progressively cover:

1. Reliable local Docker environment
2. Application configuration
3. PostgreSQL persistence
4. Redis
5. Health checks
6. Container optimization
7. CI with GitHub Actions
8. CD with Jenkins
9. Infrastructure as Code with Terraform
10. Azure deployment
11. Kubernetes
12. Monitoring with Prometheus
13. Dashboards with Grafana
14. Logs with Loki
15. Automation with Ansible

Do not implement all of these technologies at once.

Each technology should solve a concrete problem and be introduced when the project reaches the appropriate stage.

---

# Observability philosophy

Observability should eventually cover:

* application health
* container health
* infrastructure metrics
* logs
* service availability
* relevant Helpdesk metrics

Do not add monitoring tools only for the sake of listing them on a CV.

Each monitoring component should have a clear operational purpose.

---

# AI philosophy

AI functionality should solve realistic Helpdesk problems.

Potential examples:

* ticket summaries
* incident classification
* suggested troubleshooting steps
* ticket categorization

AI should not replace the core ticket lifecycle.

The application must remain functional without AI.

API credentials must always be supplied through environment variables.

---

# Working style for Copilot

When modifying TicketOPS:

1. Inspect the existing implementation first.
2. Reuse existing architecture and conventions.
3. Avoid rewriting working code unnecessarily.
4. Explain important architectural changes.
5. Prefer the smallest change that solves the problem.
6. Do not introduce a dependency when Laravel, PHP, PostgreSQL or Docker already provides the required capability.
7. Preserve database data.
8. Do not make destructive Docker or database changes without explicit confirmation.
9. If there are multiple valid approaches, explain the trade-offs and recommend one.
10. Treat the repository documentation as part of the project's architecture.

When uncertain about an existing architectural decision, inspect the relevant code and documentation before proposing a change.
