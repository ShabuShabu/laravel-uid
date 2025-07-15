<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\Uid\Facades\Uid;
use ShabuShabu\Uid\Tests\App\Models\User;

it('uses the uid facade', function () {
    $user = User::factory()->create();

    expect(Uid::encode($user))->toBeString();
});
