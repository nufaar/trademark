<?php

use App\Models\Trademark;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[Layout('layouts.admin')]
class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:255|unique:trademarks,name')]
    public $name;
    #[Validate('required|string')]
    public $address;
    #[Validate('required|string|max:255')]
    public $owner;
    #[Validate('required|image|max:2048')]
    public $logo;
    #[Validate('max:5120|file|mimes:pdf,jpg,jpeg,png')]
    public $certificate;
    #[Validate('required|image|max:2048')]
    public $signature;
    #[Validate('required|integer')]
    public $kelas;

    public $trademarks = [];

    public $maxScore;


    public function with()
    {
        return [
            'class' => range(1,45),
        ];
    }

    public function updatedName()
    {
        $url = "https://pdki-indonesia.dgip.go.id/api/search?keyword=" . $this->name . "&page=1&showFilter=true&type=trademark";
        $pdki_sign = "PDKI/735032dcbdf964d2c4426c1c2442e1650017fab3c979c42bbb390effc39425041625f60d46edfcd88363d4473bda49da967333c6a21ac6da689fc4321d5ed572";

        $data = Http::withHeaders([
            'Pdki-Signature' => $pdki_sign
        ])->get($url);


        $trademarks = array_slice($data->json()['hits']['hits'], 0, 3);
        $this->trademarks = getSimilarity($trademarks, $this->name);

//        dd($this->trademarks);


        if (empty($this->trademarks)) {
            $this->validateOnly('name');
        } else {
            $this->maxScore = $this->trademarks[0]['score'];
        }
    }

    public function store()
    {
        $this->validate();

        $this->logo->store('logos', 'public');
        $this->certificate->store('certificates', 'public');
        $this->signature->store('signatures', 'public');

        Trademark::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'address' => $this->address,
            'owner' => $this->owner,
            'logo' => $this->logo->hashName(),
            'certificate' => $this->certificate->hashName() ?? '',
            'signature' => $this->signature->hashName(),
            'class' => $this->kelas ?? 1,
        ]);

        session()->flash('success', 'Data berhasil ditambahkan.');
        $this->redirect(route('trademark.index'));
    }


}; ?>

<div>
    <x-slot:title>
        Tambah Permohonan Merek
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}" wire:navigate>Permohonan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Permohonan
        </li>
    </x-slot:menu>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($maxScore > 99)
                    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i>
                        Merek sudah digunakan!
                    </div>
                @endif
                <form wire:submit="store">

                    <div class="form-group my-2">
                        <label for="name" class="form-label">Nama Merek</label>
                        <input wire:model.blur="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Masukan nama merek">
                        <x-maz-input-error error="name"/>
                    </div>
                    @if($trademarks)
                        <div class="mb-2 text-sm col-6">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-warning">Merek yang sudah didaftar</li>
                                @foreach($trademarks as $trademark)
                                    <li class="list-group-item">
                                        <div class="mb-2 d-flex justify-content-between">
                                            <div>
                                                <div class="font-bold">{{ $trademark['name'] }}</div>
                                                <span
                                                    class="d-block">{{ round($trademark['score'], 2) . '% kesamaan' }}</span>
                                                <span
                                                    class="d-block">Kelas: {{ $trademark['kelas'] }}</span>
                                            </div>
                                            <div>
                                                <div
                                                    class="badge rou nded-pill bg-light-{{ config('constants.trademark.status.color.' . $trademark['status']) }}">{{ $trademark['status'] }}</div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group my-2">
                        <label for="role" class="form-label">Kelas</label>
                        <select wire:model="kelas" class="form-select" id="role">
                            @foreach($class as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group my-2">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea wire:model="address" name="address" id="address"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Masukan alamat" rows="3"></textarea>
                        <x-maz-input-error error="address"/>
                    </div>

                    <x-maz-form-input property="owner" label="Pemilik" type="text" name="owner"
                                      placeholder="Masukan nama pemilik"/>

                    <x-maz-form-input property="logo" label="Logo" type="file" name="logo"/>

                    <x-maz-form-input property="certificate" label="Surat Keterangan UMK" type="file"
                                      name="certificate"/>

                    <x-maz-form-input property="signature" label="Tanda Tangan" type="file" name="signature"/>

                    <div class="form-group my-3 d-flex justify-content-end flex-column flex-sm-row">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
