# Assessment Test – Laravel API (Sanctum + Swagger)

Laravel API that uses **Laravel Sanctum** for authentication and **L5-Swagger** for API documentation.

---

## Database
* MySQL

---

## Getting Started

### 1) Create project

```bash
composer install
```

### 2) Environment

Copy the example env and set your keys:

```bash
cp .env.example .env
cp .env .env.testing (digunakan untuk unit testing)
php artisan key:generate
```

### 3) Migrate database

```bash
php artisan migrate
```

### 4) Running the App
```bash
php artisan optimize:clear (hapus cache)
php artisan serve
```

### 5) Path Api
```
http://localhost/api/documentation
```

### 6) Testing

Create a test and run the suite:

```bash
php artisan test
```