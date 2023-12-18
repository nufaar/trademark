<x-admin-layout>
    <x-slot:title>
        Preview Pengumuman
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('article.index') }}">Pengumuman</a></li>
    </x-slot:menu>

    <livewire:articles.show :article="$article" />

</x-admin-layout>
