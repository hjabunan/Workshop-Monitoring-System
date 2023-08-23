@section('title','Workshop Monitoring System')
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="login" :value="__('Email/ID Number')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus  autocomplete="current-password"/>
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block pr-10 mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-2 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.992 12.361a16.892 16.892 0 01-2.767 3.713C16.814 17.984 14.486 20 12 20c-2.486 0-4.814-2.016-6.225-3.926a16.893 16.893 0 01-2.767-3.713C7.185 6.016 9.514 4 12 4c2.486 0 4.814 2.016 6.225 3.926z" />
                        </svg>
                    </button>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> --}}
        
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block pr-10 mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-2 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.992 12.361a16.892 16.892 0 01-2.767 3.713C16.814 17.984 14.486 20 12 20c-2.486 0-4.814-2.016-6.225-3.926a16.893 16.893 0 01-2.767-3.713C7.185 6.016 9.514 4 12 4c2.486 0 4.814 2.016 6.225 3.926z" />
                        </svg>
                    </button>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordButton = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            togglePasswordButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
            });
        });
    </script>
</x-guest-layout>