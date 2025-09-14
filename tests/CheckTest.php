<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\Uid\Tests\App\Models\Contact;
use ShabuShabu\Uid\Tests\App\Models\User;

it('panics for non-existing actions', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'whatever')
        ->assertExitCode(1);
});

it('confirms that prefixes are in sync with its models', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'models')
        ->expectsQuestion('Where are your models located?', __DIR__ . '/app/Models')
        ->expectsQuestion('What is the base namespace?', 'ShabuShabu\\Uid\\Tests\\App\Models\\')
        ->expectsOutputToContain('Prefixes and models are in sync!')
        ->assertExitCode(0);
});

it('panics for missing model prefixes', function () {
    config([
        'uid.prefixes' => [
            'usr' => User::class,
        ],
    ]);

    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'models')
        ->expectsQuestion('Where are your models located?', __DIR__ . '/app/Models')
        ->expectsQuestion('What is the base namespace?', 'ShabuShabu\\Uid\\Tests\\App\Models\\')
        ->expectsOutputToContain('model does not have a corresponding prefix:')
        ->expectsOutputToContain(Contact::class)
        ->assertExitCode(1);
});

it('panics for duplicate model prefixes', function () {
    config([
        'uid.prefixes' => [
            'usr' => User::class,
            'con' => Contact::class,
            'cnt' => Contact::class,
        ],
    ]);

    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'models')
        ->expectsQuestion('Where are your models located?', __DIR__ . '/app/Models')
        ->expectsQuestion('What is the base namespace?', 'ShabuShabu\\Uid\\Tests\\App\Models\\')
        ->expectsOutputToContain('Duplicate prefixes found:')
        ->expectsOutputToContain(Contact::class)
        ->assertExitCode(1);
});

it('confirms that alphabets are in sync with prefixes', function () {
    config([
        'uid.alphabets' => [
            'usr' => 'bVhoG706KLHC2OkEXN5tinPjfwJD3lmupYrRIeMS89ATcUgqszydQ1ZW4aFvBx',
            'con' => 'VbhoG706KLHC2OkEXN5tinPjfwJD3lmupYrRIeMS89ATcUgqszydQ1ZW4aFvBx',
        ],
    ]);

    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'alphabets')
        ->expectsOutputToContain('Alphabets and prefixes are in sync!')
        ->assertExitCode(0);
});

it('panics for missing alphabets', function () {
    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'alphabets')
        ->expectsOutputToContain('prefix is missing from the alphabets config:')
        ->expectsOutputToContain('usr')
        ->assertExitCode(1);
});

it('panics for missing alphabet prefixes', function () {
    config([
        'uid.alphabets' => [
            'usr' => 'bVhoG706KLHC2OkEXN5tinPjfwJD3lmupYrRIeMS89ATcUgqszydQ1ZW4aFvBx',
            'con' => 'VbhoG706KLHC2OkEXN5tinPjfwJD3lmupYrRIeMS89ATcUgqszydQ1ZW4aFvBx',
            'wtf' => 'VbhoG706KLHC2OkEX5NtinPjfwJD3lmupYrRIeMS89ATcUgqszydQ1ZW4aFvBx',
        ],
    ]);

    /* @phpstan-ignore variable.undefined */
    $this
        ->artisan('uid:check')
        ->expectsQuestion('What do you want to check?', 'alphabets')
        ->expectsOutputToContain('alphabet does not have a corresponding prefix:')
        ->expectsOutputToContain('wtf')
        ->assertExitCode(1);
});
