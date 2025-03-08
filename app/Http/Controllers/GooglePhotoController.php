<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GooglePhoto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;  // Make sure to import Inertia

class GooglePhotoController extends Controller
{
    public function fetchAndSavePhotos()
    {
        $accessToken = Session::get('google_access_token');

        if (!$accessToken) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // ✅ Fetch Photos from Google API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://photoslibrary.googleapis.com/v1/mediaItems');

        $photos = $response->json();

        if (!isset($photos['mediaItems'])) {
            return response()->json(['error' => 'No photos found'], 404);
        }

        // ✅ Save Photos in Database
        foreach ($photos['mediaItems'] as $photo) {
            GooglePhoto::updateOrCreate(
                ['photo_id' => $photo['id']],
                ['photo_url' => $photo['baseUrl']]
            );
        }

        return response()->json(['message' => 'Photos saved successfully']);
    }

    public function showBannerPhotos()
    {
        // Fetching distinct users with their photos
        $users = GooglePhoto::select('user_id', 'user_name')
                            ->distinct()
                            ->get();
    
        return Inertia::render('Banner', [
            'users' => $users // Pass only unique users
        ]);
    }
    
    
    public function showPhotos($userId)
    {
        // Fetch all photos for a specific user with pagination
        $photos = GooglePhoto::where('user_id', $userId)->paginate(100);
        
        return Inertia::render('UserPhotos', [
            'photos' => $photos
        ]);
    }
    
    
    
}
