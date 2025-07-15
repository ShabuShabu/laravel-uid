<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Service;

use Sqids\Sqids;

class Encoder
{
    public static function make(?string $alphabet = null): Sqids
    {
        return new Sqids(
            alphabet: $alphabet ?? config('uid.alphabet'),
            minLength: config('uid.length'),
            blocklist: config('uid.blocklist'),
        );
    }
}
