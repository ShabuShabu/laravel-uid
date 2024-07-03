<?php

declare(strict_types=1);

namespace ShabuShabu\Uid\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use RuntimeException;
use ShabuShabu\Uid\Service\Uid;

class Info extends Command
{
    protected $signature = 'uid:info {uid? : The uid for the model}';

    protected $description = 'Shows information about a model based on its uid';

    public function __invoke(): int
    {
        $uid = $this->argument('uid') ?? $this->components->ask(
            'What uid do you want to get info about?',
        );

        try {
            $model = Uid::make()->decodeToModel($uid);
        } catch (ModelNotFoundException) {
            $this->components->error("A model with the uid of <options=bold>$uid</> wasn't found.");

            return static::FAILURE;
        } catch (RuntimeException $e) {
            $this->components->error($e->getMessage());

            return static::FAILURE;
        }

        $this->call('model:show', ['model' => get_class($model)]);

        $this->components->twoColumnDetail('<fg=green;options=bold>Details</>');

        foreach ($model->getAttributes() as $attribute => $value) {
            $this->components->twoColumnDetail($attribute, $this->value($value));
        }

        foreach ($model->getAppends() as $attribute) {
            $this->components->twoColumnDetail($attribute, $this->value($model->$attribute));
        }

        return static::SUCCESS;
    }

    protected function value(mixed $value): mixed
    {
        return is_string($value)
            ? Str::limit($value, 200)
            : $value;
    }
}
