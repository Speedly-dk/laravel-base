<?php

namespace App\Livewire\TwoFactor;

use App\Services\TwoFactorAuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Onboarding extends Component
{
    public bool $showModal = false;

    public string $confirmationCode = '';

    public array $recoveryCodes = [];

    public string $qrCodeSvg = '';

    public string $step = 'intro';

    protected TwoFactorAuthService $twoFactorService;

    public function boot(TwoFactorAuthService $twoFactorService): void
    {
        $this->twoFactorService = $twoFactorService;
    }

    public function mount(): void
    {
        if (session()->has('two_factor_required')) {
            $this->showModal = true;
            session()->forget('two_factor_required');
        }
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    public function startSetup(): void
    {
        $this->step = 'setup';
        $this->twoFactorService->enableTwoFactor($this->user());
        $this->qrCodeSvg = $this->twoFactorService->generateQrCodeSvg($this->user());
    }

    public function confirmTwoFactor(): void
    {
        $this->validate([
            'confirmationCode' => ['required', 'string', 'size:6'],
        ]);

        if (! $this->twoFactorService->confirmTwoFactor($this->user(), $this->confirmationCode)) {
            $this->addError('confirmationCode', __('The provided two-factor authentication code was invalid.'));

            return;
        }

        $this->recoveryCodes = $this->user()->getTwoFactorRecoveryCodes();
        $this->step = 'recovery';
        $this->confirmationCode = '';
    }

    public function completeSetup(): void
    {
        $this->showModal = false;
        $this->dispatch('two-factor-enabled');
        session()->flash('message', __('Two-factor authentication has been successfully enabled!'));
    }

    public function render()
    {
        return view('livewire.two-factor.onboarding');
    }
}
