<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.temp')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (
            !Auth::guard('web')->validate([
                'email' => Auth::user()->email,
                'password' => $this->password,
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirect(session('url.intended', RouteServiceProvider::HOME), navigate: true);
    }
}; ?>

{{-- <div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</div> --}}

<div>
    <p class="auth-subtitle mb-5">This is a secure area of the application. Please confirm your password before
        continuing.</p>

    <form wire:submit="confirmPassword">
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="password" type="password"
                class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="Password"
                autocomplete="password">
            <x-maz-input-error error='password' />
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Confirm</button>
    </form>
</div>
