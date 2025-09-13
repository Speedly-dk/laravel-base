<div>
    <flux:heading size="xl">{{ __('Good') }} {{ now()->format('A') === 'AM' ? __('morning') : __('afternoon') }}, {{ auth()->user()->name }}!</flux:heading>
    <flux:subheading>{{ __('Welcome back to your dashboard') }}</flux:subheading>
</div>