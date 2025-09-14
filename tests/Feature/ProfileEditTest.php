<?php

use App\Livewire\Profile\Edit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('requires authentication to view profile page', function () {
    $this->get('/profile')
        ->assertRedirect('/login');
});

it('can view profile page when authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/profile')
        ->assertStatus(200)
        ->assertSeeLivewire(Edit::class);
});

it('loads current user data in profile form', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->assertSet('name', 'John Doe')
        ->assertSet('email', 'john@example.com');
});

it('can update profile information', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->set('name', 'John Doe')
        ->set('email', 'john.doe@example.com')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('profile-updated');

    expect($user->fresh())
        ->name->toBe('John Doe')
        ->email->toBe('john.doe@example.com');
});

it('validates required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->set('name', '')
        ->set('email', '')
        ->call('save')
        ->assertHasErrors(['name', 'email']);
});

it('validates email format', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->set('email', 'invalid-email')
        ->call('save')
        ->assertHasErrors(['email']);
});

it('can update password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->set('current_password', 'old-password')
        ->set('password', 'new-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

it('validates current password is correct', function () {
    $user = User::factory()->create([
        'password' => bcrypt('correct-password'),
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);
});

it('clears password fields after successful update', function () {
    $user = User::factory()->create([
        'password' => bcrypt('old-password'),
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class)
        ->set('current_password', 'old-password')
        ->set('password', 'new-password')
        ->call('updatePassword')
        ->assertSet('current_password', '')
        ->assertSet('password', '');
});