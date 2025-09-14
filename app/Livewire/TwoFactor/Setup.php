<?php

namespace App\Livewire\TwoFactor;

use App\Services\TwoFactorAuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Setup extends Component
{
    public string $confirmationCode = '';

    public bool $showQrCode = false;

    public bool $showRecoveryCodes = false;

    public bool $showDisableModal = false;

    public array $recoveryCodes = [];

    public string $qrCodeSvg = '';

    public string $disablePassword = '';

    protected TwoFactorAuthService $twoFactorService;

    public function boot(TwoFactorAuthService $twoFactorService): void
    {
        $this->twoFactorService = $twoFactorService;
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    public function enableTwoFactor(): void
    {
        if ($this->user()->hasTwoFactorEnabled()) {
            return;
        }

        $this->twoFactorService->enableTwoFactor($this->user());
        $this->qrCodeSvg = $this->twoFactorService->generateQrCodeSvg($this->user());
        $this->showQrCode = true;
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
        $this->showQrCode = false;
        $this->showRecoveryCodes = true;
        $this->confirmationCode = '';
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->user()->generateTwoFactorRecoveryCodes();
        $this->recoveryCodes = $this->user()->getTwoFactorRecoveryCodes();
        $this->showRecoveryCodes = true;
    }

    public function downloadRecoveryCodes(): void
    {
        $codes = implode("\n", $this->recoveryCodes);
        $filename = 'recovery-codes-'.now()->format('Y-m-d').'.txt';

        $this->dispatch('download', [
            'content' => $codes,
            'filename' => $filename,
        ]);
    }

    public function confirmDisableTwoFactor(): void
    {
        $this->validate([
            'disablePassword' => ['required', 'current_password'],
        ]);

        $this->twoFactorService->disableTwoFactor($this->user());

        $this->reset(['showDisableModal', 'disablePassword']);
        $this->dispatch('two-factor-disabled');

        session()->flash('message', __('Two-factor authentication has been disabled.'));
    }

    public function cancelSetup(): void
    {
        if (! $this->user()->two_factor_confirmed_at) {
            $this->twoFactorService->disableTwoFactor($this->user());
        }

        $this->reset(['showQrCode', 'showRecoveryCodes', 'confirmationCode', 'recoveryCodes', 'qrCodeSvg']);
    }

    public function render()
    {
        return view('livewire.two-factor.setup');
    }
}
