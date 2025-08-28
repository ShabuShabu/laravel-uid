<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ShabuShabu\Uid\Service\DecodedUid;
use ShabuShabu\Uid\Service\Uid;
use ShabuShabu\Uid\Tests\App\Models\Contact;
use ShabuShabu\Uid\Tests\App\Models\User;
use Sqids\Sqids;

use function Pest\Laravel\get;

it('encodes a model to a hash', function () {
    $user = User::factory()->create();

    $uid = Uid::make()->encode($user);

    $sqid = app(Sqids::class)->encode([$user->id]);

    $separator = config('uid.separator');

    expect($uid)
        ->toStartWith("usr$separator")
        ->toBe("usr$separator$sqid");
});

it('automatically creates and appends a uid to a model', function () {
    $user = User::factory()->create();

    expect($user->uid)
        ->toStartWith($prefix = 'usr' . config('uid.separator'))
        ->toHaveLength(Str::length($prefix) + config('uid.length'))
        ->and($user->getAppends())->toContain('uid');
});

it('encodes a model id to a hash', function () {
    $user = User::factory()->create();

    $uid = Uid::make()->encodeFromId(User::class, $user->id);

    $sqid = app(Sqids::class)->encode([$user->id]);

    $separator = config('uid.separator');

    expect($uid)
        ->toStartWith("usr$separator")
        ->toBe("usr$separator$sqid");
});

test('a model encodes ids', function () {
    $uid = User::encodeId(1);

    expect($uid)->toBe(
        Uid::make()->encodeFromId(User::class, 1)
    );
});

test('a model decodes uids', function () {
    $user = User::factory()->create();

    expect($user->is(User::decodeUid($user->uid)))->toBeTrue();
});

it('gets a model prefix', function () {
    expect(User::uidPrefix())->toBe('usr');
});

it('decodes a uid', function () {
    $user = User::factory()->create();

    $sqid = app(Sqids::class)->encode([$user->id]);

    $result = Uid::make()->decode($user->uid);

    expect($result)->toBeInstanceOf(DecodedUid::class)
        ->and($result->prefix)->toBe('usr')
        ->and($result->modelId)->toBe($user->id)
        ->and($result->hashId)->toBe($sqid);
});

it('decodes a uid while enforcing a type', function () {
    $user = User::factory()->create();

    $sqid = app(Sqids::class)->encode([$user->id]);

    $result = Uid::make()->decodeOrFail($user->uid, User::class);

    expect($result)->toBeInstanceOf(DecodedUid::class)
        ->and($result->prefix)->toBe('usr')
        ->and($result->modelId)->toBe($user->id)
        ->and($result->hashId)->toBe($sqid);
});

it('decodes a uid to a model', function () {
    $user = User::factory()->create();

    $model = Uid::make()->decodeToModel($user->uid);

    expect($model)->toBeInstanceOf(User::class)
        ->and($model->getKey())->toBe($user->getKey());
});

it('decodes a uid to a model while enforcing a type', function () {
    $user = User::factory()->create();

    $model = Uid::make()->decodeToModel($user->uid, User::class);

    expect($model)->toBeInstanceOf(User::class)
        ->and($model->getKey())->toBe($user->getKey());
});

it('panics for an invalid model while decoding to a model', function () {
    $user = User::factory()->create();

    Uid::make()->decodeToModel($user->uid, Contact::class);
})->throws(RuntimeException::class);

it('uses a uid in implicit route model binding', function () {
    Route::domain('test.com')->middleware('web')->get('/{model:uid}', function (User $model) {
        return response($model->uid, 200);
    });

    $user = User::factory()->create();

    get("https://test.com/$user->uid")
        ->assertSuccessful()
        ->assertContent($user->uid);
});

it('retrieves a uid alias from a class string', function () {
    expect(Uid::make()->alias(User::class))->toBe('usr');
});

it('panics for an invalid class', function () {
    Uid::make()->alias('Some\Undefined\Class');
})->throws(RuntimeException::class);

it('panics for an invalid type when decoding', function () {
    $user = User::factory()->create();

    Uid::make()->decodeOrFail($user->uid, Contact::class);
})->throws(RuntimeException::class);

it('uses a custom alphabet', function () {
    $user = User::factory()->create();
    $userUid = Uid::make()->decode($user->uid);

    $contact = Contact::factory()->create();
    $contactUid = Uid::make()->decode($contact->uid);

    expect($userUid->modelId)->toBe($contactUid->modelId)
        ->and($userUid->hashId)->not->toBe($contactUid->hashId)
        ->and($user->uidAlphabet())->toBe(config('uid.alphabet'))
        ->and($contact->uidAlphabet())->toBe(config('uid.alphabets.con'));
});
