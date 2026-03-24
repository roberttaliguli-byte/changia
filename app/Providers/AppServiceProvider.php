<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $allowed = ['sw', 'en'];

        $lang = session('settings.language', 'sw');

        if (!in_array($lang, $allowed)) {
            $lang = 'sw';
        }

        App::setLocale($lang);
    }
}