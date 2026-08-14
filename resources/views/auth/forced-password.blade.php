<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Welcome! Because your account was created with a temporary password, please set a new password before continuing.') }}
    </div>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Signed in as:') }} <strong>{{ $user->email }}</strong>
    </div>

    <form method="POST" action="{{ route('forced-password.update') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New Password')" />

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
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Set Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>