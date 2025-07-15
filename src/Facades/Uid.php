<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use ShabuShabu\Uid\Service\DecodedUid;

/**
 * @method string alias(string $class)
 * @method string encode(Model $model)
 * @method string encodeFromId(string $class, int | string $id)
 * @method Model decodeToModel(string $uid, ?string $class = null)
 * @method DecodedUid decode(string $uid)
 * @method DecodedUid decodeOrFail(string $uid, string $class)
 * @method bool isValid(mixed $uid, ?string $model = null)
 * @method null | string getModel(string | DecodedUid $prefix)
 * @method null | string hasModel(string | DecodedUid $prefix)
 * @method \ShabuShabu\Uid\Service\Uid withTrashed()
 */
class Uid extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'uid';
    }
}
