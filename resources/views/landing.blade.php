<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GPFetcher</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center bg-white p-10 rounded-xl shadow-lg max-w-lg">
        <h1 class="text-4xl font-bold text-blue-600">Welcome to GPFetcher</h1>

        @auth
            <!-- If the user is logged in -->
            <p class="mt-4 text-gray-600">
                Hello, <span class="font-semibold text-blue-500">{{ Auth::user()->name }}</span>! Welcome back to your Google Photos manager.
            </p>
            <a href="{{ url('/logout') }}"
                class="mt-6 inline-block bg-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-red-700 transition duration-300">
                Logout
            </a>
        @else
            <!-- If the user is not logged in -->
            <p class="mt-4 text-gray-600">
                Your secure Google Photos manager. Sign in to access your Google Photos.
            </p>
            <a href="{{ url('/auth/google') }}"
                class="mt-6 inline-block bg-blue-500 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                Login with Google
            </a>
        @endauth
    </div>
</body>
</html>
