<?php

namespace App\Providers;

use App\Repositories\GuaranteeRepository;
use App\Repositories\Interfaces\GuaranteeRepositoryInterface;
use App\Repositories\Interfaces\UploadedFileRepositoryInterface;
use App\Repositories\UploadedFileRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repositories to their interfaces
        $this->app->bind(GuaranteeRepositoryInterface::class, GuaranteeRepository::class);
        $this->app->bind(UploadedFileRepositoryInterface::class, UploadedFileRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}