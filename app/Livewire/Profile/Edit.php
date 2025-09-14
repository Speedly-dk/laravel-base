<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Edit extends Component
{
    public string $name = '';
    public string $email = '';

    public string $current_password = '';
    public string $password = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults()],
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Profile updated successfully!');
        $this->dispatch('profile-updated');
    }

    public function updatePassword(): void
    {
        $this->validate($this->passwordRules());

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password']);

        session()->flash('password_message', 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.profile.edit')
            ->layout('layouts.app');
    }
}