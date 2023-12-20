<?php

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;

    public User $user;

    public function mount()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->role = $this->user->roles->first()->name;
    }

    public function with()
    {
        return [
            'roles' => Role::all()->pluck('name'),
        ];
    }

    public function edit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => now(),
        ]);

        $this->user->syncRoles($this->role);

        $this->dispatch('user-updated');
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('password', 'password_confirmation');

        $this->dispatch('password-updated');
    }

}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route('user.index') }}" class="btn btn-primary icon icon-left"><i
                        class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <form wire:submit="edit">
                    <x-maz-form-input property="name" label="Nama" type="text" name="name"
                                      placeholder="Enter name"/>
                    <x-maz-form-input property="email" label="Email" type="email" name="email"
                                      placeholder="Enter email"/>
                    <div class="form-group">
                        <label for="role" class="form-label">Peran</label>
                        <select wire:model="role" class="form-select" id="role">
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group my-2 d-flex justify-content-end align-items-center">
                        <x-action-message class="me-3" on="user-updated"></x-action-message>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit User Password</h5>
            </div>
            <div class="card-body">
                <form wire:submit="updatePassword">
                    <x-maz-form-input property="password" label="Password" type="password" name="password"
                                      placeholder="Masukan password baru"/>
                    <x-maz-form-input property="password_confirmation" label="Konfirmasi Password" type="password"
                                      name="confirm_password" placeholder="Konfirmasi password"/>

                    <div class="form-group my-2 d-flex justify-content-end align-items-center">
                        <x-action-message class="me-3" on="password-updated"></x-action-message>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
