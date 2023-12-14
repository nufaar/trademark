<?php

use App\Livewire\Actions\Logout;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirect(session('url.intended', RouteServiceProvider::HOME), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>


<div>
    <h1 class="">Verifikasi Emailmu</h1>
    <p class="auth-subtitle mb-5">
        {{ __('Terima kasih sudah mendaftar!, lakukan verifikasi email dengan menekan tautan yang sudah kami kirimkan ke email kamu.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4">
            <div class="alert alert-success"><i class="bi bi-check-circle"></i>
                {{ __('Tautan verifikasi sudah kami kirimkan ke email kamu.') }}
            </div>
        </div>
    @endif

    <button wire:click="sendVerification"
        class="btn btn-primary btn-block btn-lg shadow-lg mt-5">{{ __('Kirim Ulang') }}</button>
</div>
