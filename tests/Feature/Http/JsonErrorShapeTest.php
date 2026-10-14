<?php

declare(strict_types=1);

use App\Models\User;

it('returns validation errors in the standard shape', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patchJson(route('profile.update'), ['name' => '', 'email' => 'not-an-email'])
        ->assertUnprocessable()
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['success', 'message', 'errors' => ['name', 'email']]);
});

it('returns authentication errors in the standard shape', function (): void {
    $this->getJson(route('dashboard'))
        ->assertUnauthorized()
        ->assertExactJson(['success' => false, 'message' => 'Unauthenticated.', 'errors' => []]);
});
