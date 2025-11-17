# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application for **LSP-PIE** (Lembaga Sertifikasi Profesi - Pustaka Ilmiah Elektronik), a professional certification body for electronic scholarly library management in Indonesia. The application uses PHP 8.2+, Tailwind CSS v4 for styling, and Vite for asset bundling. SQLite is the default database.

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

### Blade Templating System
This project uses Laravel Blade's component and layout system to eliminate code duplication:

**Layout Structure**:
- `resources/views/layouts/app.blade.php` - Main layout template containing HTML structure, head, navbar, footer
- `resources/views/components/navbar.blade.php` - Reusable navbar with dynamic active state
- `resources/views/components/footer.blade.php` - Reusable footer component

**Page Template Pattern**:
All page views follow this consistent pattern:
```php
@extends('layouts.app')
@section('title', 'Page Title')
@php
    $active = 'menu-name';  // For navbar highlighting
@endphp
@section('content')
    <!-- Page-specific content only -->
@endsection
```

The `$active` variable determines which menu item is highlighted in the navbar. Valid values: `beranda`, `profile`, `struktur-organisasi`, `skema`, `news`, `pengumuman`, `contact`.

### Routes and Pages
Current application routes (all closure-based, no controllers):
- `/` - Home page (landing page with hero section)
- `/profile` - Organization profile
- `/struktur-organisasi` - Organizational structure (includes custom CSS for org chart)
- `/skema` - Certification schemes listing
- `/skema/penerapan-it-artikel-ilmiah` - IT Application for Scholarly Articles scheme details
- `/skema/pengelolaan-jurnal-elektronik` - Electronic Journal Management scheme details
- `/news` - News articles and educational content (grid layout with gradient icons)
- `/pengumuman` - Official announcements (card-based layout for exam schedules, regulations, etc.)
- `/contact` - Contact information

### Design System
**Color Palette**:
- Primary: `blue-900` (navbar, headings, primary CTAs)
- Accent: `red-700` (secondary buttons, highlights)
- Gray scale: Used for text, borders, backgrounds

**Typography**:
- Font: Public Sans from Google Fonts
- Icon System: Material Symbols Outlined

**UI Patterns**:
- Responsive breakpoints: `md:` (768px), `lg:` (1024px), `xl:` (1280px)
- Sticky navbar with backdrop blur
- Mobile menu toggle (handled by inline JavaScript in navbar component)
- Consistent button styles: `bg-red-700` for primary actions, `bg-gray-200` for secondary
- Card-based layouts with hover effects (`hover:shadow-xl`)

### Frontend Assets
- **Entry Points**:
  - `resources/js/app.js` - Main JavaScript entry
  - `resources/css/app.css` - Main CSS entry (Tailwind)
- **Build Tool**: Vite with Laravel plugin and Tailwind CSS v4
- **Output**: `public/build/` (generated)
- **Dependencies**: Axios for HTTP requests, Tailwind handled via `@tailwindcss/vite` plugin

### MVC Structure
- **Models**: `app/Models/` - Only User model exists (authentication not yet implemented)
- **Controllers**: `app/Http/Controllers/` - Only base Controller (no custom controllers yet)
- **Views**: `resources/views/` - Blade templates organized by page/feature
- **Routes**: `routes/web.php` - All web routes using closure-based handlers

### Database
Default configuration uses SQLite (`database/database.sqlite`). Database connection, cache, sessions, and queue all use database by default.

## Development Conventions

### Adding New Pages
When creating new pages:
1. Create Blade view in `resources/views/` following the template pattern
2. Set appropriate `$active` value for navbar highlighting
3. Add route in `routes/web.php` (currently using closure-based routes)
4. If adding a new menu item, update both:
   - `resources/views/components/navbar.blade.php` (desktop and mobile menu)
   - `resources/views/components/footer.blade.php` (footer menu)

### Modifying Navigation
- **Navbar changes**: Edit `resources/views/components/navbar.blade.php` (changes apply to all pages)
- **Footer changes**: Edit `resources/views/components/footer.blade.php` (changes apply to all pages)
- **Active states**: Use conditional classes with `$active` variable (see navbar component for examples)

### Content Separation
- **News (`/news`)**: Articles, tips & tricks, industry trends - uses grid layout with gradient icon backgrounds
- **Announcements (`/pengumuman`)**: Official announcements, exam schedules, regulations - uses card-based list layout

### Configuration
Environment variables in `.env` file (copy from `.env.example` if missing). Key configuration files in `config/` directory.

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

## Project Status

**Current Implementation**:
- Static informational website with 9 pages
- Blade templating system fully implemented (layouts, components)
- Responsive design with Tailwind CSS v4
- No authentication or user management yet
- No database-driven content (all content is static in views)
- No API routes configured

**Technology Stack**:
- Laravel 12 with PHP 8.2+
- Tailwind CSS v4 via Vite
- SQLite database (configured but not actively used for content)
- Material Symbols Outlined for icons
- Public Sans font
