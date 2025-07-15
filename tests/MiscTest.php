<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\Uid\Facades\Uid;
use ShabuShabu\Uid\Tests\App\Models\User;

it('uses the uid facade', function () {
    $user = User::factory()->create();

    expect(Uid::encode($user))->toBeString();
});

it('uses the uid function', function () {
    $user = User::factory()->create();

    expect(uid()->encode($user))->toBeString();
});

it('does not enable a morph map by default', function () {
    $user = User::factory()->create();

    expect($user->getMorphClass())->toBe($user::class);
});
