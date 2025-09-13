<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSeeLivewire(Login::class);
});

test('users can authenticate using the login form', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Livewire::test(Login::class)
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password')
        ->call('login')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
});

test('users cannot authenticate with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Livewire::test(Login::class)
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'wrong-password')
        ->call('login')
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
});

test('users cannot authenticate with non-existent email', function () {
    Livewire::test(Login::class)
        ->set('form.email', 'nonexistent@example.com')
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.email'])
        ->assertNoRedirect();

    $this->assertGuest();
});

test('login form validates required fields', function () {
    Livewire::test(Login::class)
        ->set('form.email', '')
        ->set('form.password', '')
        ->call('login')
        ->assertHasErrors(['form.email', 'form.password']);
});

test('login form validates email format', function () {
    Livewire::test(Login::class)
        ->set('form.email', 'not-an-email')
        ->set('form.password', 'password')
        ->call('login')
        ->assertHasErrors(['form.email']);
});

test('authenticated users are redirected from login page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect(route('dashboard'));
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('remember me functionality works', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Livewire::test(Login::class)
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password')
        ->set('form.remember', true)
        ->call('login')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertNotNull(auth()->user()->getRememberToken());
});

test('login is rate limited after too many failed attempts', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('form.email', 'test@example.com')
            ->set('form.password', 'wrong-password')
            ->call('login');
    }

    Livewire::test(Login::class)
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'wrong-password')
        ->call('login')
        ->assertHasErrors(['form.email']);
});
