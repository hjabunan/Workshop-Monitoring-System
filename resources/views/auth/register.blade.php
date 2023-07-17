<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- ID Number -->
        <div class="mt-4">
            <x-input-label for="idum" :value="__('ID Number')" />
            <x-text-input id="idnum" class="block mt-1 w-full" type="text" name="idnum" :value="old('idnum')" required />
            <x-input-error :messages="$errors->get('idnum')" class="mt-2" />
        </div>

        <!-- Department -->
        <div class="mt-4">
            <x-input-label for="dept" :value="__('Department')" />
            <x-text-input id="dept" class="block mt-1 w-full" type="text" name="dept" :value="old('dept')" required />
            <x-input-error :messages="$errors->get('dept')" class="mt-2" />
        </div>

        <!-- Area -->
        <div class="mt-4">
            <x-input-label for="area" :value="__('Area')" />
            {{-- <select id="area" name="area" class="block mt-1 w-full" required>
                <option value="">Select an area</option>
                @foreach ($sections as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select> --}}
            <x-text-input id="area" class="block mt-1 w-full" type="text" name="area" :value="old('area')" required />
            <x-input-error :messages="$errors->get('area')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />

            <select id="role" class="block mt-1 w-full"
                            name="role" required >
                <option selected>Choose a Role</option>
                <option value="0">User</option>
                <option value="1">Admin</option>
            </select>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
