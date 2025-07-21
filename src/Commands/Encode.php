<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use ShabuShabu\Uid\Facades\Uid;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class Encode extends Command
{
    protected $signature = 'uid:encode {model? : The model to encode the id for}
                                       {id? : The id to encode}';

    protected $description = 'Encodes a model id to a uid';

    public function __invoke(): int
    {
        $model = $this->argument('model') ?? select(
            label: 'Select a model',
            options: array_values(Config::array('uid.prefixes')),
            scroll: 10,
        );

        $id = $this->argument('id') ?? text(
            label: 'Which ID should be encoded?',
            required: true,
            validate: fn (string $value) => is_numeric($value),
        );

        $uid = Uid::encodeFromId($model, $id);

        $this->components->info("Here is your uid: <options=bold>$uid</>");

        return static::SUCCESS;
    }
}
