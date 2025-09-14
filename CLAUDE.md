# Laravel Development Guidelines

These guidelines are optimized for LLM-assisted Laravel development. Follow them closely to ensure consistent, high-quality code that adheres to Laravel best practices.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- laravel/framework (LARAVEL) - v12
- livewire/flux (FLUXUI_FREE) - v2
- livewire/flux-pro (FLUXUI_PRO) - v2
- livewire/livewire (LIVEWIRE) - v3
- laravel/pint (PINT) - v1
- alpinejs (ALPINEJS) - v3
- tailwindcss (TAILWINDCSS) - v4
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12

## Core Development Principles

### General Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.
- **CRITICAL**: Never assume a library exists - always check package.json, composer.json, or neighboring files first.
- Follow security best practices - never expose or log secrets/keys.

### Code Style Requirements
- **NO COMMENTS** unless explicitly requested by the user
- Maintain existing code patterns and conventions
- Follow PSR-12 coding standards
- Use consistent naming: camelCase for variables/methods, PascalCase for classes
- Keep methods focused and single-purpose

## Development Workflow

### Verification & Testing
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.
- Always run tests after implementing features
- Use `php artisan test` or `./vendor/bin/pest` for test execution

### Git Commit Guidelines
**IMPORTANT**: Follow these commit practices:
- Commit after creating new files
- Commit after significant feature implementations
- Use descriptive commit messages:
  - `feat:` for new features
  - `fix:` for bug fixes
  - `refactor:` for code improvements
  - `test:` for test additions
  - `docs:` for documentation (only when requested)
- Run lints and tests before committing

## Application Structure & Architecture

### Directory Organization
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.
- Standard Laravel structure:
  - `app/Http/Controllers` - Keep thin, delegate to actions
  - `app/Actions` - Business logic and orchestration
  - `app/Services` - Reusable utilities
  - `app/Models` - Eloquent models with relationships
  - `resources/views` - Blade templates
  - `database/migrations` - Schema definitions
  - `tests` - Pest/PHPUnit tests

### File Naming Conventions
- Controllers: `{Resource}Controller.php`
- Actions: `{Verb}{ActionPerformed}.php`
- Form Requests: `{Resource}Request.php`
- Resources: `{Resource}Resource.php`
- Tests: `{Feature}Test.php` or `{Unit}Test.php`

## Frontend Development

