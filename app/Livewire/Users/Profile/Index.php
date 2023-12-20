<?php

namespace App\Livewire\Users\Profile;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Livewire\Actions\Logout;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';
    public string $email = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $password_delete = '';

    public $agree = false;
    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function updatePassword(): void
    {
        try {
            if (auth()->user()->socialAccounts->isNotEmpty() && auth()->user()->password === null) {
                $validated = $this->validate([
                    'current_password' => ['current_password', 'string'],
                    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
                ]);
            } else {
                $validated = $this->validate([
                    'current_password' => ['required', 'current_password', 'string'],
                    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
                ]);
            }
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $path = session('url.intended', RouteServiceProvider::HOME);

            $this->redirect($path);

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function deleteUser(Logout $logout): void
    {
        if (auth()->user()->socialAccounts->isNotEmpty() && auth()->user()->password === null) {
            $rules = [
                'password_delete' => ['string', 'current_password'],
            ];
        } else {
            $rules = [
                'password_delete' => ['required', 'string', 'current_password'],
            ];
        }
        $this->validate($rules);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.users.profile.index');
    }
}
