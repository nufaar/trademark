<?php

use App\Models\Article;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use withPagination;

    public $search = '';
    public $perPage = 5;

    function with()
    {
        return [
            'articles' => Article::query()
                ->when($this->search, function ($query) {
                    return scopeSearch($query, $this->search, ['title']);
                })
                ->paginate($this->perPage)
        ];
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if ($article->image && file_exists(storage_path('app/public/articles/' . $article->image))) {
            unlink(storage_path('app/public/articles/' . $article->image));
        }

        $article->delete();

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'message' => 'Pengumuman berhasil dihapus'
        ]);
    }

    public function publish($id)
    {
        $article = Article::findOrFail($id);
        $article->is_published = !$article->is_published;
        $article->save();
    }
}; ?>

<div>
    <div class="row mb-4">
        <div class="d-flex justify-content-between flex-column flex-sm-row gap-4">
            <div>
                <input wire:model.live.debounce300ms="search" type="text" class="form-control"
                       placeholder="Cari...">
            </div>
            @can('create articles')
                <a href="{{ route('article.create') }}" class="btn btn-primary icon icon-left"><i
                        class="bi bi-person-add"></i>
                    Tambah Pengumuman</a>
            @endcan
        </div>
    </div>

    <div class="row">
        @foreach($articles as $article)
            <div class="col-12 col-md-4">
                <div class="card d-flex flex-column justify-content-between" style="height: 370px">
                    <div class="card-content">
                        <div>
                            <img src="{{ asset('storage/articles/' . $article->image) }}" alt=""
                                 class="card-img-top img-fluid" style="object-fit: cover; height: 200px">
                            <div class="card-body pb-1">
                                @if($article->is_published)
                                    <small>{{ $article->updated_at->diffForHumans() }}</small>
                                @endif
                                <h5>{{ $article->title }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between pt-0 align-items-center">
                        <div>
                            @can('publish articles')
                                <input wire:click="publish({{ $article->id }})" type="checkbox" class="form-check-input"
                                       @if($article->is_published) checked @endif> Publish
                            @endcan
                        </div>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            @can('show articles')
                                <a href="{{ route('article.show', ['article' => $article->slug]) }}"
                                   class="btn icon btn-sm btn-primary"
                                   data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                   data-bs-original-title="Detail"><i class="bi bi-eye"></i></a>
                            @endcan
                            @can('edit articles')
                                <a href="{{ route('article.edit', ['article' => $article->id]) }}"
                                   class="btn icon btn-sm btn-primary"
                                   data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                   data-bs-original-title="Edit"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('delete articles')
                                <button wire:click="destroy({{ $article->id }})"
                                        class="btn icon btn-sm btn-primary"
                                        data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                        data-bs-original-title="Hapus">
                                    <i class="bi bi-x-lg"></i></button>
                            @endcan

                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-between mb-4">
            <div>
                <select wire:model.live="perPage" class="form-select">
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                </select>
            </div>
            <div>{{ $articles->links() }}</div>
        </div>
    </div>
    @if(session('success'))
        <span class="d-none" id="success">{{ session('success') }}</span>
    @endif
</div>

@script
<script>
    let cek = document.getElementById('success')
    if (cek) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: cek.innerText,
        })
    }

    $wire.on('showAlert', function (data) {
        Swal.fire({
            icon: data[0].icon,
            title: data[0].title,
            text: data[0].message,
        })
    })

</script>
@endscript
