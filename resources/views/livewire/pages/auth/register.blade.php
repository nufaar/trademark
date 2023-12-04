<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME);
    }
}; ?>

<div>
    <h1 class="auth-title">Sign Up</h1>
    <p class="auth-subtitle mb-5">Input your data to register to our website.</p>

    <form wire:submit="register">
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="name" type="text"
                class="form-control form-control-xl  @error('name') is-invalid @enderror" placeholder="Name"
                autocomplete="name">
            <x-maz-input-error error='name' />
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="email" type="text"
                class="form-control form-control-xl  @error('email') is-invalid @enderror" placeholder="Email"
                autocomplete="email">
            <x-maz-input-error error='email' />
            <div class="form-control-icon">
                <i class="bi bi-envelope"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="password" type="password"
                class="form-control form-control-xl  @error('password') is-invalid @enderror" placeholder="Password"
                autocomplete="new-password">
            <x-maz-input-error error='password' />
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="password_confirmation" type="password"
                class="form-control form-control-xl  @error('password_confirmation') is-invalid @enderror"
                placeholder="Confirm Password" autocomplete="new-password">
            <x-maz-input-error error='password_confirmation' />
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
    </form>

    <div class="text-center mt-5 text-lg fs-4">
        <p class='text-gray-600'>Already have an account? <a href="{{ route('login') }}" class="font-bold">Log
                in</a>.</p>
    </div>
    <div class='mt-3 d-flex align-items-center justify-content-center'>
        <a class="" href="{{ route('socialite', ['provider' => 'google']) }}"><img
                src="{{ asset('assets/compiled/svg/ctn-google.svg') }}" alt="continue with google"></a>
    </div>
</div>
