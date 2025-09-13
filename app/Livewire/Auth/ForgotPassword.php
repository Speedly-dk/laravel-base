<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\ForgotPasswordForm;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ForgotPassword extends Component
{
    public ForgotPasswordForm $form;

    public ?string $status = null;

    public function sendResetLink(): void
    {
        $status = $this->form->sendResetLink();

        if ($status) {
            $this->status = __($status);
            $this->form->reset('email');
        }
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}