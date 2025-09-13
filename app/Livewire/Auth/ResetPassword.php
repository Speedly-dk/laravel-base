<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\ResetPasswordForm;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class ResetPassword extends Component
{
    public ResetPasswordForm $form;

    #[Url]
    public string $token = '';

    #[Url]
    public string $email = '';

    public function mount(): void
    {
        $this->form->token = $this->token;
        $this->form->email = $this->email;
    }

    public function resetPassword(): void
    {
        $status = $this->form->resetPassword();

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            $this->redirect(route('login'));
        }
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}