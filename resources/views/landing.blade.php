<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to SmS Works</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-500 to-purple-600">

    <div class="bg-white/80 backdrop-blur-md p-10 rounded-2xl shadow-xl max-w-lg w-full text-center border border-gray-200">
        
        <!-- Header with Logo -->
        <div class="flex flex-col items-center">
            <img src="logo.png" alt="SmS Works Logo" class="w-24 h-24 rounded-full shadow-md">
            <h1 class="text-4xl font-extrabold text-blue-700 drop-shadow-lg mt-2">SMS Works</h1>
            <p class="text-gray-700 mt-1 text-lg">Your trusted employee management system</p>
        </div>

        @auth
            <div class="mt-6">
                <p class="text-gray-600 text-lg">
                    Hello, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>! Welcome back to your dashboard.
                </p>
                <a href="{{ url('/logout') }}"
                    class="mt-6 inline-block bg-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-red-700 transition duration-300 ease-in-out transform hover:scale-105">
                    Logout
                </a>
            </div>
        @else
            <p class="mt-6 text-gray-600 text-lg">
                Manual Application UI/UX Testing. Sign in with Employee Code.
            </p>
            <a href="{{ url('/auth/google') }}"
                class="mt-6 inline-block bg-blue-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">
                Login with Google
            </a>
        @endauth
    </div>

</body>
</html>
