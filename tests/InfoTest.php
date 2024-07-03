<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\Uid\Tests\App\Models\User;

it('panics for a missing model', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:info usr_LfeO94G20')
        ->expectsOutputToContain("A model with the uid of usr_LfeO94G20 wasn't found.")
        ->assertExitCode(1);
});

it('panics for an unknown prefix', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:info pos_LfeO94G20')
        ->expectsOutputToContain('No model class defined for prefix `pos`')
        ->assertExitCode(1);
});

it('shows model information for a uid', function () {
    $user = User::factory()->create();

    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:info')
        ->expectsQuestion('What uid do you want to get info about?', $user->uid)
        ->assertExitCode(0);
});