### Asset Compilation
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.
- Use Vite for asset bundling (Laravel's default)
- Hot Module Replacement: `npm run dev`
- Production builds: `npm run build`

### Frontend Stack
- **Blade** for templating
- **Tailwind CSS v4** for styling
- **Alpine.js v3** for JavaScript interactions
- **Livewire v3** for reactive components
- Prefer server-side rendering with Blade/Livewire over client-side JavaScript

## Communication Guidelines

### Response Style
- Be concise in your explanations - focus on what's important rather than explaining obvious details.
- Answer directly without preamble or postamble
- Provide code examples when helpful
- Avoid over-explaining obvious Laravel concepts
- Focus on the specific task at hand

## Documentation Policy
- You must only create documentation files if explicitly requested by the user.
- Never proactively create README.md or other docs
- Keep inline documentation minimal (PHPDoc for complex methods only)
- Let the code be self-documenting through clear naming

## Laravel Boost MCP Integration

### Overview
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.
- Provides direct integration with Laravel internals
- Enables real-time debugging and database inspection

### Available Tools
- `search-docs` - Version-specific Laravel documentation
- `tinker` - Execute PHP code in application context
- `database-query` - Direct database queries
- `database-schema` - View table structures
- `browser-logs` - Read browser console output
- `get-absolute-url` - Generate correct application URLs
- `list-artisan-commands` - View available Artisan commands
- `application-info` - Get app configuration details

## Artisan Commands

### Usage Guidelines
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.
- Always use `php artisan make:` for file generation
- Pass `--no-interaction` flag for automation
- Common commands:
  - `make:controller --resource`
  - `make:model -mfc` (with migration, factory, controller)
  - `make:request`
  - `make:action` (if package installed)
  - `make:livewire`
  - `make:test --pest`
  - `make:migration`

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


## PHP
- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums

### Usage Guidelines
- This project extensively uses PHP 8+ enums for type safety and better code organization
- Always use enums for fields that have a fixed set of values (status, type, severity, etc.)
- Enum cases should be TitleCase (e.g., `Production`, `Staging`, `Critical`)
- Enum values should be lowercase strings for database storage
- All enums should be backed enums using `: string` for database compatibility

### Required Enum Methods
Every enum should include these standard methods:
- `label()` - Returns translated display label using `__()` helper
- `color()` - Returns UI color for visual representation (when applicable)
- `icon()` - Returns icon identifier for UI (when applicable)

### Model Casting
Always cast enum fields in models using the `casts()`

## Application Architecture - Code Boundaries

This application follows a strict code boundaries architecture pattern for clean separation of concerns:

### Simple Version
- **Input** → FormRequest (validation & authorization)
- **Delegation** → Controller (routing & orchestration)
- **Orchestration** → Action (coordinate business operations)
- **Business Logic** → Action (core domain logic)
- **Utilities** → Service (reusable functionality)

### Implementation Guidelines
1. **Controllers** should be thin - only delegate to Actions
2. **Actions** contain business logic and orchestrate operations
3. **Services** provide reusable utilities (email, payment processing, etc.)
4. **FormRequests** handle all input validation and authorization
5. **DTOs** ensure type safety when crossing boundaries using Spatie Laravel Data
6. Never put business logic in Controllers or Models
7. Keep Models focused on relationships and data access

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database Best Practices

#### Eloquent ORM
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

#### Query Optimization
- Always eager load relationships: `with()`, `load()`
- Use query scopes for reusable query logic
- Implement caching for expensive queries
- Use database indexes on frequently queried columns
- Chunk large datasets: `chunk()`, `chunkById()`

#### Migrations
- One change per migration file
- Use descriptive names: `add_status_to_users_table`
- Never include down() method for rollbacks
- Use foreign key constraints for relationships
- Never modify existing migrations

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.
- **Important**: Models are mass assignment unguarded globally. DO NOT add `$fillable` or `$guarded` properties to any model. This is configured in `AppServiceProvider`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- **Controllers**: Always use Form Request classes for validation rather than inline validation. Include both validation rules and custom error messages.
- **Livewire Components**: Use Form Objects for complex forms instead of inline validation.
- Check sibling Form Requests/Form Objects to see if the application uses array or string based validation rules.

#### Form Request Example (Controllers)
```php
// app/Http/Requests/StorePostRequest.php
class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::enum(PostStatus::class)],
        ];
    }
}

// Controller usage
public function store(PostRequest $request)
{
    $post = Post::create($request->validated());
    // ...
}
```

#### Form Object Example (Livewire)
```php
// app/Livewire/Forms/PostForm.php
class PostForm extends Form
{
    public string $title = '';
    public string $content = '';
    public string $status = '';

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', Rule::enum(PostStatus::class)],
        ];
    }
}

// Livewire component usage
class CreatePost extends Component
{
    public PostForm $form;

    public function save()
    {
        $this->form->validate();
        Post::create($this->form->all());
        // ...
    }
}
```

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization

#### Security Implementation
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).
- Implement policies for model-based authorization
- Use gates for simple boolean checks
- Apply middleware for route protection
- Never trust user input - always validate and authorize

#### Common Patterns
```php
// Policy method
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}

// Gate definition
Gate::define('admin', fn (User $user) => $user->isAdmin());

// Middleware usage
Route::middleware(['auth', 'verified'])->group(...);
```

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration Management

#### Environment Variables
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.
- Cache configuration in production: `php artisan config:cache`
- Clear cache after changes: `php artisan config:clear`

#### Best Practices
- Group related settings in dedicated config files
- Use type casting in config files
- Provide sensible defaults
- Document non-obvious configuration options

### Testing with Pest v4
- **IMPORTANT**: This project uses Pest v4 for testing, not PHPUnit directly. Always write tests using Pest syntax.
- **Code Coverage Requirement**: Aim for 100% code coverage. All new code must have corresponding tests.
- Use Pest's expressive syntax: `test()`, `it()`, `expect()`, `beforeEach()`, `afterEach()`.
- Run tests with coverage: `herd coverage ./vendor/bin/pest --coverage`
- Write descriptive test names that explain what is being tested.
- Use the `pest()` helper for test configuration in tests/Pest.php.
- Group related tests using `describe()` blocks when appropriate.
- Run tests with: `php84 ./vendor/bin/pest` (using PHP 8.4)
- Check coverage with: `herd coverage ./vendor/bin/pest --coverage`
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- Create new test files with: `php artisan make:test [options] <name> --pest` to create Pest tests.
- Most tests should be feature tests rather than unit tests.

