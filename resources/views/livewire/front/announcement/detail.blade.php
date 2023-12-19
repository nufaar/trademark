<?php

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('front.index')]
class extends Component {
    public Article $article;

}; ?>

<div>
    <div>
        <h1 class="font-semibold text-3xl text-center mt-6">Pengumuman</h1>
    </div>

    <div>
        <div class="card">
            <div class="card-body">
                <figure><img src="{{ asset('storage/articles/' . $article->image) }}" alt="Shoes"
                             class="h-48 object-cover"/></figure>
                <h2 class="card-title">{{ $article->title }}</h2>
                <p class="text-sm text-gray-500"><span>Oleh {{ $article->user->name }}</span> - {{ $article->created_at->format('d F Y') }}</p>
                <p>{!! $article->content !!}</p>
            </div>
        </div>
    </div>
</div>
