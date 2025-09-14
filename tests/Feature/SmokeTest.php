<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('tests all non-authenticated URLs', function () {
    $nonAuthRoutes = [
        '/',
        '/login',
        '/register',
        '/forgot-password',
    ];

    visit($nonAuthRoutes)->assertNoSmoke();
});

it('tests all authenticated URLs', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $authRoutes = [
        '/dashboard',
    ];

    visit($authRoutes)->assertNoSmoke();
});
