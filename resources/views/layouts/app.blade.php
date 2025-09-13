<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible class="border-r bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="{{ route('dashboard') }}"
                logo="https://ap3.dk/wp-content/uploads/2025/03/AP3_pos.webp"
            />

            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" current="{{ request()->routeIs('dashboard') }}">{{ __('Home') }}</flux:sidebar.item>
            <flux:sidebar.item icon="inbox" badge="12" href="#">{{ __('Inbox') }}</flux:sidebar.item>
            <flux:sidebar.item icon="document-text" href="#">{{ __('Documents') }}</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">{{ __('Calendar') }}</flux:sidebar.item>

            <flux:sidebar.group expandable icon="star" heading="{{ __('Favorites') }}" class="grid">
                <flux:sidebar.item href="#">{{ __('Marketing site') }}</flux:sidebar.item>
                <flux:sidebar.item href="#">{{ __('Android app') }}</flux:sidebar.item>
                <flux:sidebar.item href="#">{{ __('Brand guidelines') }}</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="#">{{ __('Settings') }}</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">{{ __('Help') }}</flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" name="{{ auth()->user()->name }}" />

            <flux:menu>
                <flux:menu.item>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button type="submit" variant="ghost" size="sm" icon="arrow-right-start-on-rectangle" class="w-full justify-start">
                            {{ __('Logout') }}
                        </flux:button>
                    </form>
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="start">
            <flux:profile avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" />

            <flux:menu>
                <flux:menu.item>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:button type="submit" variant="ghost" size="sm" icon="arrow-right-start-on-rectangle" class="w-full justify-start">
                            {{ __('Logout') }}
                        </flux:button>
                    </form>
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
    @livewireScripts
</body>
</html>
