<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Tests\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\Uid\Concerns\HasUid;
use ShabuShabu\Uid\Tests\Database\Factories\ContactFactory;

/**
 * @property-read string $uid
 */
class Contact extends Model
{
    use HasFactory;
    use HasUid;

    protected $guarded = [];

    protected static function newFactory(): ContactFactory
    {
        return new ContactFactory;
    }
}
