<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Validator;
use ShabuShabu\Uid\Service\Rule;
use ShabuShabu\Uid\Service\Uid;
use ShabuShabu\Uid\Tests\App\Models\Contact;
use ShabuShabu\Uid\Tests\App\Models\User;

it('validates a uid against a given class', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $rule = new Rule(User::class);

    $rule->validate('user', $user->uid, function (?string $message = null) {
        expect($message)->toBe('uid::validation.invalid');
    });
})->throwsNoExceptions();

it('validates a uid', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $rule = new Rule;

    $rule->validate('user', $user->uid, function (?string $message = null) {
        expect($message)->toBe('uid::validation.invalid');
    });
})->throwsNoExceptions();

it('translates the validation message', function () {
    /** @var User $user */
    $user = User::factory()->create();

    $validator = Validator::make([
        'contact' => $user->uid,
    ], [
        'contact' => Uid::rule(Contact::class),
    ]);

    expect($validator)
        ->fails()->toBeTrue()
        ->messages()->first()->toBe('The contact must be a valid uid.');
});

it('panics for an invalid uid', function () {
    $rule = new Rule(User::class);

    $rule->validate('user', 'prt_hfkerkcd', function () {
        throw new RuntimeException('Invalid uid');
    });
})->throws(RuntimeException::class);

it('panics for the wrong uid', function () {
    /** @var Contact $user */
    $contact = Contact::factory()->create();

    $rule = new Rule(User::class);

    $rule->validate('user', $contact->uid, function () {
        throw new RuntimeException('Wrong uid');
    });
})->throws(RuntimeException::class);
