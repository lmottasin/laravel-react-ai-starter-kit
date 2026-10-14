<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

it('logs profile changes without sensitive fields', function (): void {
    $user = User::factory()->create(['name' => 'Old Name']);

    $user->update(['name' => 'New Name', 'password' => 'new-secret-password']);

    $activity = Activity::query()->where('log_name', 'user')->latest('id')->firstOrFail();

    expect($activity->event)->toBe('updated')
        ->and($activity->attribute_changes?->toArray())->toBe([
            'attributes' => ['name' => 'New Name'],
            'old' => ['name' => 'Old Name'],
        ]);
});
