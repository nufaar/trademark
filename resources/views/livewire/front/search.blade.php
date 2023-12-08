<?php

use Livewire\Volt\Component;

new class extends Component {
    public $keyword;
    public $trademarks = [];

    public function getTrademark()
    {
        $url = "https://pdki-indonesia-api.dgip.go.id/api/trademark/search2?keyword=". $this->keyword ."&page=1&showFilter=true&type=trademark&order_state=asc";
        $key = private_key($this->keyword);

        $data = Http::post($url, [
            'key' => $key
        ]);

        return $data->json();
    }

    public function search()
    {
        $data = $this->getTrademark();
        $this->trademarks = $data['hits']['hits'];
    }
}; ?>

<div>
    <div class="flex flex-col w-screen h-screen items-center pt-24">
        <div class="flex gap-4 w-full justify-center">
            <input wire:model="keyword" type="text" placeholder="Type here" class="input w-full max-w-xs"/>
            <button wire:click="search" class="btn">Search</button>
        </div>

        <div class="flex w-full justify-center mt-4 ">
            <div class="card w-full max-w-sm bg-base-100 shadow-xl">
                <div class="card-body">
                    @forelse($trademarks as $trademark)
                        <div class="flex gap-6 my-2">
                            <div class="w-32 h-24">
                                <img src="{{ $trademark['_source']['image'][0]['image_path'] }}"
                                     alt="{{ $trademark['_source']['image'][0]['brand_name'] }}">
                            </div>
                            <div class="">
                                <p class="text-md font-bold">{{ $trademark['_source']['nama_merek'] }}</p>
                                <div class="flex gap-2">
                                    <p class="badge badge-neutral">{{ $trademark['_source']['status_group']['status_group'] }}</p>
                                    <p
                                        class="text-md font-light">{{ $trademark['_source']['nomor_permohonan'] }}</p>
                                </div>
                                <p class="text-sm font-light">Score : {{ $trademark['_score'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">No data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
