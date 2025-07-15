<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\Uid\Tests\App\Models\User;

it('returns the model', function () {
    $user = User::factory()->create();

    $result = resolve_model(User::class, $user);

    expect($result)
        ->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);
});

it('returns the model for a numeric id', function () {
    $user = User::factory()->create();

    $result = resolve_model(User::class, $user->id);

    expect($result)
        ->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);
});

it('returns the model for a uid', function () {
    $user = User::factory()->create();

    $result = resolve_model(User::class, $user->uid);

    expect($result)
        ->toBeInstanceOf(User::class)
        ->and($result->id)->toBe($user->id);
});

it('returns null', function () {
    $result = resolve_model(User::class, false);

    expect($result)->toBeNull();
});
