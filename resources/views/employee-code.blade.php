<!DOCTYPE html>
<html lang="en">
<head>
    <title>Enter Employee Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="text-center bg-white p-10 rounded-xl shadow-lg max-w-lg">
        <h1 class="text-3xl font-bold text-blue-600">Employee Code Verification</h1>
        <form action="{{ route('employee.verify') }}" method="POST">
            @csrf
            <input type="text" name="employee_code" placeholder="Enter Employee Code" required
                class="mt-4 p-2 border rounded-lg w-full"/>
            <button type="submit" class="mt-4 bg-green-500 text-white py-2 px-6 rounded-lg shadow-md hover:bg-green-700">
                Verify & Proceed
            </button>
        </form>
        <a href="{{ url('/logout') }}" class="mt-6 inline-block text-red-500">Logout</a>
    </div>
</body>
</html>
