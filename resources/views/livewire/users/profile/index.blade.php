<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Pengguna</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="updateProfileInformation">
                        <x-maz-form-input property="name" label="Nama" type="text" name="name"
                            placeholder="Enter your name" />
                        <x-maz-form-input property="email" label="Email" type="email" name="email"
                            placeholder="Enter email" disabled="{{ auth()->user()->socialAccounts->isNotEmpty() }}" />
                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
                                !auth()->user()->hasVerifiedEmail())
                            <div>
                                <p class="fs-6 mt-2">
                                    {{ __('Email kamu belum diverifikasi.') }}

                                    <button wire:click.prevent="sendVerification" class="btn btn-success">
                                        {{ __('Klik disini untuk mengirim ulang verifikasi email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 fs-6 text-success">
                                        {{ __('Link verifikasi baru sudah dikirimkan..') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="form-group my-2 d-flex justify-content-end align-items-center">
                            <x-action-message class="me-3" on="profile-updated"></x-action-message>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="updatePassword">
                        <x-maz-form-input property="current_password" label="Password Saat ini" type="password"
                            name="current_password" placeholder="Masukan password saat ini" />
                        <x-maz-form-input property="password" label="Password" type="password" name="password"
                            placeholder="Masukan password baru" />
                        <x-maz-form-input property="password_confirmation" label="Confirm Password" type="password"
                            name="confirm_password" placeholder="Konfirmasi password" />

                        <div class="form-group my-2 d-flex justify-content-end align-items-center">
                            <x-action-message class="me-3" on="password-updated"></x-action-message>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Hapus Akun</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="deleteUser">
                        <p>Akun kamu akan dihapus secara permanen dan tidak dapat dikembalikan</p>
                        <input wire:model="password_delete" @if (auth()->user()->socialAccounts->isNotEmpty()) disabled @endif
                            type="password" class="form-control @error('password_delete') is-invalid @enderror" placeholder="Masukan password" value="">
                        <x-maz-input-error error="password_delete" />
                        <div class="form-check mt-3">
                            <div class="checkbox">
                                <input wire:model.live="agree" type="checkbox" id="iaggree" class="form-check-input">
                                <label for="iaggree">Ceklis untuk menyetujui.</label>
                            </div>
                        </div>
                        <div class="form-group my-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger" id="btn-delete-account"
                                @if(!$agree) disabled @endif>Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
