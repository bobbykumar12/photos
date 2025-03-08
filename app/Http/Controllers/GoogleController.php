<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\GooglePhoto;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer; // Import Customer model
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;  // Employee table jisme codes hai


class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // return Socialite::driver('google')
        //     ->scopes([
        //         'https://www.googleapis.com/auth/photoslibrary.readonly',
        //         'https://www.googleapis.com/auth/photoslibrary.appendonly',
        //         'https://www.googleapis.com/auth/photoslibrary.edit.appcreateddata'
        //     ])
        //     ->redirect();

        return Socialite::driver('google')
                ->scopes([
                    'https://www.googleapis.com/auth/photoslibrary.readonly',
                    'https://www.googleapis.com/auth/photoslibrary.appendonly',
                    'https://www.googleapis.com/auth/photoslibrary.edit.appcreateddata'
                ])
                ->with(['prompt' => 'consent', 'access_type' => 'offline'])
                ->redirect();

    }



    // public function handleGoogleCallback()
    // {
    //     // Get the user data from Google via Socialite
    //     $socialiteUser = Socialite::driver('google')->stateless()->user();
    //     // dd( $socialiteUser);
       
    //     // Find or create a customer based on the Google ID
    //     $customer = Customer::firstOrCreate(
    //         ['google_id' => $socialiteUser->id],  // Check using Google ID
    //         [
    //             'google_access_token' => $socialiteUser->token, // Store the Google token
    //             'google_refresh_token' => $socialiteUser->refreshToken, // Important
    //             'google_user_name' => $socialiteUser->name,  // Store the Google user name
    //             'email' => $socialiteUser->email, // Optionally store the email
    //         ]
    //     );
        
    //     // Log the customer in
    //     Auth::login($customer, true); // Log in the customer
        
    //     // Now the customer is authenticated, you can fetch and save their photos
    //     $this->fetchAndSavePhotos(); // Directly calling the method here

    //     return redirect('/'); // Redirect the customer to the landing page or dashboard
    // }

    public function handleGoogleCallback()
    {
        $socialiteUser = Socialite::driver('google')->stateless()->user();
    
        // Google se user info nikal li
        $customer = Customer::updateOrCreate(
            ['google_id' => $socialiteUser->id],
            [
                'google_access_token' => $socialiteUser->token,
                'google_refresh_token' => $socialiteUser->refreshToken,
                'google_user_name' => $socialiteUser->name,
                'email' => $socialiteUser->email,
            ]
        );
    
        Auth::guard('customer')->login($customer, true);

        return redirect()->route('employee.code');
    }

    

    public function showEmployeeCodeForm()
    {
        return view('employee-code');
    }

    public function verifyEmployeeCode(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string'
        ]);

        $employeeCode = $request->employee_code;

        // Check employee code in `employees` table
        $employee = Employee::where('employee_code', $employeeCode)->first();

        if (!$employee) {
            // Agar code invalid hai, logout aur alert
            Auth::guard('customer')->logout();

            return "<script>alert('Invalid Employee Code - Contact Admin'); window.location='/';</script>";
        }

        // Employee code sahi hai, user ko welcome page par bhejo
        $user = Auth::guard('customer')->user();
        $user->employee_code = $employeeCode;  // Store code in customer table (optional)
        $user->save();

        return redirect()->route('employee.welcome');
    }

    public function welcomePage()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect('/')->with('error', 'Session expired. Please login again.');
        }
    
        $user = Auth::guard('customer')->user();
        // dd(  $user);
    
        return view('welcome-employee', ['user' => $user]);
    }
    
    

    public function fetchAndSavePhotos()
    {
        // Get the authenticated user
        $user = Auth::guard('customer')->user();
        // dd($user);
        // Fetch the user's access token from the database
        $accessToken = $user->google_access_token;
    
        if (!$accessToken) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        // Fetch Photos from Google Photos API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://photoslibrary.googleapis.com/v1/mediaItems');
    
        $photos = $response->json();
    
        if (!isset($photos['mediaItems'])) {
            return response()->json(['error' => 'No photos found'], 404);
        }
    
        // Save Photos in Database, associate them with the authenticated user
        // foreach ($photos['mediaItems'] as $photo) {
        //     GooglePhoto::updateOrCreate(
        //         ['photo_id' => $photo['id'], 'user_id' => $user->id], // Store user_id along with photo_id
        //         [
        //             'photo_url' => $photo['baseUrl'],
        //             'user_name' => $user->google_user_name, // Save user's name along with the photo
        //         ]
        //     );
        // }
        foreach ($photos['mediaItems'] as $photo) {
            $photoUrl = $photo['baseUrl'];
        
            // 1. Image को download करो
            $photoContent = Http::get($photoUrl);
        
            // 2. Laravel storage में save करो
            $filename = $photo['id'] . '.jpg';
            $path = 'google-photos/' . $user->id . '/' . $photo['id'] . '.jpg';
            // Storage::disk('public')->put($path, $photoContent->body());

            Storage::disk('spaces')->put($path, $photoContent->body(), 'public');  

            $photoUrl = Storage::disk('spaces')->url($path); 
        
            // 3. Save photo path in MySQL
            GooglePhoto::updateOrCreate(
                [
                    'photo_id' => $photo['id'],
                    'user_id' => $user->id
                ],
                [
                    'photo_url' => $photoUrl, // ये public URL है जो directly काम करेगा
                    'user_name' => $user->google_user_name,
                ]
            );
        }
        
    
        return response()->json(['message' => 'Photos saved successfully']);
    }
  
    public function logout()
    {
        // Clear the Google Access Token and User Information from the session
        Session::forget('google_access_token');
        Session::forget('google_user_name');
        
        // Optionally, you can also log the user out of your application
        auth()->logout(); // If using Laravel's built-in auth system
        
        // Redirect the user to the homepage or login page
        return redirect('/');
    }

}
