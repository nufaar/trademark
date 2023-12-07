<x-admin-layout>
    <x-slot:title>
        Edit Pengumuman
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('article.index') }}">Pengumuman</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Pengumuman
        </li>
    </x-slot:menu>

    <livewire:articles.edit :article="$article" />

</x-admin-layout>
