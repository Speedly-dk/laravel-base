# Laravel Base Project

A modern, production-ready Laravel starter template with the TALL stack and premium UI components.

## Features

### Core Stack
- **Laravel 12** - Latest version of the Laravel framework
- **PHP 8.4** - Running on the latest PHP version
- **Livewire 3.6** - Full-stack framework for Laravel
- **Alpine.js 3.14** - Lightweight JavaScript framework
- **Tailwind CSS v4** - Utility-first CSS framework

### Premium Components
- **Flux Pro v2.2** - Premium UI component library with advanced components
- **Stripe-inspired Design** - Clean, professional interface with dark mode support

### Development Tools
- **Laravel Boost** - AI-assisted development configuration
- **Pest PHP v4** - Modern testing framework with expressive syntax
- **Laravel Herd** - Optimized local development environment
- **Vite** - Fast build tool for modern web development

### Testing
- **100% Code Coverage Goal** - Comprehensive test suite requirement
- **Pest v4 Testing** - Modern, readable test syntax
- **Coverage Command**: `herd coverage ./vendor/bin/pest --coverage`

## Quick Start

### Prerequisites
- PHP 8.4+ (via Laravel Herd recommended)
- Composer
- Node.js & NPM
- Laravel Herd (for optimal local development)

### Installation

1. Clone the repository:
```bash
git clone https://github.com/Speedly-dk/laravel-base.git my-project
cd my-project
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Copy environment file and generate key:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env` and run migrations:
```bash
php artisan migrate
```

6. Start the development server:
```bash
composer run dev
```

This will start:
- Laravel development server
- Queue worker
- Laravel Pail for log monitoring
- Vite for asset compilation

## Architecture - Code Boundaries

This project follows a strict code boundaries architecture pattern for clean separation of concerns:

### Simple Version
- **Input** → FormRequest (validation & authorization)
- **Delegation** → Controller (routing & orchestration)  
- **Orchestration** → Action (coordinate business operations)
- **Business Logic** → Action (core domain logic)
- **Utilities** → Service (reusable functionality)

### Detailed Flow with Transitions
```
> Inbound Processing (Routing, Middlewares, Policies)
- Delegation (Controller)
> Contract Validation (FormRequest, Policies, DTOs)
- Orchestration (Action)
> Domain Enforcement (Business Logic Conditions, DTOs)
- Business Logic (Action)
> System Integration (DTOs, Mapping Data)
- Utilities (Service)
```

Where:
- `>` represents a Transition (crossing a boundary)
- `-` represents a Boundary (within the same layer)

### Key Principles
- **Thin Controllers**: Only delegate to Actions, no business logic
- **Actions**: Contain all business logic and orchestration
- **Services**: Reusable utilities (email, payments, external APIs)
- **FormRequests**: Handle validation and authorization
- **DTOs**: Type safety when crossing boundaries
- **Models**: Focus on relationships and data access only

## Development

### Available Commands

- `composer run dev` - Start all development services concurrently
- `php artisan test` - Run tests
- `herd coverage ./vendor/bin/pest --coverage` - Run tests with code coverage
- `./vendor/bin/pint` - Format code with Laravel Pint

### Project Structure

- Modern Laravel 12 structure
- Livewire components in `app/Livewire`
- Tailwind CSS v4 configuration
- Flux Pro components available globally
- Dark mode support with localStorage persistence

## Testing

This project uses Pest PHP v4 for testing with a goal of 100% code coverage for all new code.

```bash
# Run tests
php artisan test

# Run with coverage (requires Laravel Herd)
herd coverage ./vendor/bin/pest --coverage
```

## AI Development Guidelines

This project includes `CLAUDE.md` with comprehensive guidelines for AI-assisted development, ensuring consistent code quality and adherence to Laravel best practices.

## License

This is a starter template - apply your own license to derived projects.

## Credits

Built with:
- [Laravel](https://laravel.com)
- [Livewire](https://livewire.laravel.com)
- [Flux UI Pro](https://fluxui.dev)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Pest PHP](https://pestphp.com)