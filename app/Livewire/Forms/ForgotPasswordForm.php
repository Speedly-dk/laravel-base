<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ForgotPasswordForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    public function sendResetLink(): string
    {
        $this->validate();

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return '';
        }

        return $status;
    }
}