<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Contracts;

interface Identifiable
{
    public function uidAlphabet(): string;
}
