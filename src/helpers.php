<?php

declare(strict_types=1);

use ShabuShabu\Uid\Service\Uid;

if (! function_exists('uid')) {
    function uid(): Uid
    {
        return Uid::make();
    }
}

/**
 * @template TModel
 *
 * @param  class-string<TModel>  $model
 * @return TModel|null
 */
if (! function_exists('resolve_model')) {
    function resolve_model(string $model, mixed $id)
    {
        if ($id instanceof $model) {
            return $id;
        }

        if (is_int($id) && class_exists($model)) {
            return call_user_func([$model, 'find'], $id);
        }

        if (is_string($id) && (class_exists($model) || interface_exists($model))) {
            $record = Uid::make()->decodeToModel($id);

            return $record instanceof $model ? $record : null;
        }

        return null;
    }
}

if (! function_exists('split_uid')) {
    /**
     * Split a given uid into its prefix and hash id
     *
     * @throws Throwable
     */
    function split_uid(string $uid, ?string $class = null): array
    {
        $service = Uid::make();

        $decoded = $class
            ? $service->decodeOrFail($uid, $class)
            : $service->decode($uid);

        return [$decoded->prefix => $decoded->hashId];
    }
}
