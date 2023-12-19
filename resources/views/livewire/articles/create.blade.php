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
    public $is_published = false;

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

                    <div wire:ignore class="my-2">
                        <label for="content" class="form-label">Konten</label>
                        <textarea wire:model="content" id="content"></textarea>
                    </div>
                    @error('content')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror


                    <x-maz-form-input property="image" label="Gambar" type="file" name="image"/>

                    <div class="form-check my-3">
                        <div class="checkbox">
                            <input wire:model="is_published" type="checkbox" id="publish" class="form-check-input">
                            <label for="publish">Publish</label>
                        </div>
                    </div>

                    <div class="form-group my-2 d-flex justify-content-end flex-column flex-md-row">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/oqad5da4gvp1dmdb6yhytc34893q38cra9goyqxiqj2ot4c6/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>

    <script>

        tinymce.init({

            selector: 'textarea#content', // Replace this CSS selector to match the placeholder element for TinyMCE

            plugins: 'powerpaste advcode table lists checklist',

            toolbar: 'undo redo | blocks| bold italic | bullist numlist checklist | code | table',

            setup: function (editor) {
                editor.on('init change', function () {
                    editor.save();
                });
                editor.on('change', function (e) {
                    @this.
                    set('content', editor.getContent());
                });
            }

        });

    </script>

</div>
