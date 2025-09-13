<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSeeLivewire(Register::class);
});

test('new users can register', function () {
    Livewire::test(Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password123')
        ->set('form.terms', true)
        ->call('register')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Test User');
});

test('registration requires all fields', function () {
    Livewire::test(Register::class)
        ->call('register')
        ->assertHasErrors([
            'form.name' => 'required',
            'form.email' => 'required',
            'form.password' => 'required',
            'form.terms' => 'accepted',
        ]);
});

test('registration requires valid email', function () {
    Livewire::test(Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'not-an-email')
        ->set('form.password', 'password123')
        ->set('form.terms', true)
        ->call('register')
        ->assertHasErrors(['form.email' => 'email']);
});

test('registration requires unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'existing@example.com')
        ->set('form.password', 'password123')
        ->set('form.terms', true)
        ->call('register')
        ->assertHasErrors(['form.email' => 'unique']);
});


test('registration requires minimum password length', function () {
    Livewire::test(Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'short')
        ->set('form.terms', true)
        ->call('register')
        ->assertHasErrors(['form.password' => 'min']);
});

test('registration requires accepting terms', function () {
    Livewire::test(Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password123')
        ->set('form.terms', false)
        ->call('register')
        ->assertHasErrors(['form.terms' => 'accepted']);
});

test('authenticated users cannot access registration page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/register')
        ->assertRedirect('/dashboard');
});