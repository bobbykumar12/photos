<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Octane\Facades\Octane;
use App\Models\Customer;
use App\Services\GooglePhotoService;
use Illuminate\Support\Facades\Log;
use App\Models\DummyLog;

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

 

     public function boot()
     {
         Octane::tick('fetch_google_photos', function () {
             $users = Customer::whereNotNull('google_access_token')->get();
             $service = new GooglePhotoService();
     
             foreach ($users as $user) {
                 $service->syncUserPhotos($user);
             }
            
             Log::info('Google Photos Sync via Octane Ticker at ' . now());
         })->seconds(300)->immediate();  
     }

     
}
