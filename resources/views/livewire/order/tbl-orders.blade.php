<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>orders</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if ($form_active)
            <div class="card">
                <div class="card-body">
                    <x-select name="user_id" label="Pelanggan">
                        <option value="">Select Pelanggan</option>
                    </x-select>
                    <x-text-field type="text" name="total_order" label="Total Order" />
                    <x-text-field type="text" name="kode_order" label="Kode Order" />
                    <x-select name="metode_pembayaran" label="Type Pembayaran">
                        <option value="">Select Type Pembayaran</option>
                    </x-select>
                    <x-text-field type="text" name="kode_unik" label="Kode Unik" />
                    <x-text-field type="date" name="tanggal_order" label="Tanggal Order" />
                    <x-select name="payment_method_id" label="Metode Pembayaran">
                        <option value="">Select Metode Pembayaran</option>
                    </x-select>
                    <x-text-field type="text" name="status" label="Status" />
                    <x-text-field type="text" name="catatan" label="Catatan" />

                    <div class="form-group">
                        <button class="btn btn-primary pull-right" wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
                    </div>
                </div>
            </div>
            @else
            <livewire:table.order-table />
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


        {{-- Modal form --}}
        <div id="form-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                @if ($order)

                @if (in_array($order->status, ['Dikirim','Diterima','Diproses','Selesai','Siap Diambil', 'Diambil']))
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="my-modal-title">
                            Rincian pesanan</h5>
                    </div>
                    <div class="modal-body">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nama Pelanggan
                            <span>{{$order->user->name}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Alamat
                            <span>{{$order->user->userDetail->alamat}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nomor Hp
                            <span>{{$order->user->userDetail->nomor_hp}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Jumlah Bayar
                            <span>Rp. {{number_format($order->total_order)}}</span>
                        </li>
                        @if ($order)
                        @if (auth()->user()->role->role_type == 'member' && in_array($order->status, ['Selesai']) &&
                        !$order->rate)
                        <x-select name="rate" label="Nilai">
                            <option value="">Pilih Nilai</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </x-select>
                        <x-textarea name="description" label="Deskripsi" />

                        @else

                        @if ($order->metode_pembayaran == 'cash')
                        @if ($order->status == 'Selesai')
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nilai
                            <span>{{$rate}}</span>
                        </li>
                        <li class="list-group-item border-0">
                            Deskripsi
                            <p>{{$description}}</p>
                        </li>
                        @else
                        <x-select name="status" label="Status">
                            <option value="">Select Status</option>
                            @if ($order->status == 'Diproses')
                            <option value="Siap Diambil">Siap Diambil</option>
                            @elseif ($order->status == 'Siap Diambil')
                            <option value="Diambil">Diambil</option>
                            @elseif ($order->status == 'Diambil')
                            <option value="Selesai">Selesai</option>
                            @endif
                        </x-select>
                        @endif

                        @else
                        @if (!in_array($order->status, ['Selesai']))
                        <x-select name="status" label="Status ">
                            <option value="">Select Status</option>
                            @if ($order->status == 'Diproses')
                            <option value="Diterima">Diterima</option>
                            <option value="Ditolak">Ditolak</option>
                            @elseif ($order->status == 'Diterima')
                            <option value="Siap Diambil">Siap Diambil</option>
                            @elseif ($order->status == 'Siap Diambil')
                            <option value="Diambil">Diambil</option>
                            @elseif ($order->status == 'Diambil')
                            <option value="Selesai">Selesai</option>
                            @endif

                        </x-select>
                        @if ($order->status == 'Ditolak')
                        <x-textarea name="catatan" label="Catatan" />
                        @endif
                        @endif
                        @endif
                        @endif
                        @endif


                    </div>
                    <div class="modal-footer">
                        @if ($order)
                        @if (auth()->user()->role->role_type == 'member' && in_array($order->status, ['Selesai']) &&
                        !$order->rate)
                        <button type="button" wire:click='rateOrder' class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan</button>
                        @else
                        @if (!in_array($order->status, ['Selesai']))
                        <button type="button" wire:click='updateOrder' class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan</button>
                        @endif
                        @endif

                        @endif
                        <button class="btn btn-danger btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>

                    </div>
                </div>
                @else
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="my-modal-title">
                            Detail Konfirmasi Pembayaran</h5>
                    </div>
                    <div class="modal-body">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nama Bank
                            <span>{{$nama_bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nama Akun Bank
                            <span>{{$nama_rekening_bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nomor Rekening
                            <span>{{$nomor_rekening_bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Jumlah Bayar
                            <span>Rp. {{number_format($jumlah_transfer)}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Tanggal bayar
                            <span>{{$tanggal_transfer}}</span>
                        </li>
                        <li class="list-group-item border-0 text-center justify-content-center align-items-center">
                            <img src="{{asset('storage/'.$foto_struk)}}" style="height: 200px;" alt="">
                        </li>


                        <x-select name="status" label="Status">
                            <option value="">Select Status</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Ditolak">Ditolak</option>
                        </x-select>
                        @if ($status == 'Ditolak')
                        <x-textarea name="catatan" label="Catatan" />
                        @endif

                    </div>
                    <div class="modal-footer">
                        @if ($order)
                        @if ($order->status == 'Diproses')
                        <button type="button" wire:click='update' class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan</button>
                        @endif
                        @endif
                        <button class="btn btn-danger btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>

                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
    @push('scripts')



    <script>
        document.addEventListener('livewire:load', function(e) {
            window.livewire.on('showModalConfirm', (data) => {
            $('#form-modal').modal('show')
        });

            window.livewire.on('closeModal', (data) => {
                $('#form-modal').modal('hide')
            });
        })
    </script>
    @endpush
</div>