<?php

use App\Models\Trademark;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    #[Validate('required|string|max:255')]
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
        ]);

        session()->flash('success', 'Data berhasil ditambahkan.');

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
                <form wire:submit="store">
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
                    <div wire:loading wire:target="logo">Uploading...</div>

                    <x-maz-form-input property="certificate" label="Surat Keterangan UMK" type="file" name="certificate" />

                    <x-maz-form-input property="signature" label="Tanda Tangan" type="file" name="signature" />

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
