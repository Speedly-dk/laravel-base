<?php

namespace App\Livewire\TwoFactor;

use App\Models\User;
use App\Services\TwoFactorAuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Challenge extends Component
{
    public string $code = '';

    public string $recoveryCode = '';

    public bool $useRecoveryCode = false;

    public ?int $userId = null;

    public string $remember = '';

    protected TwoFactorAuthService $twoFactorService;

    public function boot(TwoFactorAuthService $twoFactorService): void
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function mount(): void
    {
        if (! session()->has('two_factor_user_id')) {
            $this->redirect(route('login'), navigate: true);
        }

        $this->userId = session('two_factor_user_id');
        $this->remember = session('two_factor_remember', '');
    }

    public function verify(): void
    {
        $this->ensureIsNotRateLimited();

        if ($this->useRecoveryCode) {
            $this->validateRecoveryCode();
        } else {
            $this->validateCode();
        }
    }

    protected function validateCode(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = User::findOrFail($this->userId);

        if (! $this->twoFactorService->isValidCode($user, $this->code)) {
            RateLimiter::hit($this->throttleKey());

            $this->addError('code', __('The provided two-factor authentication code was invalid.'));

            return;
        }

        $this->authenticateUser($user);
    }

    protected function validateRecoveryCode(): void
    {
        $this->validate([
            'recoveryCode' => ['required', 'string'],
        ]);

        $user = User::findOrFail($this->userId);

        if (! $this->twoFactorService->isValidRecoveryCode($user, $this->recoveryCode)) {
            RateLimiter::hit($this->throttleKey());

            $this->addError('recoveryCode', __('The provided recovery code was invalid.'));

            return;
        }

        $this->authenticateUser($user);
    }

    protected function authenticateUser(User $user): void
    {
        Auth::login($user, $this->remember === 'on');

        RateLimiter::clear($this->throttleKey());

        session()->forget(['two_factor_user_id', 'two_factor_remember']);

        session()->regenerate();

        $this->redirect(session('url.intended', route('dashboard')), navigate: true);
    }

    public function toggleRecoveryCode(): void
    {
        $this->useRecoveryCode = ! $this->useRecoveryCode;
        $this->reset(['code', 'recoveryCode']);
        $this->resetValidation();
    }

    public function cancel(): void
    {
        session()->forget(['two_factor_user_id', 'two_factor_remember']);
        $this->redirect(route('login'), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->addError('code', trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]));

        $this->dispatch('rate-limited');
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->userId).'|'.request()->ip();
    }

    public function render()
    {
        return view('livewire.two-factor.challenge')
            ->layout('layouts.guest');
    }
}
