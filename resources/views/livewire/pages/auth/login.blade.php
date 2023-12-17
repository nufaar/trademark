<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        auth()->user()->loginLogs()->create();

        Session::regenerate();

        $this->redirect(session('url.intended', RouteServiceProvider::HOME), navigate: false);
    }
}; ?>

<div>
    <h1 class="mb-5">Log in</h1>

    @if (session('status'))
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('status') }}</div>
    @endif

    <form wire:submit="login">
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="form.email" type="email"
                class="form-control form-control-xl @error('form.email') is-invalid @enderror  @if ($errors->get('email')) is-invalid @endif"
                placeholder="Email" autocomplete="email">
            <x-maz-input-error error='form.email' />
            @if ($errors->get('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>

        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="form.password" type="password"
                class="form-control form-control-xl @error('form.password') is-invalid @enderror" placeholder="Password"
                autocomplete="password">
            <x-maz-input-error error='form.password' />
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        <div class="form-check form-check-lg d-flex align-items-end">
            <input wire:model="form.remember" class="form-check-input me-2" type="checkbox" value=""
                id="flexCheckDefault">
            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                Remember me
            </label>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
    </form>
    <div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="font-bold">Sign
                up</a>.</p>
        @if (Route::has('password.request'))
            <p><a class="font-bold" href="{{ route('password.request') }}">Lupa password?</a>.</p>
        @endif
    </div>
    <div class='mt-3 d-flex align-items-center justify-content-center'>
        <a class="" href="{{ route('socialite', ['provider' => 'google']) }}"><img
                src="{{ asset('assets/compiled/svg/ctn-google.svg') }}" alt="continue with google"></a>
    </div>
</div>
