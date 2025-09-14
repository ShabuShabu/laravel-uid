<?php

declare(strict_types=1);

namespace ShabuShabu\Uid;

use Illuminate\Database\Eloquent\Relations\Relation;
use ShabuShabu\Uid\Service\Encoder;
use ShabuShabu\Uid\Service\Uid;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sqids\Sqids;

class UidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-uid')
            ->hasConfigFile()
            ->hasCommands(
                Commands\Alphabet::class,
                Commands\Check::class,
                Commands\Encode::class,
                Commands\Info::class,
            )
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('ShabuShabu/laravel-uid');
            });
    }

    public function packageBooted(): void
    {
        $config = $this->app['config']['uid'];

        if (! $config['morph_map']['enabled']) {
            return;
        }

        if ($config['morph_map']['type'] === 'enforced') {
            Relation::enforceMorphMap($config['prefixes']);
        } else {
            Relation::morphMap($config['prefixes']);
        }
    }

    public function packageRegistered(): void
    {
        $this->app->scoped(Sqids::class, fn () => Encoder::make());
        $this->app->scoped('uid', Uid::class);
    }
}
