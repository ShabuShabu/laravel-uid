<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\File;
use ShabuShabu\Uid\Contracts\Identifiable;
use Sqids\Sqids;

class Check extends Command
{
    protected $signature = 'uid:check {action? : The action to take, either models or alphabets}
                                      {--directory= : Which directory to check for models}
                                      {--namespace= : Which base namespace to use}';

    protected $description = 'Various tools to check if uid prefixes, models and alphabets are in sync';

    public function __invoke(#[Config('uid')] array $config): int
    {
        $context = $this->argument('action') ?? $this->components->choice(
            'What do you want to check?',
            ['models', 'alphabets'],
            'models'
        );

        return match ($context) {
            'models' => $this->models($config),
            'alphabets' => $this->alphabets($config),
            default => static::FAILURE,
        };
    }

    protected function models(array $config): int
    {
        $directory = $this->option('directory') ?? $this->components->ask(
            'Where are your models located?',
            app_path('Models'),
        );

        $namespace = $this->option('namespace') ?? $this->components->ask(
            'What is the base namespace?',
            'App\\Models\\',
        );

        $prefixModels = array_values($config['prefixes']);

        $duplicates = array_diff_assoc($prefixModels, array_unique($prefixModels));

        if (($duplicateCount = count($duplicates)) > 0) {
            $this->components->warn('Duplicate prefixes found:');

            foreach ($duplicates as $model) {
                $this->line("  - <fg=#A69A9f>$model</>");
            }

            $this->newLine();
        }

        sort($prefixModels);

        $eloquentModels = [];
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $class = $namespace . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());

            if (class_exists($class) && in_array(Identifiable::class, class_implements($class), true)) {
                $eloquentModels[] = $class;
            }
        }

        sort($eloquentModels);

        $diff = array_values(array_diff($eloquentModels, $prefixModels));

        if (($count = count($diff)) > 0) {
            $models = str('model')->plural($count);
            $do = $count === 1 ? 'does' : 'do';

            $this->components->warn("<options=bold>$count</> $models $do not have a corresponding prefix:");

            foreach ($diff as $model) {
                $this->line("  - <fg=#A69A9f>$model</>");
            }

            $this->newLine();
        }

        if ($count <= 0 && $duplicateCount <= 0) {
            $this->components->info('Prefixes and models are in sync!');

            return static::SUCCESS;
        }

        return static::FAILURE;
    }

    protected function alphabets(array $config): int
    {
        $alphabetKeys = array_keys($config['alphabets']);
        $prefixKeys = array_keys($config['prefixes']);

        sort($alphabetKeys);
        sort($prefixKeys);

        $alphabetDiff = array_values(array_diff($prefixKeys, $alphabetKeys));
        $prefixDiff = array_values(array_diff($alphabetKeys, $prefixKeys));

        if (($alphabetCount = count($alphabetDiff)) > 0) {
            $prefixes = str('prefix')->plural($alphabetCount);
            $are = $alphabetCount === 1 ? 'is' : 'are';

            $this->components->warn("<options=bold>$alphabetCount</> $prefixes $are missing from the alphabets config:");

            foreach ($alphabetDiff as $key) {
                $alphabet = str_shuffle(Sqids::DEFAULT_ALPHABET);

                $this->line("'$key' => '$alphabet',");
            }

            $this->newLine();
        }

        if (($prefixCount = count($prefixDiff)) > 0) {
            $alphabets = str('alphabet')->plural($prefixCount);
            $do = $prefixCount === 1 ? 'does' : 'do';

            $this->components->warn("<options=bold>$prefixCount</> $alphabets $do not have a corresponding prefix:");

            foreach ($prefixDiff as $key) {
                $this->line("  - <fg=#A69A9f>$key</>");
            }

            $this->newLine();
        }

        if ($alphabetCount <= 0 && $prefixCount <= 0) {
            $this->components->info('Alphabets and prefixes are in sync!');

            return static::SUCCESS;
        }

        return static::FAILURE;
    }
}