#### Pest Best Practices:
- Use `expect()` assertions for cleaner, more readable tests
- Chain expectations: `expect($value)->toBeString()->toContain('test')`
- Use datasets for parameterized testing
- Utilize Pest's Laravel helpers: `actingAs()`, `assertDatabaseHas()`, etc.
- Write tests before implementation when possible (TDD approach)

### Browser Testing with Pest v4
- **IMPORTANT**: This project uses Pest's browser testing plugin, NOT Laravel Dusk directly
- The plugin is already installed: `pestphp/pest-plugin-browser`
- Browser tests go in `tests/Feature/` directory, NOT `tests/Browser/`
- Uses simplified syntax with `visit()` and `assertNoSmoke()` helpers

#### Smoke Testing Pattern
```php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('tests all non-authenticated URLs', function () {
    $routes = ['/', '/login', '/register', '/forgot-password'];
    visit($routes)->assertNoSmoke();
});

it('tests all authenticated URLs', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $authRoutes = ['/dashboard'];
    visit($authRoutes)->assertNoSmoke();
});
```

#### Common Browser Testing Patterns
- **Smoke Testing**: `visit($routes)->assertNoSmoke()` - Checks for JavaScript errors and page loads
- **Authentication**: Use `$this->actingAs($user)` before `visit()` for authenticated routes
- **RefreshDatabase**: Always use `uses(RefreshDatabase::class)` for test isolation
- **Multiple Routes**: Pass array of routes to `visit()` to test multiple pages at once

#### Browser Test Setup
```php
use function Pest\Browser\browse;

it('can visit the homepage', function () {
    browse(function ($browser) {
        $browser->visit('/')
            ->assertSee('Welcome')
            ->screenshot('homepage');
    });
});
```

#### Common Browser Testing Patterns
- **Page interactions**: `click()`, `type()`, `select()`, `check()`
- **Assertions**: `assertSee()`, `assertPathIs()`, `assertPresent()`, `assertMissing()`
- **Screenshots**: `screenshot('name')` for debugging
- **Waiting**: `waitFor()`, `waitForText()`, `waitUntilMissing()`
- **JavaScript execution**: `script()` for custom JS

#### Livewire Browser Testing
```php
it('interacts with livewire component', function () {
    browse(function ($browser) {
        $browser->visit('/dashboard')
            ->waitForLivewire()
            ->type('@search-input', 'Laravel')
            ->waitForText('Search Results')
            ->assertSee('Laravel Documentation');
    });
});
```

#### Best Practices for Browser Tests
- Use data attributes for targeting elements: `data-test="submit-button"`
- Run tests with: `php84 ./vendor/bin/pest tests/Feature/SmokeTest.php`
- Take screenshots on failure for debugging
- Use page objects for complex interactions
- Clean up test data after each test
- Run headless in CI/CD pipelines

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Laravel 12 Specific Features

### Overview
- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.
- Simplified application skeleton
- Improved performance and developer experience
- Native type declarations throughout

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Laravel 12 Database Features
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11+ allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.
- Improved query builder performance
- Better database transaction handling
- Enhanced migration squashing capabilities

### Laravel 12 Model Enhancements
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.
- Attribute casting improvements
- Better performance for large datasets
- Enhanced relationship handling
- Improved model event system


## Flux UI Pro

- **This project has newest Flux UI Pro v2 installed** with full access to all Pro components and features.
- Flux UI is a premium component library for Livewire. Flux is a robust, hand-crafted, UI component library for your Livewire applications. It's built using Tailwind CSS and provides a comprehensive set of components that are easy to use and customize.
- Always prefer Flux UI Pro components when building UI elements.
- Flux Pro includes advanced components like data tables, charts, calendars, and more.
- Use Laravel Boost's `search-docs` tool to get the exact documentation and code snippets for Flux Pro components.
- Flux UI components look like this:

<code-snippet name="Flux UI Component Usage Example" lang="blade">
    <flux:button variant="primary"/>
</code-snippet>


### Available Flux Pro Components
With Flux Pro v2.3.2, you have access to ALL Flux components:

**Components**: accent, accordion, aside, autocomplete, avatar, badge, brand, breadcrumbs, button, calendar, callout, card, chart, checkbox, command, container, context, date-picker, dropdown, editor, field, fieldset, footer, header, heading, icon (300+ icons), input, label, legend, link, main, menu, modal, navbar, navlist, navmenu, pagination, profile, radio, select, separator, sidebar, spacer, subheading, switch, table, tabs, text, textarea, toast, tooltip

