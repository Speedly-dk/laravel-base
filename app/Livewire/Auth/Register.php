<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\RegisterForm;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Register extends Component
{
    public RegisterForm $form;

    public function register(): void
    {
        $this->validate();

        $this->form->register();

        $this->redirectIntended(default: route('dashboard'));
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}