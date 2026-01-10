<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-slate-100">

<div class="w-[800px] h-[450px] bg-white rounded-2xl shadow-xl flex overflow-hidden">

    <!-- LEFT : BLUE PANEL -->
    <div class="w-1/2 bg-blue-600 text-white flex flex-col justify-center items-center text-center px-8 rounded-r-[120px]">
        <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
        <p class="text-sm mb-6">
            To keep connected with us please login with your personal info
        </p>

        <a
            href="{{ route('login') }}"
            class="border border-white px-8 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition"
        >
            SIGN IN
        </a>
    </div>

    <!-- RIGHT : REGISTER FORM -->
    <div class="w-1/2 p-10 flex flex-col justify-center">
        <h1 class="text-3xl font-bold mb-2">Create Account</h1>
        <p class="text-sm text-gray-500 mb-6">
            use your personal details to create account
        </p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <input
                type="text"
                name="name"
                placeholder="Full Name"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >

            <input
                type="text"
                name="username"
                placeholder="Username"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >

            <!-- PILIH LAB -->
            <select
                name="lab"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >
                <option value="" disabled selected>Pilih Laboratorium</option>
                <option value="iot">Laboratorium IoT</option>
                <option value="jaringan">Laboratorium Jaringan</option>
                <option value="cloud">Laboratorium Cloud Computing</option>
            </select>

            <input
                type="password"
                name="password"
                placeholder="Password"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >

            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirm Password"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required
            >

            <button
                type="submit"
                class="mt-4 w-40 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
            >
                SIGN UP
            </button>
        </form>
    </div>

</div>

</body>
</html>
