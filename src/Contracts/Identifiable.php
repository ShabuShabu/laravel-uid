<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Contracts;

interface Identifiable
{
    /* @phpstan-ignore typeCoverage.returnTypeCoverage */
    public function getKey();

    /* @phpstan-ignore typeCoverage.returnTypeCoverage */
    public function getMorphClass();

    public function uidAlphabet(): string;
}
