<div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <livewire:two-factor.onboarding />

    <div class="max-w-3xl mx-auto space-y-8">
        {{-- Profile Information Form --}}
        <form wire:submit="save">
            <div class="space-y-12">
                <div class="pb-12 border-b border-gray-900/10">
                    <flux:heading size="lg" level="2">{{ __('Profile Information') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('Update your account information.') }}</flux:text>

                    <div class="grid grid-cols-1 mt-10 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <flux:field>
                                <flux:label for="name">{{ __('Name') }}</flux:label>
                                <flux:input wire:model="name" id="name" type="text" />
                                <flux:error name="name" />
                            </flux:field>
                        </div>

                        <div class="sm:col-span-4">
                            <flux:field>
                                <flux:label for="email">{{ __('Email address') }}</flux:label>
                                <flux:input wire:model="email" id="email" type="email" />
                                <flux:error name="email" />
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 gap-x-6">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>

        {{-- Update Password Form --}}
        <form wire:submit="updatePassword">
            <div class="space-y-12">
                <div class="pb-12 border-b border-gray-900/10">
                    <flux:heading size="lg" level="2">{{ __('Update Password') }}</flux:heading>
                    <flux:text class="mt-1">{{ __('Ensure your account is using a long, random password to stay secure.') }}</flux:text>

                    <div class="grid grid-cols-1 mt-10 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <flux:field>
                                <flux:label for="current_password">{{ __('Current Password') }}</flux:label>
                                <flux:input wire:model="current_password" id="current_password" type="password" />
                                <flux:error name="current_password" />
                            </flux:field>
                        </div>

                        <div class="sm:col-span-4">
                            <flux:field>
                                <flux:label for="password">{{ __('New Password') }}</flux:label>
                                <flux:input wire:model="password" id="password" type="password" />
                                <flux:error name="password" />
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 gap-x-6">
                <flux:button variant="primary" type="submit">{{ __('Update Password') }}</flux:button>
            </div>
        </form>

        {{-- Two-Factor Authentication --}}
        <div class="pb-12 border-b border-gray-900/10">
            <flux:heading size="lg" level="2">{{ __('Security') }}</flux:heading>
            <flux:text class="mt-1">{{ __('Manage your account security settings.') }}</flux:text>

            <div class="mt-10">
                <livewire:two-factor.setup />
            </div>
        </div>

        @if(session()->has('message'))
            <flux:toast>
                {{ session('message') }}
            </flux:toast>
        @endif

        @if(session()->has('password_message'))
            <flux:toast>
                {{ session('password_message') }}
            </flux:toast>
        @endif
    </div>
</div>
