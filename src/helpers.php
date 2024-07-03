<?php

declare(strict_types=1);

namespace ShabuShabu\Uid;

use ShabuShabu\Uid\Service\Uid;

/**
 * @template TModel
 *
 * @param  class-string<TModel>  $model
 * @return TModel|null
 */
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
