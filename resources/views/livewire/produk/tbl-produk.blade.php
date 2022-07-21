<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>produk {{$route_name}}</span>
                        </a>
                        <div class="pull-right">
                            @if ($form_active)
                            <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i> Cancel</button>
                            @else
                            @if (auth()->user()->hasTeamPermission($curteam, $route_name.':create'))
                            <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i>
                                Add
                                New</button>
                            @endif
                            @endif
                        </div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-select name="jenis_produk_id" label="Jenis Produk" handleChange="filterProduct" type="filter">
                        <option value="all">Semua Jenis Produk</option>
                        @foreach ($jenis_produks as $jenis_produk)
                        <option value="{{$jenis_produk->id}}">{{$jenis_produk->nama_jenis}}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if ($form_active)
            <div class="card">
                <div class="card-body">
                    <x-text-field type="text" name="nama_produk" label="Nama Produk" />
                    <x-select name="jenis_produk_id" label="Jenis Produk">
                        <option value="">Pilih Jenis Produk</option>
                        @foreach ($jenis_produks as $jenis_produk)
                        <option value="{{$jenis_produk->id}}">{{$jenis_produk->nama_jenis}}</option>
                        @endforeach
                    </x-select>
                    <x-select name="produk_katalog_id" label="Produk Katalog">
                        <option value="">Pilih Produk Katalog</option>
                        @foreach ($katalog_produks as $katalog_produk)
                        <option value="{{$katalog_produk->id}}">{{$katalog_produk->nama_katalog}}</option>
                        @endforeach
                    </x-select>
                    <x-text-field type="number" name="harga_produk" label="Harga Produk" />
                    <x-input-photo foto="{{$foto_produk}}" path="{{optional($foto_produk_path)->temporaryUrl()}}" name="foto_produk_path" label="Foto Produk" />
                    <div wire:ignore class="form-group @error('deskripsi_produk')has-error has-feedback @enderror">
                        <label for="deskripsi_produk" class="text-capitalize">Deskripsi Produk</label>
                        <textarea wire:model="deskripsi_produk" id="deskripsi_produk" class="form-control"></textarea>
                        @error('deskripsi_produk')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary pull-right" wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
                    </div>
                </div>
            </div>
            @else
            <livewire:table.produk-table params="{{$route_name}}" />
            @endif

        </div>

        {{-- Modal confirm --}}
        <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin hapus data ini.?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i class="fa fa-check pr-2"></i>Ya, Hapus</button>
                        <button class="btn btn-primary btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
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
                $('#deskripsi_produk').summernote({
            placeholder: 'deskripsi_produk',
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
            tabsize: 2,
            height: 300,
            callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('deskripsi_produk', contents);
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