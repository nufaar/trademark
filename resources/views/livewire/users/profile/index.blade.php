<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="updateProfileInformation">
                        <x-maz-form-input property="name" label="Name" type="text" name="name"
                            placeholder="Enter your name" />
                        <x-maz-form-input property="email" label="Email" type="email" name="email"
                            placeholder="Enter email" />
                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
                                !auth()->user()->hasVerifiedEmail())
                            <div>
                                <p class="fs-6 mt-2">
                                    {{ __('Your email address is unverified.') }}

                                    <button wire:click.prevent="sendVerification" class="btn btn-success">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 fs-6 text-success">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="form-group my-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Change Password</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="updatePassword">
                        <x-maz-form-input property="current_password" label="Current Password" type="password"
                            name="current_password" placeholder="Enter your current password" />
                        <x-maz-form-input property="password" label="Password" type="password" name="password"
                            placeholder="Enter new password" />
                        <x-maz-form-input property="password_confirmation" label="Confirm Password" type="password"
                            name="confirm_password" placeholder="Enter confirm password" />

                        <div class="form-group my-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Delete Account</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="deleteUser">
                        <p>Your account will be permanently deleted and cannot be restored, click "Touch me!" and enter
                            your password to continue</p>
                        <input wire:model="password_delete" type="password" class="form-control"
                            placeholder="Enter password" value="">
                        <div class="form-check mt-3">
                            <div class="checkbox">
                                <input type="checkbox" id="iaggree" class="form-check-input">
                                <label for="iaggree">Touch me! If you agree to delete permanently</label>
                            </div>
                        </div>
                        <div class="form-group my-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger" id="btn-delete-account"
                                disabled>Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
