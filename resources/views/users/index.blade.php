<x-admin-layout>
    <x-slot:title>
        Users
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">List Users
        </li>
    </x-slot:menu>

    <livewire:users.index />

</x-admin-layout>
