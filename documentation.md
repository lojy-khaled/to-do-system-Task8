# Todo System — Project Documentation

## Overview

This project is a **REST API** built with **Laravel 12** (PHP 8.2+) for managing a to-do list organized into categories. There is no frontend — the entire project is an API that returns JSON responses.

- **Framework:** Laravel ^12.0
- **Database:** SQLite (`database/database.sqlite`)
- **Authentication:** Laravel Sanctum is installed but not currently enforced on any routes
- **Auto-generated API docs:** `dedoc/scramble` package (generates OpenAPI documentation automatically with no extra annotations needed)

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── CategoryController.php   # CRUD operations for categories
│   │   └── TaskController.php       # CRUD operations for tasks
│   └── Resources/
│       ├── CategoryResource.php     # Category response formatting
│       └── TaskResource.php         # Task response formatting
└── Models/
    ├── Category.php
    ├── Task.php
    └── User.php

database/
├── migrations/
│   ├── ..._create_categories_table.php
│   └── ..._create_tasks_table.php
└── database.sqlite

routes/
└── api.php    # All API routes
```

---

## Database Schema

### `categories` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint (PK) | Auto-increment |
| `name` | string | Category name |
| `created_at` / `updated_at` | timestamp | Auto-managed |

### `tasks` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint (PK) | Auto-increment |
| `title` | string | Task title |
| `description` | text (nullable) | Optional task description |
| `is_done` | boolean | Defaults to `false` |
| `category_id` | foreign key → `categories.id` | `onDelete('cascade')` — deleting a category deletes its tasks too |
| `created_at` / `updated_at` | timestamp | Auto-managed |

### Relationships

- `Category` → `hasMany` → `Task`
- `Task` → `belongsTo` → `Category`

---

## API Endpoints

All routes are defined as [Resource Routes](https://laravel.com/docs/controllers#resource-controllers) in `routes/api.php`, which automatically generates 7 standard routes per resource (Task and Category).

> **Note:** Laravel's default prefix for API routes is `/api`, so the full path is e.g. `http://localhost/api/tasks`.

### Tasks

| Method | Route | Description |
|---|---|---|
| `GET` | `/api/tasks` | List all tasks (with their category data) |
| `POST` | `/api/tasks` | Create a new task |
| `GET` | `/api/tasks/{id}` | Show a single task in detail |
| `PUT/PATCH` | `/api/tasks/{id}` | Update an existing task |
| `DELETE` | `/api/tasks/{id}` | Delete a task |

**Example request to create a task (`POST /api/tasks`):**
```json
{
  "title": "Buy milk",
  "description": "Whole milk",
  "is_done": false,
  "category_id": 1
}
```

**Response shape — `TaskResource`:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Buy milk",
    "description": "Whole milk",
    "is_done": false,
    "category_id": 1,
    "category": { "id": 1, "name": "Home", "created_at": "...", "updated_at": "..." },
    "created_at": "2026-06-30T10:00:00.000000Z",
    "updated_at": "2026-06-30T10:00:00.000000Z"
  }
}
```

### Categories

| Method | Route | Description |
|---|---|---|
| `GET` | `/api/categories` | List all categories |
| `POST` | `/api/categories` | Create a new category |
| `GET` | `/api/categories/{id}` | Show a single category |
| `PUT/PATCH` | `/api/categories/{id}` | Update a category |
| `DELETE` | `/api/categories/{id}` | Delete a category (cascades and deletes its tasks too) |

**Example request (`POST /api/categories`):**
```json
{ "name": "Work" }
```

---

## Notable Observations (Potential Improvements)

- **No Form Requests / Validation:** Controllers currently use `$request->all()` directly with no input validation. In a real production environment, validation rules should be added (e.g. `required`, `string`, `exists:categories,id`) before persisting data.
- **No Authentication Enforced:** Sanctum is present in `composer.json`, but routes aren't protected by any middleware such as `auth:sanctum`, meaning anyone can access the API without logging in.
- **Web routes** (`routes/web.php`) only contain a `welcome` page and have nothing to do with the app's core logic.
- **Interactive API docs:** Since `dedoc/scramble` is installed, you'll likely find interactive documentation (Swagger-like) available at `/docs/api` once the server is running.

---

## Running Locally

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve
```

The API will then be available at `http://localhost:8000/api`.
