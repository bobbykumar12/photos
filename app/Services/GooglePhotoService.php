<?php

namespace App\Services;

use App\Models\GooglePhoto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GooglePhotoService
{
    // public function syncUserPhotos($user)
    // {
    //     $accessToken = $this->getFreshAccessToken($user);

    //     if (!$accessToken) {
    //         Log::error("No valid access token for user {$user->id}");
    //         return;
    //     }

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $accessToken,
    //     ])->get('https://photoslibrary.googleapis.com/v1/mediaItems');

    //     $photos = $response->json();
    //         // dd($photos);
    //     if (!isset($photos['mediaItems'])) {
    //         Log::info("No photos found for user {$user->id}");
    //         return;
    //     }

    //     foreach ($photos['mediaItems'] as $photo) {
    //         $exists = GooglePhoto::where('photo_id', $photo['id'])
    //                              ->where('user_id', $user->id)
    //                              ->exists();

    //         if ($exists) continue;

    //         $photoContent = Http::get($photo['baseUrl']);
    //         $path = 'google-photos/' . $user->id . '/' . $photo['id'] . '.jpg';
    //         Storage::disk('public')->put($path, $photoContent->body());

    //         GooglePhoto::create([
    //             'user_id'   => $user->id,
    //             'photo_id'  => $photo['id'],
    //             'photo_url' => 'storage/' . $path,
    //             'user_name' => $user->google_user_name,
    //         ]);
    //     }
    // }

    // public function syncUserPhotos($user)
    // {
    //     $accessToken = $this->getFreshAccessToken($user);

    //     if (!$accessToken) {
    //         Log::error("No valid access token for user {$user->id}");
    //         return;
    //     }

    //     $nextPageToken = null;

    //     do {
    //         $url = 'https://photoslibrary.googleapis.com/v1/mediaItems?pageSize=100'; 
    //         if ($nextPageToken) {
    //             $url .= '&pageToken=' . $nextPageToken;
    //         }

    //         $response = Http::withHeaders([
    //             'Authorization' => 'Bearer ' . $accessToken,
    //         ])->get($url);

    //         $photos = $response->json();

    //         if (!isset($photos['mediaItems'])) {
    //             Log::info("No photos found for user {$user->id}");
    //             return;
    //         }

    //         foreach ($photos['mediaItems'] as $photo) {
    //             $exists = GooglePhoto::where('photo_id', $photo['id'])
    //                 ->where('user_id', $user->id)
    //                 ->exists();

    //             if ($exists) continue;

    //             // $photoContent = Http::get($photo['baseUrl']);
    //             $photoContent = Http::get($photo['baseUrl'] . '=d');

    //             $path = 'google-photos/' . $user->id . '/' . $photo['id'] . '.jpg';
    //             // Storage::disk('public')->put($path, $photoContent->body());

    //             Storage::disk('spaces')->put($path, $photoContent->body(), 'public');  

    //             $photoUrl = Storage::disk('spaces')->url($path); 

    //             GooglePhoto::updateOrCreate(
    //                 [
    //                     'user_id'  => $user->id,
    //                     'photo_id' => $photo['id'],
    //                 ],
    //                 [
    //                     'photo_url' => $photoUrl,
    //                     'user_name' => $user->google_user_name,
    //                 ]
    //             );
    //         }

    //         $nextPageToken = $photos['nextPageToken'] ?? null;

    //     } while ($nextPageToken);  
    // }


    public function syncUserPhotos($user)
{
    $accessToken = $this->getFreshAccessToken($user);

    if (!$accessToken) {
        Log::error("No valid access token for user {$user->id}");
        return;
    }

    $nextPageToken = null;

    do {
        $url = 'https://photoslibrary.googleapis.com/v1/mediaItems?pageSize=100';
        if ($nextPageToken) {
            $url .= '&pageToken=' . $nextPageToken;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get($url);

        $mediaItems = $response->json();

        if (!isset($mediaItems['mediaItems'])) {
            Log::info("No media found for user {$user->id}");
            return;
        }

        foreach ($mediaItems['mediaItems'] as $item) {
            $exists = GooglePhoto::where('photo_id', $item['id'])
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) continue;

            $path = 'google-photos/' . $user->id . '/' . $item['id'];

            if (strpos($item['mimeType'], 'image') !== false) {
                // Handling Photos
                $photoContent = Http::get($item['baseUrl'] . '=d');
                $filePath = $path . '.jpg';
                Storage::disk('spaces')->put($filePath, $photoContent->body(), 'public');

            } elseif (strpos($item['mimeType'], 'video') !== false) {
                // Handling Videos
                $filePath = $path . '.mp4';
                $videoUrl = $item['baseUrl'] . '=dv'; // Fetching video content

                // Since videos can be large, we store the URL instead of downloading
                Storage::disk('spaces')->put($filePath, file_get_contents($videoUrl), 'public');
            } else {
                continue;
            }

            $mediaUrl = Storage::disk('spaces')->url($filePath);

            GooglePhoto::updateOrCreate(
                [
                    'user_id'  => $user->id,
                    'photo_id' => $item['id'],
                ],
                [
                    'photo_url' => $mediaUrl,
                    'user_name' => $user->google_user_name,
                    'mime_type' => $item['mimeType'],
                ]
            );
        }

        $nextPageToken = $mediaItems['nextPageToken'] ?? null;

    } while ($nextPageToken);
}



    private function getFreshAccessToken($user)
    {
        if (!$user->google_refresh_token) {
            Log::error("User {$user->id} has no refresh token.");
            return null;
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'refresh_token' => $user->google_refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        $data = $response->json();

        if (isset($data['access_token'])) {
            $user->update(['google_access_token' => $data['access_token']]);
            Log::info("User {$user->id} access token refreshed.");
            return $data['access_token'];
        }

        Log::error("Failed to refresh token for user {$user->id}", $data);
        return null;
    }

}
