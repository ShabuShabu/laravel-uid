<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Service;

use Illuminate\Container\Attributes\Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use ShabuShabu\Uid\Contracts\Identifiable;
use Sqids\Sqids;
use Throwable;

final class Uid
{
    protected bool $trashed = false;

    public function __construct(
        #[Config('uid')]
        protected array $config
    ) {}

    public static function make(): Uid
    {
        return app(self::class);
    }

    public function alias(string $class): string
    {
        if (
            blank($prefixes = $this->config['prefixes']) ||
            ! in_array($class, $prefixes, true) ||
            ($alias = array_search($class, $prefixes, true)) === false
        ) {
            throw new RuntimeException(
                "The model prefix for `$class` has not been registered yet"
            );
        }

        return $alias;
    }

    public function encode(Identifiable $model): string
    {
        return $this->makeUid($this->alias($model::class), $model->getKey(), $model);
    }

    public function encodeFromId(string $class, int | string $id): string
    {
        return $this->makeUid($this->alias($class), $id, new $class);
    }

    protected function service(Identifiable $model): Sqids
    {
        return Encoder::make($model->uidAlphabet());
    }

    protected function makeUid(string $prefix, int | string $id, Identifiable $model): string
    {
        return $prefix . $this->config['separator'] . $this->service($model)->encode([(int) $id]);
    }

    public function decodeToModel(string $uid, ?string $class = null): Model
    {
        $decoded = $this->decode($uid);

        if (is_string($class) && (! class_exists($class) || $decoded->class !== $class)) {
            throw new RuntimeException(
                "The returned model would not match `$class`"
            );
        }

        return $decoded->class::query()->when(
            $this->trashed,
            fn (Builder $builder) => $builder->withTrashed() // @phpstan-ignore method.notFound
        )->findOrFail($decoded->modelId);
    }

    public function decode(string $uid): DecodedUid
    {
        [$prefix, $hashId] = explode($this->config['separator'], $uid);

        if (! $model = $this->getModel($prefix)) {
            throw new RuntimeException(
                "No model class defined for prefix `$prefix`"
            );
        }

        if (blank($result = $this->service(new $model)->decode($hashId))) {
            throw new RuntimeException(
                "Unable to decode uid `$hashId`"
            );
        }

        return new DecodedUid(
            prefix: rtrim($prefix, $this->config['separator']),
            hashId: $hashId,
            modelId: $result[0],
            class: $model,
        );
    }

    public function decodeOrFail(string $uid, string $class): DecodedUid
    {
        $decoded = $this->decode($uid);

        if ($decoded->prefix !== $this->alias($class)) {
            throw new RuntimeException(
                "The returned model would not match `$class`"
            );
        }

        return $decoded;
    }

    public function isValid(mixed $uid, ?string $class = null): bool
    {
        if (! is_string($uid)) {
            return false;
        }

        try {
            $decoded = $this->decode($uid);

            return match (true) {
                $class === null => $this->hasModel($decoded->prefix),
                is_string($class) => $this->alias($class) === $decoded->prefix,
            };
        } catch (Throwable) {
            return false;
        }
    }

    protected function normalizeDecoded(string | DecodedUid $prefix): string
    {
        return $prefix instanceof DecodedUid
            ? $prefix->prefix
            : $prefix;
    }

    public function getModel(string | DecodedUid $prefix): ?string
    {
        $key = $this->normalizeDecoded($prefix);

        return $this->config['prefixes'][$key] ?? null;
    }

    public function hasModel(string | DecodedUid $prefix): bool
    {
        $key = $this->normalizeDecoded($prefix);

        return array_key_exists($key, $this->config['prefixes']);
    }

    public function withTrashed(): Uid
    {
        $this->trashed = true;

        return $this;
    }
}
