<?php

declare(strict_types=1);

use Sqids\Sqids;

return [
    /*
    |--------------------------------------------------------------------------
    | Default alphabet
    |--------------------------------------------------------------------------
    |
    | It is recommended to set a custom alphabet here using the `uid:alphabet`
    | command.
    |
    */

    'alphabet' => env('UID_ALPHABET', Sqids::DEFAULT_ALPHABET),

    /*
    |--------------------------------------------------------------------------
    | Squids length
    |--------------------------------------------------------------------------
    |
    | Please note that this is a minimum length and generated UIDs might be
    | longer. The full minimum length of the generated UIDs will be the length
    | of the prefix plus the length of the separator plus whatever you set here.
    |
    */

    'length' => (int) env('UID_LENGTH', 10),

    /*
    |--------------------------------------------------------------------------
    | Separator
    |--------------------------------------------------------------------------
    |
    | While you can use different characters here, please note that they need
    | to be URL-safe. The underscore was chosen as it allows UIDs to be
    | copied in full.
    |
    */

    'separator' => env('UID_SEPARATOR', '_'),

    /*
    |--------------------------------------------------------------------------
    | Blocklist
    |--------------------------------------------------------------------------
    |
    | A list of words you do not want to show up in your UIDs.
    |
    */

    'blocklist' => Sqids::DEFAULT_BLOCKLIST,

    /*
    |--------------------------------------------------------------------------
    | Use prefixes as morph map
    |--------------------------------------------------------------------------
    |
    | Instead of declaring your morph map separately, you can re-use your
    | prefixes as the morph map.
    |
    | Type can be either `regular` or `enforced`
    |
    */

    'morph_map' => [
        'enabled' => (bool) env('UID_MORPH_MAP_ENABLED', false),
        'type' => env('UID_MORPH_MAP_TYPE', 'enforced'),
    ],

    /*
    |--------------------------------------------------------------------------
    | UID prefixes
    |--------------------------------------------------------------------------
    |
    | You should specify all your models here (incl. pivot models) eg:
    | ['usr' => \App\Models\User::class]
    |
    */

    'prefixes' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom model alphabets
    |--------------------------------------------------------------------------
    |
    | Specify any model alphabets here, prefix => alphabet.
    | Models without a custom alphabet will use the default one above.
    |
    */

    'alphabets' => [],
];
