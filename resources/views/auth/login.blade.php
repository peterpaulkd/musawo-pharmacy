<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css') <!-- if you use Tailwind via Vite -->
</head>

<body class="bg-gradient-to-br from-blue-500 to-indigo-600 text-gray-800 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl p-10 max-w-md w-full">
        <h2 class="text-3xl font-bold text-center text-yellow-600 mb-6 animate-bounce">
            Welcome Back!
        </h2>
        <p class="text-center text-gray-600 mb-8">Please select your role and enter your password</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Role Selector -->
            <div class="flex flex-col">
                <label for="role" class="mb-2 font-medium text-gray-700">Select Role</label>
                <select name="role" id="role" required
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 outline-none transition duration-300">
                    <option value="">--Choose Role--</option>
                    <option value="admin">Admin</option>
                    <option value="nurse">Nurse</option>
                </select>
            </div>

            <!-- Password -->
            <div class="flex flex-col">
                <label for="password" class="mb-2 font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-400 focus:border-yellow-400 outline-none transition duration-300">
            </div>

            <!-- Login Button -->
            <div>
                <button type="submit" class="btn-yellow w-full text-lg font-semibold">
                    Login
                </button>
            </div>
        </form>
    </div>

    <!-- Yellow Button Style -->
    <style>
        .btn-yellow {
            background: linear-gradient(90deg, #FFD700, #FFC107);
            color: #000;
            font-weight: 600;
            border: 2px solid #FFA500;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
            transition: all 0.3s ease;
        }
        .btn-yellow:hover {
            background: linear-gradient(90deg,rgb(221, 167, 5),rgb(199, 171, 16));
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.6);
            transform: translateY(-2px);
        }
        .btn-yellow:active {
            transform: translateY(1px);
            box-shadow: 0 3px 10px rgba(255, 193, 7, 0.5);
        }
    </style>

</body>
</html>