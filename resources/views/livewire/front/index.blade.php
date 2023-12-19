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
            'announcements' => Article::latest()->take(4)->get()
        ];
    }
}; ?>

<div>
    <div class="mt-10 mb-20">
        <livewire:front.search />
    </div>
    <div class="">
        <p class="font-semibold text-xl mb-8 text-center">Pengumuman</p>
        <div class="flex flex-col gap-5 sm:flex-row mx-3">
            @foreach($announcements as $announcement)
                <div class="card min-w-72 bg-base-100 shadow-md">
                    <figure><img src="{{ asset('storage/articles/' . $announcement->image) }}" alt="Shoes" class="h-36 object-cover hover:scale-110 transition"/></figure>
                    <div class="card-body">
                        <h2 class="card-title"><a href="{{ route('front.announcement.show', $announcement->slug) }}">{{ $announcement->title }}</a></h2>
                        <p>{!! substr($announcement->content, 0, 50) !!}...</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="my-6 flex justify-end mx-3">
            <a href="{{ route('front.announcement') }}" class="btn">Selengkapnya</a>
        </div>
    </div>
</div>
