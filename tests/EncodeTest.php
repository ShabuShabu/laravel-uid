<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use ShabuShabu\Uid\Tests\App\Models\User;

it('encodes a model id', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:encode')
        ->expectsChoice('Select a model', User::class, array_values(Config::array('uid.prefixes')))
        ->expectsQuestion('Which ID should be encoded?', 1)
        ->expectsOutputToContain('Here is your uid: ')
        ->assertExitCode(0);
});

it('encodes a model id with default params', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:encode', [
            'model' => User::class,
            'id' => 1,
        ])
        ->expectsOutputToContain('Here is your uid: ')
        ->assertExitCode(0);
});
