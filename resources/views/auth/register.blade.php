<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" value="{{ __('Name') }}" />
            <x-text-input id="name" class="block mt-1 w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="{{ __('Email') }}" />
            <x-text-input id="email" class="block mt-1 w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input id="password" class="block mt-1 w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 bg-pink-400 hover:bg-pink-500 text-white font-bold rounded-lg shadow-md transition duration-150 ease-in-out tracking-wider uppercase">
                Register
            </button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-sm text-gray-600">Already Registered?</span>
            <a class="text-sm text-pink-500 hover:text-pink-700 underline font-bold ml-1" href="{{ route('login') }}">
                Login
            </a>
        </div>
    </form>
</x-guest-layout>