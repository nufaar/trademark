<x-admin-layout>
    <x-slot:title>
        Merek Dagang
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}">Trademark</a></li>
        <li class="breadcrumb-item active" aria-current="page">List Trademark
        </li>
    </x-slot:menu>

    <livewire:trademarks.index />

</x-admin-layout>