### Flux Component Publishing
**NOTE**: Flux components can be published to `resources/views/flux/` for customization using:
```bash
php artisan flux:publish <component-name> --no-interaction
```

**Best Practice**: Only publish components you need to customize. Unpublished components use vendor defaults and receive automatic updates when upgrading Flux.

## Livewire Core
- Use the `search-docs` tool to find exact version specific documentation for how to write Livewire & Livewire tests.
- Use the `php artisan make:livewire [Posts\\Create]` artisan command to create new components
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend, they're like regular HTTP requests. Always validate form data, and run authorization checks in Livewire actions.

### Alpine.js Integration - CRITICAL
- **WARNING: Do NOT manually initialize Alpine.js**
- Alpine.js is already included and managed by Livewire 3
- **Never add** `import Alpine from 'alpinejs'` or `Alpine.start()` to your app.js
- Flux UI components automatically use Livewire's built-in Alpine instance
- Adding Alpine manually will cause conflicts and JavaScript errors

## Livewire Best Practices
- Livewire components require a single root element.
- Use `wire:loading` and `wire:dirty` for delightful loading states.
- Add `wire:key` in loops:

    ```blade
    @foreach ($items as $item)
        <div wire:key="item-{{ $item->id }}">
            {{ $item->name }}
        </div>
    @endforeach
    ```

- Prefer lifecycle hooks like `mount()`, `updatedFoo()`) for initialization and reactive side effects:

<code-snippet name="Lifecycle hook examples" lang="php">
    public function mount(User $user) { $this->user = $user; }
    public function updatedSearch() { $this->resetPage(); }
</code-snippet>


## Testing Livewire

<code-snippet name="Example Livewire component test" lang="php">
    Livewire::test(Counter::class)
        ->assertSet('count', 0)
        ->call('increment')
        ->assertSet('count', 1)
        ->assertSee(1)
        ->assertStatus(200);
</code-snippet>


    <code-snippet name="Testing a Livewire component exists within a page" lang="php">
        $this->get('/posts/create')
        ->assertSeeLivewire(CreatePost::class);
    </code-snippet>

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing
- When listing items, use gap utilities for spacing, don't use margins.

    <code-snippet name="Valid Flex Gap Spacing Example" lang="html">
        <div class="flex gap-8">
            <div>Superior</div>
            <div>Michigan</div>
            <div>Erie</div>
        </div>
    </code-snippet>


## Tailwind CSS v4

### Key Changes
- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- Native CSS cascade layers
- Improved performance
- New utility classes
- Better dark mode support

### Best Practices
- Use semantic color names from design system
- Leverage CSS variables for theming
- Minimize custom CSS - use utilities
- Extract common patterns to components

## Performance Optimization

### Caching Strategies
- Route caching: `php artisan route:cache`
- View caching: `php artisan view:cache`
- Event caching: `php artisan event:cache`
- Query result caching with Redis/Memcached
- Use Laravel Octane for high-performance applications

### Code Optimization
- Lazy load relationships when possible
- Use database indexing strategically
- Implement queue workers for heavy tasks
- Optimize image assets
- Minimize HTTP requests

## Error Handling

### Exception Management
- Use custom exceptions for domain-specific errors
- Implement proper error logging
- Return appropriate HTTP status codes
- Provide helpful error messages in development
- Hide sensitive information in production

### Debugging Tools
- Laravel Telescope for local debugging
- Laravel Debugbar for query analysis
- Use `dd()`, `dump()` sparingly
- Leverage Laravel Boost's tinker tool
- Check `storage/logs/laravel.log` for errors

## Security Best Practices

### Input Validation
- Always validate user input
- Use Form Request classes
- Sanitize data before storage
- Implement CSRF protection
- Use prepared statements (automatic with Eloquent)

### Authentication Security
- Hash passwords with bcrypt/argon2
- Implement rate limiting
- Use secure session configuration
- Enable HTTPS in production
- Implement 2FA when appropriate

## API Development

### RESTful Principles
- Use proper HTTP verbs
- Return consistent JSON structures
- Implement API versioning
- Use API Resources for transformations
- Document endpoints clearly

### API Security
- Implement token-based authentication (Sanctum/Passport)
- Rate limit API endpoints
- Validate API input thoroughly
- Use CORS configuration properly
- Log API access for monitoring

## Common Development Commands

### Cache Management
Laravel uses various caches that can cause issues during development, especially with Blade views and Flux UI components.

**Quick Fix for Most Issues:**
```bash
php artisan optimize:clear  # Clears ALL caches at once
```
