<x-admin-layout>
    <x-slot:title>
        Profil
    </x-slot:title>

    <x-slot:subtitle>
        Ubah profil kamu.
    </x-slot:subtitle>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profil
        </li>
    </x-slot:menu>

    <livewire:users.profile.index />

</x-admin-layout>
