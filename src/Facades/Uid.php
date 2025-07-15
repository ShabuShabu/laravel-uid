<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use ShabuShabu\Uid\Service\DecodedUid;

/**
 * @method static string alias(string $class)
 * @method static string encode(Model $model)
 * @method static string encodeFromId(string $class, int | string $id)
 * @method static Model decodeToModel(string $uid, ?string $class = null)
 * @method static DecodedUid decode(string $uid)
 * @method static DecodedUid decodeOrFail(string $uid, string $class)
 * @method static bool isValid(mixed $uid, ?string $model = null)
 * @method static null | string getModel(string | DecodedUid $prefix)
 * @method static null | string hasModel(string | DecodedUid $prefix)
 * @method static \ShabuShabu\Uid\Service\Uid withTrashed()
 */
class Uid extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'uid';
    }
}
