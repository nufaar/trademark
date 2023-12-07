<?php

use App\Models\Article;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $title;
    public $slug;
    public $content;
    public $image;
    public $is_published;

    public function updated($property)
    {
        if ($property == 'title') {
            $this->slug = Str::of($this->title)->slug('-');
        }
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'slug' => 'required|unique:articles',
            'content' => 'required',
            'image' => 'required|image|max:2048',
        ]);

        $this->image->store('articles', 'public');

        $article = Article::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $this->image->hashName(),
            'is_published' => $this->is_published,
        ]);


        session()->flash('success', 'Pengumuman berhasil ditambahkan');

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
                <form wire:submit="store">
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

                    <div class="form-check my-3">
                        <div class="checkbox">
                            <input wire:model="is_published" type="checkbox" id="publish" class="form-check-input">
                            <label for="publish">Publish</label>
                        </div>
                    </div>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
