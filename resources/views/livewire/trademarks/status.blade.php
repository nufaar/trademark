<?php

use App\Models\Trademark;
use Livewire\Volt\Component;

new class extends Component {
    public $comment;

    public Trademark $trademark;

    public function revision()
    {
        $this->validate([
            'comment' => 'required|string'
        ]);

        $this->trademark->update([
            'status' => 'revision',
            'comment' => $this->comment,
        ]);
    }
}; ?>

<div>
    <!-- Button trigger for login form modal -->
    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
            data-bs-target="#statusRevision">
        Revisi
    </button>

    <!--status revision Modal -->
    <div class="modal modal-borderless fade text-left" id="statusRevision" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
             role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Status </h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form wire:submit="revision">
                    <div class="modal-body">
                            <textarea wire:model="comment" name="comment" id="comment"
                                      class="form-control @error('comment') is-invalid @enderror"
                                      placeholder="Tuliskan alasan" rows="3" required></textarea>
                        <x-maz-input-error error="comment"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                                data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1"
                                data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Revisi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
