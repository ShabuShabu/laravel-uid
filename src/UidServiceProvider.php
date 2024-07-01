<?php

declare(strict_types=1);

namespace ShabuShabu\Uid;

use Illuminate\Database\Eloquent\Relations\Relation;
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
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('ShabuShabu/laravel-uid');
            });
    }

    public function packageRegistered(): void
    {
        $this->app->scoped(
            Sqids::class,
            fn () => new Sqids(
                alphabet: config('uid.alphabet'),
                minLength: config('uid.length'),
                blocklist: config('uid.blocklist'),
            )
        );
    }

    public function packageBooted(): void
    {
        Relation::enforceMorphMap(config('uid.prefixes'));
    }
}
