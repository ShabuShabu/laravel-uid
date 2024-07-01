<?php

declare(strict_types=1);

use Sqids\Sqids;

return [
    'alphabet' => env('UIDS_ALPHABET', Sqids::DEFAULT_ALPHABET),
    'length' => (int) env('UIDS_LENGTH', 10),
    'separator' => env('UIDS_SEPARATOR', '_'),
    'blocklist' => Sqids::DEFAULT_BLOCKLIST,
    'prefixes' => [],
];
