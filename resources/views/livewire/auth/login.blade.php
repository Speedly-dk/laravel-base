<div class="flex min-h-screen bg-white">
    <div class="flex items-center justify-center flex-1">
        <div class="space-y-6 text-gray-900 w-80 max-w-80">
            <div class="flex justify-center">
                <a href="/" class="block">
                    <x-logo class="w-auto h-12" />
                </a>
            </div>

            <flux:heading class="text-center" size="xl">{{ __('Welcome back') }}</flux:heading>

            <form wire:submit="login" class="space-y-6">
                <div class="flex flex-col gap-6">
                    <flux:input wire:model="form.email" label="{{ __('Email') }}" type="email" placeholder="{{ __('email@example.com') }}" />

                    <flux:field>
                        <div class="flex justify-between mb-3">
                            <flux:label>{{ __('Password') }}</flux:label>

                            <flux:link href="#" variant="subtle" class="text-sm">{{ __('Forgot password?') }}</flux:link>
                        </div>

                        <flux:input wire:model="form.password" type="password" placeholder="{{ __('Your password') }}" />
                    </flux:field>

                    <flux:checkbox wire:model="form.remember" label="{{ __('Remember me for 30 days') }}" />

                    <flux:button type="submit" variant="primary" class="w-full">{{ __('Log in') }}</flux:button>
                </div>

                <flux:subheading class="text-center text-gray-600">
                    {{ __('First time around here?') }} <flux:link href="#" class="text-gray-900">{{ __('Sign up for free') }}</flux:link>
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
                {{ __('We build web solutions that simplify, communicate, and create value – with a focus on close relationships and genuine understanding of your business.') }}
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
