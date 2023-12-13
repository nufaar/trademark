<?php

use App\Models\Trademark;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Trademark $trademark;

    public $name;
    public $address;
    public $owner;
    public $logo;
    public $certificate;
    public $signature;

//    old image
    public $oldLogo;
    public $oldCertificate;
    public $oldSignature;

//    from api
    public $trademarks = [];

    public function mount(Trademark $trademark)
    {
        $this->trademark = $trademark;
        $this->name = $trademark->name;
        $this->address = $trademark->address;
        $this->owner = $trademark->owner;
        $this->oldLogo = $trademark->logo;
        $this->oldCertificate = $trademark->certificate;
        $this->oldSignature = $trademark->signature;
    }

    public function updatedName()
    {
        $url = "https://pdki-indonesia.dgip.go.id/api/search?keyword=" . $this->name . "&page=1&showFilter=true&type=trademark";
        $pdki_sign = "PDKI/735032dcbdf964d2c4426c1c2442e1650017fab3c979c42bbb390effc39425041625f60d46edfcd88363d4473bda49da967333c6a21ac6da689fc4321d5ed572";

        $data = Http::withHeaders([
            'Pdki-Signature' => $pdki_sign
        ])->get($url);

        $this->trademarks = array_slice($data->json()['hits']['hits'], 0, 3);

        if(empty($this->trademarks)) {
            $this->validateOnly('name');
        } else {
            $this->addError('name', 'Merek sudah diambil.');
        }
    }

    public function edit()
    {
        $this->validate(
            [
                'name' => 'required|string|max:255|unique:trademarks,name,' . $this->trademark->id,
                'address' => 'required|string',
                'owner' => 'required|string|max:255',
                'logo' => 'nullable|image|max:2048',
                'certificate' => 'nullable|max:5120',
                'signature' => 'nullable|image|max:2048',
            ]
        );

        if ($this->trademarks) {
            $this->addError('name', 'Merek sudah diambil.');
            return;
        }

        if ($this->logo) {
            $this->logo->store('logos', 'public');
            if ($this->oldLogo && file_exists(storage_path('app/public/logos/' . $this->oldLogo))) {
                unlink(storage_path('app/public/logos/' . $this->oldLogo));
            }
        }

        if ($this->certificate) {
            $this->certificate->store('certificates', 'public');
            if ($this->oldCertificate && file_exists(storage_path('app/public/certificates/' . $this->oldCertificate))) {
                unlink(storage_path('app/public/certificates/' . $this->oldCertificate));
            }
        }

        if ($this->signature) {
            $this->signature->store('signatures', 'public');
            if ($this->oldSignature && file_exists(storage_path('app/public/signatures/' . $this->oldSignature))) {
                unlink(storage_path('app/public/signatures/' . $this->oldSignature));
            }
        }

        $this->trademark->update([
            'name' => $this->name,
            'address' => $this->address,
            'owner' => $this->owner,
            'logo' => $this->logo ? $this->logo->hashName() : $this->oldLogo,
            'certificate' => $this->certificate ? $this->certificate->hashName() : $this->oldCertificate,
            'signature' => $this->signature ? $this->signature->hashName() : $this->oldSignature,
        ]);

        session()->flash('success', 'Data berhasil diubah.');

        return redirect()->route('trademark.index');
    }


}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Permohonan</h5>
            </div>
            <div class="card-body">
                <form wire:submit="edit">
{{--                    <x-maz-form-input property="name" label="Nama Usaha" type="text" name="name"--}}
{{--                                      placeholder="Masukan nama usaha"/>--}}
                    <div class="form-group my-2">
                        <label for="name" class="form-label">Nama merek</label>
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
                                        <span class="d-block">{{ number_format($trademark['_score'], 2, '.', '') . '% kesamaan' }}</span>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group my-2">
                        <label for="address" class="form-label">Alamat Usaha</label>
                        <textarea wire:model="address" name="address" id="address"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Masukan alamat" rows="3"></textarea>
                        <x-maz-input-error error="address"/>
                    </div>

                    <x-maz-form-input property="owner" label="Pemilik Usaha" type="text" name="owner"
                                      placeholder="Masukan nama pemilik usaha"/>

                    <x-maz-form-input property="logo" label="Logo Usaha" type="file" name="logo" />
                    <div>
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="logo {{ $name }}" width="100">
                        @else
                            <img src="{{ asset('storage/logos/' . $oldLogo) }}" alt="logo {{ $name }}" width="100">
                        @endif
                    </div>

                    <x-maz-form-input property="certificate" label="Surat Keterangan UMK" type="file" name="certificate" />

                    <x-maz-form-input property="signature" label="Tanda Tangan" type="file" name="signature" />

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
