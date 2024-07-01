<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Service;

readonly class DecodedUid
{
    public function __construct(
        public string $prefix,
        public string $hashId,
        public int $modelId,
    ) {}
}
