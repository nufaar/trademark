<?php

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
    public $keyword;
    public $trademarks = [];

    public function getTrademark()
    {
        $url = "https://pdki-indonesia.dgip.go.id/api/search?keyword=" . $this->keyword . "&page=1&showFilter=true&type=trademark";
        $key = private_key($this->keyword);
        $pdki_sign = "PDKI/735032dcbdf964d2c4426c1c2442e1650017fab3c979c42bbb390effc39425041625f60d46edfcd88363d4473bda49da967333c6a21ac6da689fc4321d5ed572";

//        $data = Http::post($url, [
//            'key' => $key
//        ]);
        $data = Http::withHeaders([
            'Pdki-Signature' => $pdki_sign
        ])->get($url);

        return $data->json();
    }

    public function search()
    {
        $data = $this->getTrademark();
        $this->trademarks = $data['hits']['hits'];
    }
}; ?>

<div>

    <div class="flex flex-col items-center px-6">
        <h1 class="font-semibold text-xl mb-4">Penelusuran Data Merek PDKI</h1>
        <div class="flex gap-4 w-full justify-center">
            <input wire:model="keyword" type="text" placeholder="Cari..." class="input w-full max-w-xs input-bordered"/>
            <button wire:click="search" class="btn">Cari</button>
        </div>

        @if($trademarks)
            <div class="mt-6 shadow-md p-3 w-full flex flex-wrap">
                @foreach($trademarks as $trademark)
                    <div class="basis-1/2 md:basis-1/3 lg:basis-1/4 p-1">
                        <div class="flex p-2 gap-4 items-center rounded border border-base-300">
                            <div
                                class="w-24 h-24 bg-white flex items-center justify-center rounded border border-base-200 p-2">
                                <img src="{{ $trademark['_source']['image'][0]['image_path'] }}"
                                     alt="{{ $trademark['_source']['image'][0]['brand_name'] }}">
                            </div>
                            <div>
                                <div class="font-semibold">{{ $trademark['_source']['nama_merek'] }}</div>
                                <div>
                                    <p class="badge badge-{{ config('constants.trademark.status.color.' . $trademark['_source']['status_group']['status_group']) }}">{{ $trademark['_source']['status_group']['status_group'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
