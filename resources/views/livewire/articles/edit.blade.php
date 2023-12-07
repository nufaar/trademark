<?php

use App\Models\Article;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Article $article;

    public $title;
    public $slug;
    public $content;
    public $image;
    public $is_published;

    public $oldImage;

    public function mount(Article $article)
    {
        $this->article = $article;
        $this->title = $article->title;
        $this->slug = $article->slug;
        $this->content = $article->content;
        $this->oldImage = $article->image;
        $this->is_published = $article->is_published;
    }

    public function updated($property)
    {
        if ($property == 'title') {
            $this->slug = Str::of($this->title)->slug('-');
        }
    }

    public function edit()
    {
        $this->validate([
            'title' => 'required',
            'slug' => 'required|unique:articles,slug,' . $this->article->id,
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($this->image) {
            $this->image->store('articles', 'public');
            if ($this->oldImage && file_exists(storage_path('app/public/articles/' . $this->oldImage))) {
                unlink(storage_path('app/public/articles/' . $this->oldImage));
            }
        }

        $this->article->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $this->image ? $this->image->hashName() : $this->oldImage,
            'is_published' => $this->is_published,
        ]);

        session()->flash('success', 'Pengumuman berhasil diubah');

        return redirect()->route('article.index');
    }

}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Pengumuman</h5>
            </div>
            <div class="card-body">
                <form wire:submit="edit">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group my-2">
                                <label for="judul" class="form-label">Judul</label>
                                <input wire:model.blur="title" type="text" name="judul" id="judul"
                                       class="form-control @error('title') is-invalid @enderror"
                                       placeholder="Masukan judul">
                                <x-maz-input-error error="{{ 'title' }}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-maz-form-input property="slug" label="Slug" type="text" name="slug"/>
                        </div>
                    </div>

                    <div class="form-group my-2">
                        <label for="address" class="form-label">Konten</label>
                        <textarea wire:model="content" name="content" id="address"
                                  class="form-control @error('content') is-invalid @enderror"
                                  placeholder="Masukan konten" rows="3"></textarea>
                        <x-maz-input-error error="content"/>
                    </div>

                    <x-maz-form-input property="image" label="Gambar" type="file" name="image"/>
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="" class="img-fluid">
                    @else
                        <img src="{{ asset('storage/articles/' . $oldImage) }}" alt="" class="img-fluid">
                    @endif

                    <div class="form-check my-3">
                        <div class="checkbox">
                            <input wire:model="is_published" type="checkbox" id="publish" class="form-check-input" @if($is_published) checked @endif>
                            <label for="publish">Publish</label>
                        </div>
                    </div>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
