# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application using PHP 8.2+ with Tailwind CSS v4 for frontend styling. The project uses SQLite as the default database and includes Vite for asset bundling.

## Development Commands

### Initial Setup
```bash
composer setup
```
This installs dependencies, creates .env, generates app key, runs migrations, and builds frontend assets.

### Running Development Server
```bash
composer dev
```
Starts a full development environment with:
- PHP development server (http://localhost:8000)
- Queue worker
- Laravel Pail (log viewer)
- Vite dev server (hot module reloading)

All services run concurrently and will stop together.

### Frontend Development
```bash
npm run dev    # Start Vite dev server with hot reloading
npm run build  # Build production assets
```

### Testing
```bash
composer test           # Run full test suite
php artisan test        # Alternative way to run tests
php artisan test --filter=TestName  # Run specific test
```

Tests use in-memory SQLite database. PHPUnit is configured with two test suites:
- `tests/Unit/` - Unit tests
- `tests/Feature/` - Feature/integration tests

### Code Quality
```bash
./vendor/bin/pint       # Format code (Laravel Pint)
./vendor/bin/pint --test # Check formatting without changes
```

### Database Operations
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Drop all tables and re-run migrations
php artisan migrate:fresh --seed # Fresh migration with seeders
php artisan db:seed              # Run database seeders
```

### Artisan Commands
```bash
php artisan tinker               # Interactive REPL
php artisan serve                # Start development server (standalone)
php artisan queue:work           # Process queue jobs
php artisan make:controller ControllerName
php artisan make:model ModelName -m  # Model with migration
php artisan route:list           # List all routes
php artisan config:clear         # Clear cached config
```

## Architecture

### MVC Structure
- **Models**: `app/Models/` - Eloquent ORM models (currently only User model exists)
- **Controllers**: `app/Http/Controllers/` - HTTP request handlers (only base Controller exists currently)
- **Views**: `resources/views/` - Blade templates
- **Routes**:
  - `routes/web.php` - Web routes (currently single welcome route)
  - `routes/console.php` - Artisan console commands

### Database
- **Migrations**: `database/migrations/` - Schema definitions (users, cache, jobs tables)
- **Factories**: `database/factories/` - Model factories for testing
- **Seeders**: `database/seeders/` - Database seeders

Default configuration uses SQLite (`database/database.sqlite`). Database connection, cache, sessions, and queue all use database by default.

### Frontend Assets
- **Entry Points**:
  - `resources/js/app.js` - Main JavaScript entry
  - `resources/css/app.css` - Main CSS entry (Tailwind)
- **Build Tool**: Vite with Laravel plugin and Tailwind CSS v4
- **Output**: `public/build/` (generated)

Axios is included for HTTP requests. Tailwind configuration is handled through `@tailwindcss/vite` plugin.

### Service Providers
- `app/Providers/AppServiceProvider.php` - Application service provider for service container bindings

### Configuration
All configuration files in `config/` directory. Key configs:
- `config/app.php` - Application settings
- `config/database.php` - Database connections
- `config/cache.php` - Cache stores
- `config/queue.php` - Queue connections

Environment variables in `.env` file (copy from `.env.example` if missing).

## Queue System

The application is configured to use database-backed queues (`QUEUE_CONNECTION=database`). The `composer dev` command automatically starts a queue listener. For manual queue processing:

```bash
php artisan queue:work
php artisan queue:listen --tries=1
```

## Testing Conventions

- Tests run in isolated environment with in-memory SQLite
- PHPUnit configuration in `phpunit.xml`
- Always run `php artisan config:clear` before tests to avoid config cache issues
- Use factories defined in `database/factories/` for test data generation

## Important Notes

- This is a fresh Laravel 12 installation with minimal customization
- No authentication scaffolding is installed yet
- No API routes configured (only web routes)
- The project uses modern Laravel features including the new application structure
