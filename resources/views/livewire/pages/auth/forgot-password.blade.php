<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>


<div>
    <h1 class="">Lupa Password</h1>
    <p class="text-xl mb-5">Masukan email kamu dan kami akan mengirimkan tautan untuk mengganti password.</p>

    @if (session('status'))
        <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('status') }}</div>
    @endif

    <form wire:submit="sendPasswordResetLink">
        <div class="form-group position-relative has-icon-left mb-4">
            <input wire:model="email" type="email"
                class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email">
            <x-maz-input-error error='email' />
            <div class="form-control-icon">
                <i class="bi bi-envelope"></i>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Kirim</button>
    </form>
    <div class="text-center mt-5 text-lg fs-4">
        <p class='text-gray-600'>Ingat akun? <a href="{{ route('login') }}" class="font-bold">Log in</a>.
        </p>
    </div>
</div>
