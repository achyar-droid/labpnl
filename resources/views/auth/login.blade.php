<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="min-h-screen flex items-center justify-center bg-slate-100">

<div class="w-[800px] h-[450px] bg-white rounded-2xl shadow-xl flex overflow-hidden">

    <!-- LEFT : LOGIN FORM -->
    <div class="w-1/2 p-10 flex flex-col justify-center">
        <h1 class="text-3xl font-bold mb-2">Sign In</h1>
        <p class="text-sm text-gray-500 mb-6">
            use your username password?
        </p>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <input
                type="text"
                name="username"
                placeholder="Username"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >

            <a href="#" class="text-sm text-gray-400 hover:underline block">
                Forget Your Password?
            </a>

            <button
                type="submit"
                class="mt-4 w-32 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
            >
                SIGN IN
            </button>
        </form>
    </div>

    <!-- RIGHT : BLUE PANEL -->
    <div class="w-1/2 bg-blue-600 text-white flex flex-col justify-center items-center text-center px-8 rounded-l-[120px]">
        <h2 class="text-3xl font-bold mb-4">Hello, Friend!</h2>
        <p class="text-sm mb-6">
            Register with your personal details to use all of site features
        </p>

        <a
            href="{{ route('register') }}"
            class="border border-white px-8 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition"
        >
            SIGN UP
        </a>
    </div>

</div>

</body>
</html>
