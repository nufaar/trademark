<x-admin-layout>
    <x-slot:title>
        Detail Permohonan
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}">Trademark</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Trademark
        </li>
    </x-slot:menu>

    <livewire:trademarks.show :trademark="$trademark" />

</x-admin-layout>
