<div class="flex min-h-screen bg-white">
    <div class="flex items-center justify-center flex-1">
        <div class="space-y-6 text-gray-900 w-80 max-w-80">
            <div class="flex justify-center">
                <a href="/" class="block">
                    <x-logo class="w-auto h-12" />
                </a>
            </div>

            <flux:heading class="text-center" size="xl">{{ __('Create your account') }}</flux:heading>

            <form wire:submit="register" class="space-y-6">
                <div class="flex flex-col gap-6">
                    <flux:input wire:model="form.name" label="{{ __('Full name') }}" type="text" placeholder="{{ __('John Doe') }}" />

                    <flux:input wire:model="form.email" label="{{ __('Email') }}" type="email" placeholder="{{ __('email@example.com') }}" />

                    <flux:field>
                        <flux:label>{{ __('Password') }}</flux:label>
                        <flux:input wire:model="form.password" type="password" placeholder="{{ __('At least 8 characters') }}" />
                        <flux:description>{{ __('Must be at least 8 characters') }}</flux:description>
                    </flux:field>

                    <flux:field variant="inline">
                        <flux:checkbox wire:model="form.terms" />
                        <flux:label>
                            {{ __('I agree to the') }}
                            <flux:link href="#" class="ml-1">{{ __('Terms and Conditions') }}</flux:link>
                        </flux:label>
                        <flux:error name="form.terms" />
                    </flux:field>

                    <flux:button type="submit" variant="primary" class="w-full">{{ __('Create account') }}</flux:button>
                </div>

                <flux:subheading class="text-center text-gray-600">
                    {{ __('Already have an account?') }} <flux:link href="{{ route('login') }}" class="text-gray-900">{{ __('Sign in') }}</flux:link>
                </flux:subheading>
            </form>
        </div>
    </div>

    <div class="flex-1 p-4 max-lg:hidden">
        <div class="relative flex flex-col items-start justify-end w-full h-full p-16 text-white rounded-lg bg-zinc-900" style="background-image: url('/img/demo/bg.png'); background-size: cover">
            <div class="flex gap-2 mb-4">
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
                <flux:icon.star variant="solid" />
            </div>

            <div class="mb-6 text-3xl italic font-base xl:text-4xl">
                {{ __('Join thousands of developers building amazing applications with Laravel and modern tools.') }}
            </div>

            <div class="flex gap-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/10">
                    <x-logo class="w-auto h-4" />
                </div>

                <div class="flex flex-col justify-center font-medium">
                    <div class="text-lg">{{ __('AP3') }}</div>
                    <div class="text-zinc-300">{{ __('Digital Solutions Partner') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
