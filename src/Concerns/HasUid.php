<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use ShabuShabu\Uid\Service\Uid;

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
                ? Uid::make()->encode($this)
                : null,
        );
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($field !== 'uid') {
            return parent::resolveRouteBindingQuery($query, $value, $field);
        }

        return $query->where('id', Uid::make()->decode($value)->modelId);
    }

    public function getMorphClass(): string
    {
        $prefixes = config('uid.prefixes');

        if (filled($prefixes) && in_array(static::class, $prefixes, true)) {
            return array_search(static::class, $prefixes, true);
        }

        return parent::getMorphClass();
    }
}
