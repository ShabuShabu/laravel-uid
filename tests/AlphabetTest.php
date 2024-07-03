<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

it('creates a custom alphabet', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:alphabet')
        ->expectsOutputToContain('Add this to your .env file: ')
        ->assertExitCode(0);
});
