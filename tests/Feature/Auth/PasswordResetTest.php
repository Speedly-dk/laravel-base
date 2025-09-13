<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('forgot password screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
    $response->assertSeeLivewire(ForgotPassword::class);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('form.email', $user->email)
        ->call('sendResetLink')
        ->assertHasNoErrors()
        ->assertSet('status', 'We have emailed your password reset link.');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('reset password link request requires valid email', function () {
    Livewire::test(ForgotPassword::class)
        ->set('form.email', 'not-an-email')
        ->call('sendResetLink')
        ->assertHasErrors(['form.email' => 'email']);
});

test('reset password link request requires existing user', function () {
    Livewire::test(ForgotPassword::class)
        ->set('form.email', 'nonexistent@example.com')
        ->call('sendResetLink')
        ->assertHasErrors(['form.email']);
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

    $response->assertStatus(200);
    $response->assertSeeLivewire(ResetPassword::class);
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->set('form.password', 'new-password')
        ->call('resetPassword')
        ->assertRedirect('/login')
        ->assertSessionHas('status');

    $this->assertTrue(
        auth()->attempt([
            'email' => $user->email,
            'password' => 'new-password',
        ])
    );
});

test('password reset requires minimum length', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->set('form.password', 'short')
        ->call('resetPassword')
        ->assertHasErrors(['form.password' => 'min']);
});

test('password reset requires valid token', function () {
    $user = User::factory()->create();

    Livewire::test(ResetPassword::class, ['token' => 'invalid-token', 'email' => $user->email])
        ->set('form.password', 'new-password')
        ->call('resetPassword')
        ->assertHasErrors(['form.email']);
});

test('password reset requires valid email', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token, 'email' => 'wrong@example.com'])
        ->set('form.password', 'new-password')
        ->call('resetPassword')
        ->assertHasErrors(['form.email']);
});

test('authenticated users cannot access forgot password page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/forgot-password')
        ->assertRedirect('/dashboard');
});

test('authenticated users cannot access reset password page', function () {
    $user = User::factory()->create();

    $token = Password::createToken($user);

    $this->actingAs($user)
        ->get('/reset-password/'.$token.'?email='.$user->email)
        ->assertRedirect('/dashboard');
});