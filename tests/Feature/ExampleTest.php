<?php

test('the application redirects to dashboard', function () {
    $response = $this->get('/');

    $response->assertStatus(302);
    $response->assertRedirect('/dashboard');
});

test('the dashboard loads successfully', function () {
    $response = $this->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Dashboard');
    $response->assertSee('Nexus Monitor');
});
