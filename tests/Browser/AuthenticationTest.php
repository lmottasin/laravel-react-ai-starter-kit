<?php

declare(strict_types=1);

use App\Models\User;

it('lets a user log in and reach the dashboard', function (): void {
    $user = User::factory()->create(['password' => 'password']);

    visit('/login')
        ->assertSee('Log in to your account')
        ->type('email', $user->email)
        ->type('password', 'password')
        ->press('@login-button')
        ->assertPathIs('/dashboard')
        ->assertNoJavaScriptErrors();
});

it('shows an error for a wrong password', function (): void {
    $user = User::factory()->create();

    visit('/login')
        ->type('email', $user->email)
        ->type('password', 'wrong-password')
        ->press('@login-button')
        ->assertPathIs('/login')
        ->assertSee('These credentials do not match our records.');
});

it('keeps guests out of the dashboard', function (): void {
    visit('/dashboard')
        ->assertPathIs('/login');
});
