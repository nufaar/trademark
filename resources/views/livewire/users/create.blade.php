<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'pemohon';

    public function with()
    {
        return [
            'roles' => Role::all()->pluck('name'),
        ];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',

        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($this->role);

        session()->flash('success', 'User berhasil ditambahkan');
        $this->redirectRoute('user.index');
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit="store">
                    <x-maz-form-input property="name" label="Nama" type="text" name="name"
                                      placeholder="Masukan nama"/>
                    <x-maz-form-input property="email" label="Email" type="email" name="email"
                                      placeholder="Masukan email"/>
                    <x-maz-form-input property="password" label="Password" type="password" name="password"
                                      placeholder="Masukan password"/>
                    <x-maz-form-input property="password_confirmation" label="Konfirmasi Password" type="password"  name="password_confirmation"
                                      placeholder="Konfirmasi password"/>

                    <div class="form-group">
                        <label for="role" class="form-label">Peran</label>
                        <select wire:model="role" class="form-select" id="role">
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group my-2 d-flex justify-content-end flex-column flex-sm-row">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
