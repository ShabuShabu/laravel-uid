<?php

declare(strict_types=1);

return [
    'alphabet' => env('UIDS_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
    'length' => (int) env('UIDS_LENGTH', 12),
    'separator' => env('UIDS_SEPARATOR', '_'),
    'blocklist' => \Sqids\Sqids::DEFAULT_BLOCKLIST,
    'prefixes' => [],
];
