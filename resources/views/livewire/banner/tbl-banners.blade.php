<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>banners</span>
                        </a>
                        <div class="pull-right">
                        @if ($form_active)
                        <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i> Cancel</button>
                        @else
                        @if (auth()->user()->hasTeamPermission($curteam, $route_name.':create'))
                        <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i> Add
                            New</button>
                        @endif
                        @endif
                        </div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if ($form_active)
                <div class="card">
                    <div class="card-body">
                    <x-text-field type="text" name="banner_title" label="Title" />
<x-input-photo foto="{{$banner_image}}" path="{{optional($banner_image_path)->temporaryUrl()}}"
                            name="banner_image_path"  label="Image" />
<div wire:ignore class="form-group @error('banner_description')has-error has-feedback @enderror">
                                <label for="banner_description" class="text-capitalize">Description</label>
                                <textarea wire:model="banner_description" id="banner_description" class="form-control"></textarea>
                                @error('banner_description')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                    <div class="form-group">
                        <button class="btn btn-primary pull-right"
                            wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
                    </div>
                    </div>
                </div>
            @else
                <livewire:table.banner-table params="{{$route_name}}" />
            @endif

        </div>

        {{-- Modal confirm --}}
        <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog"
            aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin hapus data ini.?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i
                                class="fa fa-check pr-2"></i>Ya, Hapus</button>
                        <button class="btn btn-primary btn-sm" wire:click='_reset'><i
                                class="fa fa-times pr-2"></i>Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="{{asset('assets/js/plugin/summernote/summernote-bs4.min.js')}}"></script>
    

    <script>
         document.addEventListener('livewire:load', function(e) {
            window.livewire.on('loadForm', (data) => {
                $('#banner_description').summernote({
            placeholder: 'banner_description',
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
            tabsize: 2,
            height: 300,
            callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('banner_description', contents);
                        }
                    }
            });
                
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
            });
        })
    </script>
    @endpush
</div>