<?php

use App\Livewire\Auth\Login;
use App\Livewire\TwoFactor\Challenge;
use App\Livewire\TwoFactor\Onboarding;
use App\Livewire\TwoFactor\Setup;
use App\Models\User;
use App\Services\TwoFactorAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->twoFactorService = new TwoFactorAuthService;
    $this->google2fa = new Google2FA;
});

test('user can enable two-factor authentication', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Setup::class)
        ->assertSee('Enable Two-Factor Authentication')
        ->call('enableTwoFactor')
        ->assertSet('showQrCode', true);

    expect($user->fresh()->two_factor_secret)->not->toBeNull();
});

test('user can confirm two-factor authentication with valid code', function () {
    $user = User::factory()->create();
    $secret = $this->twoFactorService->generateSecretKey();
    $user->two_factor_secret = $secret;
    $user->save();

    $validCode = $this->google2fa->getCurrentOtp($secret);

    Livewire::actingAs($user)
        ->test(Setup::class)
        ->set('showQrCode', true)
        ->set('confirmationCode', $validCode)
        ->call('confirmTwoFactor')
        ->assertSet('showRecoveryCodes', true);

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull();
});

test('user cannot confirm two-factor authentication with invalid code', function () {
    $user = User::factory()->create();
    $user->two_factor_secret = $this->twoFactorService->generateSecretKey();
    $user->save();

    Livewire::actingAs($user)
        ->test(Setup::class)
        ->set('showQrCode', true)
        ->set('confirmationCode', '000000')
        ->call('confirmTwoFactor')
        ->assertHasErrors(['confirmationCode']);
});

test('user can disable two-factor authentication', function () {
    $user = User::factory()->create([
        'two_factor_secret' => $this->twoFactorService->generateSecretKey(),
        'two_factor_confirmed_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(Setup::class)
        ->set('showDisableModal', true)
        ->set('disablePassword', 'password')
        ->call('confirmDisableTwoFactor')
        ->assertSet('showDisableModal', false);

    $user->refresh();
    expect($user->two_factor_secret)->toBeNull();
    expect($user->two_factor_confirmed_at)->toBeNull();
});

test('login redirects to two-factor challenge when enabled', function () {
    $user = User::factory()->create([
        'two_factor_secret' => $this->twoFactorService->generateSecretKey(),
        'two_factor_confirmed_at' => now(),
    ]);

    Livewire::test(Login::class)
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertRedirect(route('two-factor.challenge'));

    expect(session('two_factor_user_id'))->toBe($user->id);
});

test('user can authenticate with valid two-factor code', function () {
    $secret = $this->twoFactorService->generateSecretKey();
    $user = User::factory()->create([
        'two_factor_secret' => $secret,
        'two_factor_confirmed_at' => now(),
    ]);

    session(['two_factor_user_id' => $user->id]);
    $validCode = $this->google2fa->getCurrentOtp($secret);

    Livewire::test(Challenge::class)
        ->set('code', $validCode)
        ->call('verify')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('user cannot authenticate with invalid two-factor code', function () {
    $user = User::factory()->create([
        'two_factor_secret' => $this->twoFactorService->generateSecretKey(),
        'two_factor_confirmed_at' => now(),
    ]);

    session(['two_factor_user_id' => $user->id]);

    Livewire::test(Challenge::class)
        ->set('code', '000000')
        ->call('verify')
        ->assertHasErrors(['code']);

    $this->assertGuest();
});

test('user can authenticate with recovery code', function () {
    $user = User::factory()->create([
        'two_factor_secret' => $this->twoFactorService->generateSecretKey(),
        'two_factor_confirmed_at' => now(),
    ]);
    $user->generateTwoFactorRecoveryCodes();
    $recoveryCodes = $user->getTwoFactorRecoveryCodes();

    session(['two_factor_user_id' => $user->id]);

    Livewire::test(Challenge::class)
        ->call('toggleRecoveryCode')
        ->set('recoveryCode', $recoveryCodes[0])
        ->call('verify')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->getTwoFactorRecoveryCodes())->not->toContain($recoveryCodes[0]);
});

test('mandatory two-factor onboarding modal shows for users with required 2FA', function () {
    $user = User::factory()->create(['two_factor_required' => true]);
    session(['two_factor_required' => true]);

    Livewire::actingAs($user)
        ->test(Onboarding::class)
        ->assertSet('showModal', true)
        ->assertSee('Secure Your Account')
        ->assertSee('Two-factor authentication is now required');
});

test('user can complete two-factor onboarding flow', function () {
    $user = User::factory()->create(['two_factor_required' => true]);
    $secret = $this->twoFactorService->generateSecretKey();

    $component = Livewire::actingAs($user)
        ->test(Onboarding::class)
        ->set('showModal', true)
        ->call('startSetup')
        ->assertSet('step', 'setup');

    $user->refresh();
    $validCode = $this->google2fa->getCurrentOtp($user->two_factor_secret);

    $component->set('confirmationCode', $validCode)
        ->call('confirmTwoFactor')
        ->assertSet('step', 'recovery')
        ->call('completeSetup')
        ->assertSet('showModal', false);

    expect($user->fresh()->hasTwoFactorEnabled())->toBeTrue();
});

test('middleware enforces two-factor authentication for protected routes', function () {
    $user = User::factory()->create(['two_factor_required' => true]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('profile.edit'));

    expect(session('two_factor_required'))->toBeTrue();
});

test('user with 2FA enabled must complete challenge before accessing protected routes', function () {
    $user = User::factory()->create([
        'two_factor_secret' => $this->twoFactorService->generateSecretKey(),
        'two_factor_confirmed_at' => now(),
    ]);

    Livewire::test(Login::class)
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertRedirect(route('two-factor.challenge'));

    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('new users are registered with two-factor requirement', function () {
    Livewire::test(\App\Livewire\Auth\Register::class)
        ->set('form.name', 'Test User')
        ->set('form.email', 'test@example.com')
        ->set('form.password', 'password')
        ->set('form.terms', true)
        ->call('register');

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->two_factor_required)->toBeTrue();
});

test('recovery codes are generated when enabling two-factor authentication', function () {
    $user = User::factory()->create();
    $this->twoFactorService->enableTwoFactor($user);

    $recoveryCodes = $user->getTwoFactorRecoveryCodes();

    expect($recoveryCodes)->toBeArray();
    expect($recoveryCodes)->toHaveCount(8);
    expect($recoveryCodes[0])->toMatch('/^[A-Za-z0-9]{10}-[A-Za-z0-9]{10}$/');
});

test('recovery codes can be regenerated', function () {
    $user = User::factory()->create([
        'two_factor_secret' => $this->twoFactorService->generateSecretKey(),
        'two_factor_confirmed_at' => now(),
    ]);
    $user->generateTwoFactorRecoveryCodes();
    $originalCodes = $user->getTwoFactorRecoveryCodes();

    Livewire::actingAs($user)
        ->test(Setup::class)
        ->call('regenerateRecoveryCodes');

    $newCodes = $user->fresh()->getTwoFactorRecoveryCodes();

    expect($newCodes)->not->toBe($originalCodes);
    expect($newCodes)->toHaveCount(8);
});
