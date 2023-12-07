<?php

use App\Models\Article;
use Livewire\Volt\Component;

new class extends Component {
    function with() {
        return ['articles' => Article::with('user')->latest()->get()];
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if ($article->image && file_exists(storage_path('app/public/articles/' . $article->image))) {
            unlink(storage_path('app/public/articles/' . $article->image));
        }

        $article->delete();

        session()->flash('success', 'Pengumuman berhasil dihapus');
    }
}; ?>

<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">
                Daftar Pengumuman
            </h5>
            <a href="{{ route('article.create') }}" class="btn btn-primary icon icon-left"><i
                    class="bi bi-person-add"></i>
                Tambah Pengumuman</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                    <div wire:key="{{ $article->id }}">
                        <tr>
                            <td>
                                {{ $article->title }}
                            </td>
                            <td>
                                {{ $article->created_at->format('d F Y') }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="{{ route('article.show', ['article' => $article->slug]) }}"
                                       class="btn icon btn-sm btn-primary"
                                       data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                       data-bs-original-title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('article.edit', ['article' => $article->id]) }}"
                                       class="btn icon btn-sm btn-primary"
                                       data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                       data-bs-original-title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy({{ $article->id }})"
                                            class="btn icon btn-sm btn-primary"
                                            data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="Hapus">
                                        <i class="bi bi-x-lg"></i></button>
                                </div>
                            </td>

                        </tr>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
