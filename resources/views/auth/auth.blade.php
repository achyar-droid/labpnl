<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auth</title>

    <!-- CSS dari Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js CDN (FIX UTAMA) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-slate-100">

<div 
    x-data="{ open: false }"
    class="relative w-[800px] h-[450px] bg-white rounded-2xl shadow-xl overflow-hidden"
>

    @if(session('success'))
        <div class="absolute top-4 left-4 right-4 z-50 p-3 rounded bg-green-100 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="absolute top-4 left-4 right-4 z-50 bg-red-100 text-red-700 p-3 rounded">
            <ul class="text-sm list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORMS -->
    <div class="absolute inset-0 flex">

        <!-- LOGIN -->
        <div 
            class="w-1/2 p-10 flex flex-col justify-center transition-all duration-700 ease-in-out"
            :class="open 
                ? 'opacity-0 blur-sm scale-95 pointer-events-none' 
                : 'opacity-100 blur-0 scale-100'"
        >
            <h1 class="text-3xl font-bold mb-2">Sign In</h1>
            <p class="text-sm text-gray-500 mb-6">
                use your username & password
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

                <!-- PASSWORD -->
                <div x-data="{ show: false }" class="relative">
                    <input 
                        :type="show ? 'text' : 'password'"
                        name="password"
                        placeholder="Password"
                        class="w-full rounded-lg border-gray-300 pr-16 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                    <button 
                        type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 hover:text-blue-600"
                        x-text="show ? 'HIDE' : 'SHOW'"
                    ></button>
                </div>

                <button class="w-40 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    SIGN IN
                </button>
            </form>
        </div>

        <!-- REGISTER -->
        <div 
            class="w-1/2 p-10 flex flex-col justify-center transition-all duration-700 ease-in-out"
            :class="open 
                ? 'opacity-100 blur-0 scale-100' 
                : 'opacity-0 blur-sm scale-95 pointer-events-none'"
        >
            <h1 class="text-3xl font-bold mb-2">Create Account</h1>
            <p class="text-sm text-gray-500 mb-6">
                register to access all features
            </p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <input 
                    type="text" 
                    name="name" 
                    placeholder="Full Name"
                    class="w-full rounded-lg border-gray-300"
                    required
                >

                <input 
                    type="text" 
                    name="username" 
                    placeholder="Username"
                    class="w-full rounded-lg border-gray-300"
                    required
                >

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

                <!-- PASSWORD -->
                <div x-data="{ show: false }" class="relative">
                    <input 
                        :type="show ? 'text' : 'password'"
                        name="password"
                        placeholder="Password"
                        class="w-full rounded-lg border-gray-300 pr-16"
                        required
                    >
                    <button 
                        type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 hover:text-blue-600"
                        x-text="show ? 'HIDE' : 'SHOW'"
                    ></button>
                </div>

                <!-- CONFIRM PASSWORD -->
                <div x-data="{ show: false }" class="relative">
                    <input 
                        :type="show ? 'text' : 'password'"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        class="w-full rounded-lg border-gray-300 pr-16"
                        required
                    >
                    <button 
                        type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 hover:text-blue-600"
                        x-text="show ? 'HIDE' : 'SHOW'"
                    ></button>
                </div>

                <button class="w-40 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    SIGN UP
                </button>
            </form>
        </div>
    </div>

    <!-- BLUE SLIDE PANEL -->
    <div 
        class="absolute top-0 left-1/2 w-1/2 h-full bg-blue-600 text-white flex flex-col justify-center items-center text-center px-8 transition-all duration-700 ease-in-out rounded-l-[120px]"
        :class="open 
            ? '-translate-x-full rounded-r-[120px] rounded-l-none' 
            : 'translate-x-0'"
    >
        <h2 class="text-3xl font-bold mb-4"
            x-text="open ? 'Welcome Back!' : 'Hello, Friend!'">
        </h2>

        <p class="text-sm mb-6"
           x-text="open 
            ? 'Login to continue managing lab inventory'
            : 'Register to start borrowing lab equipment'">
        </p>

        <button
            @click="open = !open"
            class="border border-white px-8 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition"
            x-text="open ? 'SIGN IN' : 'SIGN UP'"
        ></button>
    </div>

</div>

</body>
</html>
