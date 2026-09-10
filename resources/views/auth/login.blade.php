<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="{{ __('Email') }}" />
            <x-text-input id="email" class="block mt-1 w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input id="password" class="block mt-1 w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-pink-300 text-pink-500 shadow-sm focus:ring-pink-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-pink-500 hover:text-pink-700 hover:underline focus:outline-none" href="{{ route('password.request') }}">
                Forgot password?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 bg-pink-400 hover:bg-pink-500 text-white font-bold rounded-lg shadow-md transition duration-150 ease-in-out tracking-wider uppercase">
                Login
            </button>
        </div>

        <div class="mt-8 text-center">
            @if (Route::has('register'))
                <span class="text-sm text-gray-600">Don't have an account?</span>
                <a class="text-sm text-pink-500 hover:text-pink-700 underline font-bold ml-1" href="{{ route('register') }}">
                    Register
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>