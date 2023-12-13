<?php

use Livewire\Volt\Component;

new class extends Component {
    public $keyword;
    public $trademarks = [];

    public function getTrademark()
    {
        $url = "https://pdki-indonesia.dgip.go.id/api/search?keyword=". $this->keyword ."&page=1&showFilter=true&type=trademark";
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
{{--    <div class="pt-20"></div>--}}
{{--    <div class="columns-2">--}}
{{--        <p>Well, let me tell you something, ...</p>--}}
{{--        <p class="">Sure, go ahead, laugh...</p>--}}
{{--        <p>Maybe we can live without...</p>--}}
{{--        <p>Look. If you think this is...</p>--}}
{{--    </div>--}}

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
