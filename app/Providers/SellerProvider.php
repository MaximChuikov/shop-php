<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SellerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('seller', function () {
            return "<?php if (auth()->check() && auth()->user()->isSeller) : ?>";
        });

        Blade::directive('endseller', function () {
            return "<?php endif; ?>";
        });
    }
}
