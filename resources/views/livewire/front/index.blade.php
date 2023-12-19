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
            // get 4 published articles latest
            'announcements' => Article::latest()->where(['is_published' => true])->take(4)->get()
        ];
    }
}; ?>

<div>
    <div class="mt-10 mb-20">
        <livewire:front.search />
    </div>
    <div class="">
        <p class="font-semibold text-xl mb-8 text-center">Pengumuman</p>
        <div class="w-full flex flex-wrap mx-3">
            @foreach($announcements as $announcement)
                <div class="w-full sm:basis-1/2 md:basis-1/3 lg:basis-1/4 p-2">
                    <div class="card bg-base-100 shadow-md">
                        <figure><img src="{{ asset('storage/articles/' . $announcement->image) }}" alt="Shoes"
                                     class="h-36 object-cover hover:scale-110 transition"/></figure>
                        <div class="card-body">
                            <h2 class="card-title"><a wire:navigate
                                                      href="{{ route('front.announcement.show', $announcement->slug) }}">{{ $announcement->title }}</a>
                            </h2>
                            <p>{!! substr($announcement->content, 0, 50) !!}...</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="my-6 flex justify-end mx-3">
            <a wire:navigate href="{{ route('front.announcement') }}" class="btn">Selengkapnya</a>
        </div>
    </div>
</div>
