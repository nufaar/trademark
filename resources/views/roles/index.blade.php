<x-admin-layout>
    <x-slot:title>
        Daftar Peran
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Peran</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Peran
        </li>
    </x-slot:menu>

    <livewire:roles.index />

</x-admin-layout>
