<?php

use App\Models\Article;
use Livewire\Volt\Component;

new class extends Component {
    public Article $article;

}; ?>

<div>
    <div class="card">
        <div class="card-header">
            <h5>{{ $article->title }}</h5>
            <small>{{ $article->created_at->format('l, d F Y') }}</small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mx-auto">
                    <img src="{{ asset('storage/articles/' . $article->image) }}" alt="image" class="w-100">
                </div>
            </div>
            <div class="row mt-5">
                <div>
                    {!! $article->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
