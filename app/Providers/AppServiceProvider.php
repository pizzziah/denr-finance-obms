<?php

namespace App\Providers;

use App\Models\Admin\AdminUser;
use Illuminate\Support\ServiceProvider;

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
        // Forcefully rebind the Eloquent user provider model at runtime
        config(['auth.providers.users.model' => AdminUser::class]);
    }
}
