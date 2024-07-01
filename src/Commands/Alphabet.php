<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Commands;

use Illuminate\Console\Command;
use Sqids\Sqids;

class Alphabet extends Command
{
    protected $signature = 'uid:alphabet';

    protected $description = 'Creates a custom sqids alphabet';

    public function __invoke(): int
    {
        $alphabet = str_shuffle(Sqids::DEFAULT_ALPHABET);

        $this->components->info("Add this to your .env file: <options=bold>UID_ALPHABET=$alphabet</>");

        return self::SUCCESS;
    }
}
