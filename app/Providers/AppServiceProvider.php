<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $service = [
        'App\Services\Interfaces\AccountServiceInterface' => 'App\Services\AccountService',
    
      ];
  

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach ($this->service as $interface => $class) {
            $this->app->bind($interface, $class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
