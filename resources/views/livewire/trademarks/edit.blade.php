<?php

use App\Models\Trademark;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public Trademark $trademark;

    #[Validate('required|string|max:255')]
    public $name;
    #[Validate('required|string')]
    public $address;
    #[Validate('required|string|max:255')]
    public $owner;
    #[Validate('max:2048')]
    public $logo;
    #[Validate('max:5120')]
    public $certificate;
    #[Validate('max:2048')]
    public $signature;

//    old image
    public $oldLogo;
    public $oldCertificate;
    public $oldSignature;

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

    public function edit()
    {
        $this->validate();

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
                    <x-maz-form-input property="name" label="Nama Usaha" type="text" name="name"
                                      placeholder="Masukan nama usaha"/>

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
