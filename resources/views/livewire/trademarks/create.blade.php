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
    #[Validate('max:5120')]
    public $certificate;
    #[Validate('required|image|max:2048')]
    public $signature;

    public $trademarks = [];

    public function updatedName()
    {
        $url = "https://pdki-indonesia.dgip.go.id/api/search?keyword=" . $this->name . "&page=1&showFilter=true&type=trademark";
        $pdki_sign = "PDKI/735032dcbdf964d2c4426c1c2442e1650017fab3c979c42bbb390effc39425041625f60d46edfcd88363d4473bda49da967333c6a21ac6da689fc4321d5ed572";

        $data = Http::withHeaders([
            'Pdki-Signature' => $pdki_sign
        ])->get($url);


        $this->trademarks = array_slice($data->json()['hits']['hits'], 0, 3);


        if (empty($this->trademarks)) {
            $this->validateOnly('name');
        } else {
            $this->addError('name', 'Merek sudah diambil.');
        }
    }

    public function store()
    {
        $this->validate();

        if ($this->trademarks) {
            $this->addError('name', 'Merek sudah diambil.');
            return;
        }

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
                <form wire:submit="store">

                    <div class="form-group my-2">
                        <label for="name" class="form-label">Nama Merek</label>
                        <input wire:model.blur="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Masukan nama merek">
                        <x-maz-input-error error="name"/>
                    </div>
                    @if($trademarks)
                        <div class="mb-2 text-sm">
                            <ul>
                                @foreach($trademarks as $trademark)
                                    <div class="d-block text-danger mb-2">
                                        <div>{{ $trademark['_source']['nama_merek'] }}</div>
                                        <span
                                            class="d-block">{{ number_format($trademark['_score'], 2, '.', '') . '% kesamaan' }}</span>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    @endif


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

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
