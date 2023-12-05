<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Component;

new class extends Component {
    public $name;
    public $email;
    public $password;

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => now(),
        ]);

        session()->flash('success', 'User berhasil ditambahkan');
        $this->redirectRoute('user.index');
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambahkan User</h5>
            </div>
            <div class="card-body">
                <form wire:submit="store">
                    <x-maz-form-input property="name" label="Name" type="text" name="name"
                                      placeholder="Enter name"/>
                    <x-maz-form-input property="email" label="Email" type="email" name="email"
                                      placeholder="Enter email"/>
                    <x-maz-form-input property="password" label="Password" type="password" name="password" placeholder="Enter password"/>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
