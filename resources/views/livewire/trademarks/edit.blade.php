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

    public Trademark $trademark;

    public $name;
    public $address;
    public $owner;
    public $logo;
    public $certificate;
    public $signature;
    public $kelas;

//    old image
    public $oldLogo;
    public $oldCertificate;
    public $oldSignature;

//    from api
    public $trademarks = [];

    public $maxScore;

    public function with()
    {
       return [
           'class' => range(1,45),
       ];
    }

    public function mount(Trademark $trademark)
    {
        $this->trademark = $trademark;
        $this->name = $trademark->name;
        $this->address = $trademark->address;
        $this->owner = $trademark->owner;
        $this->oldLogo = $trademark->logo;
        $this->oldCertificate = $trademark->certificate;
        $this->oldSignature = $trademark->signature;
        $this->kelas = $trademark->class ?? 1;
    }
    // rules
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:trademarks,name,' . $this->trademark->id,
            'address' => 'required|string',
            'owner' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'certificate' => 'nullable|max:5120|file|mimes:pdf,jpg,jpeg,png',
            'signature' => 'nullable|image|max:2048',
            'kelas' => 'required|integer'
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


        if (empty($this->trademarks)) {
            $this->validateOnly('name');
        } else {
            $this->maxScore = $this->trademarks[0]['score'];
        }
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
            'status' => 'pending',
            'class' => $this->kelas
        ]);

        session()->flash('success', 'Permohonan berhasil diubah.');



//        return redirect(route('trademark.index'), navigate: true));
        $this->redirect(route('trademark.index'));
    }


}; ?>

<div>
    <x-slot:title>
        Ubah Permohonan Merek
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}">Permohonan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah Permohonan
        </li>
    </x-slot:menu>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($maxScore > 99)
                    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i>
                        Merek sudah digunakan!</div>
                @endif
                <form wire:submit="edit">
                    <div class="form-group my-2">
                        <label for="name" class="form-label">Nama Merek</label>
                        <input wire:model.blur="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Masukan nama merek">
                        <x-maz-input-error error="name"/>
                    </div>
                    @if($trademarks)
                        <div class="mb-2 text-sm">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-danger">Merek sudah digunakan!</li>
                                @foreach($trademarks as $trademark)
                                    <li class="list-group-item">
                                        <div class=" mb-2">
                                            <div class="font-bold">{{ $trademark['name'] }}</div>
                                            <span
                                                class="d-block">{{ $trademark['score'] . '% kesamaan' }}</span>
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
                                      placeholder="Masukan nama pemilik usaha"/>

                    <x-maz-form-input property="logo" label="Logo" type="file" name="logo"/>
                    <div>
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="logo {{ $name }}" width="100">
                        @else
                            <img src="{{ asset('storage/logos/' . $oldLogo) }}" alt="logo {{ $name }}" width="100">
                        @endif
                    </div>

                    <x-maz-form-input property="certificate" label="Surat Keterangan UMK" type="file"
                                      name="certificate"/>

                    <div>
{{--                        check if certificate is pdf open in new tab if image show it --}}
                        @if($certificate)
                            @if($certificate->extension() === 'pdf')
                                <a href="{{ $certificate->temporaryUrl() }}" target="_blank">Lihat</a>
                            @else
                                <img src="{{ $certificate->temporaryUrl() }}" alt="certificate {{ $name }}"
                                     width="100">
                            @endif
                        @else
                            @if($oldCertificate)
                                @if(pathinfo(storage_path('app/public/certificates/' . $oldCertificate), PATHINFO_EXTENSION) === 'pdf')
                                    <a href="{{ asset('storage/certificates/' . $oldCertificate) }}" target="_blank">Lihat Surat</a>
                                @else
                                    <img src="{{ asset('storage/certificates/' . $oldCertificate) }}"
                                         alt="certificate {{ $name }}" width="100">
                                @endif
                            @endif
                        @endif

                    </div>

                    <x-maz-form-input property="signature" label="Tanda Tangan" type="file" name="signature"/>

                    <div>
                        @if($signature)
                            <img src="{{ $signature->temporaryUrl() }}" alt="signature {{ $name }}" width="100">
                        @else
                            <img src="{{ asset('storage/signatures/' . $oldSignature) }}" alt="signature {{ $name }}"
                                 width="100">
                        @endif

                    <div class="form-group my-2 d-flex justify-content-end flex-column flex-sm-row">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
