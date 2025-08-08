<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\StudentStatistic; // <-- Add this
use App\Observers\StudentStatisticObserver; // <-- Add this

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::statement("SET time_zone = '+03:00'");
        StudentStatistic::observe(StudentStatisticObserver::class);
    }
}
