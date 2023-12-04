<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="updateProfileInformation">
                        <div class="form-group my-2">
                            <label for="name" class="form-label">Name</label>
                            <input wire:model="name" type="text" name="name" id="name" class="form-control"
                                placeholder="Enter your current password" value="1L0V3Indonesia">
                        </div>
                        <div class="form-group my-2">
                            <label for="email" class="form-label">Email</label>
                            <input wire:model="email" type="email" name="email" id="email" class="form-control"
                                placeholder="Enter new password" value="">
                        </div>
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
                        <div class="form-group my-2">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input wire:model="current_password" type="password" name="current_password"
                                id="current_password" class="form-control" placeholder="Enter your current password"
                                value="1L0V3Indonesia">
                        </div>
                        <div class="form-group my-2">
                            <label for="password" class="form-label">New Password</label>
                            <input wire:model="password" type="password" name="password" id="password"
                                class="form-control" placeholder="Enter new password" value="">
                        </div>
                        <div class="form-group my-2">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input wire:model="password_confirmation" type="password" name="confirm_password"
                                id="confirm_password" class="form-control" placeholder="Enter confirm password"
                                value="">
                        </div>

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
