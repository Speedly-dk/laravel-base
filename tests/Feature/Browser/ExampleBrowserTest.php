<?php

test('example browser test', function () {
    $this->visit('http://localhost:8000')
        ->assertSee('Laravel');
});

test('can interact with forms', function () {
    $this->visit('http://localhost:8000/login')
        ->type('#email', 'user@example.com')
        ->type('#password', 'password')
        ->click('button[type="submit"]')
        ->assertPathIs('/dashboard');
})->skip('This is just an example - adjust for your actual routes');

test('can wait for elements', function () {
    $this->visit('http://localhost:8000')
        ->waitForText('Welcome', 10)
        ->assertSee('Welcome');
})->skip('Example of waiting for dynamic content');

test('can interact with JavaScript', function () {
    $this->visit('http://localhost:8000')
        ->click('#modal-button')
        ->waitFor('.modal')
        ->assertVisible('.modal');
})->skip('Example of JavaScript interaction');

test('can take screenshots', function () {
    $this->visit('http://localhost:8000')
        ->screenshot('homepage');
})->skip('Example of taking screenshots');

test('can test multiple pages simultaneously', function () {
    $pages = $this->visit([
        'http://localhost:8000',
        'http://localhost:8000/about',
        'http://localhost:8000/contact',
    ]);

    $pages->each(function ($page) {
        $page->assertSuccessful();
    });
})->skip('Example of testing multiple pages');
