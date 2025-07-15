<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Service;

readonly class DecodedUid
{
    /**
     * @template T
     *
     * @param  class-string<T>  $class
     */
    public function __construct(
        public string $prefix,
        public string $hashId,
        public int $modelId,
        public string $class,
    ) {}
}
