<?php

namespace App\Providers;

use App\Contracts\NotifikasiInterface;
use App\Models\Pendaftaran;
use App\Notifications\EmailNotifikasi;
use App\Observers\PendaftaranObserver;
use App\Services\PencarianService;
use App\Services\RankingService;
use App\Services\ResourceMonitor;
use App\Services\SkorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SkorService::class, fn () => new SkorService);
        $this->app->singleton(RankingService::class, fn () => new RankingService);
        $this->app->singleton(PencarianService::class, fn () => new PencarianService);
        $this->app->singleton(ResourceMonitor::class, fn () => new ResourceMonitor);
        $this->app->bind(NotifikasiInterface::class, EmailNotifikasi::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pendaftaran::observe(PendaftaranObserver::class);
    }
}
