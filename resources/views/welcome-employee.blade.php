<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center bg-white p-10 rounded-xl shadow-lg max-w-lg">
        <h1 class="text-3xl font-bold text-green-600">Welcome to Our Company</h1>
        <p class="mt-4 text-gray-600">
            Hello, <span class="font-semibold text-blue-500">{{ $user->google_user_name }}</span> 
            (Employee Code: <span class="font-semibold">{{ $user->employee_code }}</span>)
        </p>
        <a href="{{ url('/logout') }}"
            class="mt-6 inline-block bg-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:bg-red-700 transition duration-300">
            Logout
        </a>
    </div>
</body>
</html>
