<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResetPasswordForm extends Form
{
    public string $token = '';

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    public function resetPassword(): string
    {
        $this->validate();

        $status = Password::reset(
            $this->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return '';
        }

        return $status;
    }
}