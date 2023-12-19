<?php

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('front.index')]
class extends Component {
    public function with()
    {
        return [
            'announcements' => Article::latest()->where(['is_published' => true])->get()
        ];
    }
}; ?>

<div>
    <p class="font-semibold text-xl mb-8 text-center mt-6">Pengumuman</p>
    <div class="mt-6 shadow-md p-3 w-full flex flex-wrap">
        @foreach($announcements as $announcement)
            <div class="card basis-1/2 md:basis-1/3 lg:basis-1/4 bg-base-100 shadow-md">
                <figure><img src="{{ asset('storage/articles/' . $announcement->image) }}" alt="Shoes"
                             class="h-36 object-cover hover:scale-110 transition"/></figure>
                <div class="card-body">
                    <h2 class="card-title"><a wire:navigate
                            href="{{ route('front.announcement.show', $announcement->slug) }}">{{ $announcement->title }}</a></h2>
                    <p>{!! substr($announcement->content, 0, 50) !!}...</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
