<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Shadra System</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white min-h-screen flex items-center justify-center">

    <div class="text-center space-y-6">
        <h1 class="text-5xl font-bold">Welcome to <span class="text-yellow-300">Shadra's Pharmacy</span></h1>
        <p class="text-lg">Your Health is our Priority.</p>

        <div class="flex justify-center space-x-4">
            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
               Login
            </a>
        </div>
    </div>

</body>
</html>