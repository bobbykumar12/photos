<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Models\Customer;
use App\Services\GooglePhotoService;

use Illuminate\Support\Facades\Log;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule::call(function () {
//     $users = Customer::whereNotNull('google_access_token')->get();
//     $service = new GooglePhotoService();

//     foreach ($users as $user) {
//         $service->syncUserPhotos($user);
//     }
//     Log::info('Cron1 is running at ' . now());

// })->everyFiveMinutes(); 

