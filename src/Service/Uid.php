<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Service;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use RuntimeException;
use Sqids\Sqids;
use Throwable;

final class Uid
{
    protected bool $trashed = false;

    protected function __construct(
        protected Sqids $service,
        protected string $separator
    ) {}

    public static function make(): Uid
    {
        return new self(
            app(Sqids::class),
            config('uid.separator')
        );
    }

    public static function alias(string $class): string
    {
        if (
            blank($prefixes = config('uid.prefixes')) ||
            ! in_array($class, $prefixes, true) ||
            ($alias = array_search($class, $prefixes, true)) === false
        ) {
            throw new RuntimeException(
                "The model prefix for `$class` has not been registered yet"
            );
        }

        return $alias;
    }

    public function encode(Model $model): string
    {
        $this->assertValidPrefix(
            $prefix = $model->getMorphClass(),
            get_class($model)
        );

        return $this->makeUid($prefix, $model->getKey());
    }

    protected function assertValidPrefix(string $prefix, string $class): void
    {
        if (! array_key_exists($prefix, config('uid.prefixes'))) {
            throw new RuntimeException(
                "The model prefix for `$class` has not been registered yet. Current prefix: `$prefix`"
            );
        }
    }

    protected function makeUid(string $prefix, int | string $id): string
    {
        return $prefix . $this->separator . $this->service->encode([(int) $id]);
    }

    public function encodeFromId(string $class, int | string $id): string
    {
        $this->assertValidPrefix(
            $prefix = (new $class())->getMorphClass(),
            $class
        );

        return $this->makeUid($prefix, $id);
    }

    public function decodeToModel(string $uid): Model
    {
        $decoded = $this->decode($uid);

        if (! $class = Relation::getMorphedModel($decoded->prefix)) {
            throw new ModelNotFoundException();
        }

        /** @var Builder $builder */
        $builder = call_user_func([$class, 'query']);

        return $builder
            ->when(
                $this->trashed,
                fn (Builder $query) => method_exists($query, 'withTrashed')
                    ? $query->withTrashed()
                    : $query
            )
            ->findOrFail($decoded->modelId);
    }

    public function decode(string $uid): DecodedUid
    {
        [$prefix, $hashId] = explode($this->separator, $uid);

        if (blank($result = $this->service->decode($hashId))) {
            throw new RuntimeException(
                "Unable to decode uid [$hashId]"
            );
        }

        return new DecodedUid(
            prefix: rtrim($prefix, $this->separator),
            hashId: $hashId,
            modelId: $result[0]
        );
    }

    public function isValid(mixed $uid, ?string $model = null): bool
    {
        if (! is_string($uid)) {
            return false;
        }

        try {
            $decoded = self::make()->decode($uid);

            return match (true) {
                $model === null => array_key_exists($decoded->prefix, config('uid.prefixes')),
                is_string($model) => self::alias($model) === $decoded->prefix,
            };
        } catch (Throwable) {
            return false;
        }
    }

    public function withTrashed(): Uid
    {
        $this->trashed = true;

        return $this;
    }
}
