<?php

namespace App\Providers;

use App\Repositories\MovieRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\DirectorRepository;
use App\Repositories\MovieRepositoryInterface;
use App\Repositories\DirectorRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->singleton(DirectorRepositoryInterface::class, DirectorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
