<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ShabuShabu\Uid\Service\DecodedUid;
use ShabuShabu\Uid\Service\Uid;
use ShabuShabu\Uid\Tests\App\Models\User;
use Sqids\Sqids;

use function Pest\Laravel\get;

it('encodes a model id to a hash', function () {
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

it('decodes a uid', function () {
    $user = User::factory()->create();

    $sqid = app(Sqids::class)->encode([$user->id]);

    $result = Uid::make()->decode($user->uid);

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
    expect(Uid::alias(User::class))->toBe('usr');
});

it('panics for an invalid class', function () {
    Uid::alias('Some\Undefined\Class');
})->throws(RuntimeException::class);
