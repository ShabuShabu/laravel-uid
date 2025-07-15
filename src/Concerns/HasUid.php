<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\Uid\Facades\Uid;

/**
 * @mixin Model
 */
trait HasUid
{
    public function initializeHasUid(): void
    {
        $appends = $this->getAppends();
        $appends[] = 'uid';

        $this->setAppends($appends);
    }

    protected function uid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->exists
                ? Uid::encode($this)
                : null,
        );
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($field !== 'uid') {
            return parent::resolveRouteBindingQuery($query, $value, $field);
        }

        return $query->where('id', Uid::decode($value)->modelId);
    }

    public function getMorphClass(): string
    {
        if (! config('uid.morph_map.enabled')) {
            return parent::getMorphClass();
        }

        $prefixes = config('uid.prefixes');

        if (filled($prefixes) && in_array(static::class, $prefixes, true)) {
            return array_search(static::class, $prefixes, true);
        }

        return parent::getMorphClass();
    }

    public function uidAlphabet(): string
    {
        $alias = Uid::alias(static::class);

        $alphabet = config("uid.alphabets.$alias");

        return filled($alphabet) ? $alphabet : config('uid.alphabet');
    }
}
