<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if ($tab==1)
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item border-0">
                            <span><strong>Detail Pembayaran</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nama Bank
                            <span>{{$order->paymentMethod->nama_bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nama Akun Bank
                            <span>{{$order->paymentMethod->nama_rekening_bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Nomor Rekening
                            <span>{{$order->paymentMethod->nomor_rekening_bank}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            Kode Unik
                            <span>{{$order->kode_unik}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            <span>
                                Kami akan memproses transfer Anda lebih cepat jika Anda melakukan pembayaran dengan
                                jumlah yang tiga digit terakhirnya <strong>[{{$order->kode_unik}}]</strong>.
                                <br>
                                <strong>Contoh: Rp. {{number_format($order->total_order)}}</strong>. <br>
                                Agar pembayaran Anda terlihat unik, digitnya diambil dari random angka. Dengan
                                begitu waktu yang dibutuhkan untuk melacak dan memproses transfer semacam ini di antara
                                banyaknya permintaan akan lebih sedikit
                            </span>
                        </li>

                    </ul>
                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" wire:click="moveTab(2)">Konfirmasi Pembayaran</button>
                    </div>
                </div>
                @elseif ($tab == 2)
                <div class="card-body">
                    <x-text-field type="text" name="nama_bank" label="Nama Bank" />
                    <x-text-field type="text" name="nomor_rekening_bank" label="Nomor Rekening Bank" />
                    <x-text-field type="text" name="nama_rekening_bank" label="Nama Rekening Bank" />
                    <x-text-field type="number" name="jumlah_transfer" label="Jumlah Transfer" />
                    <x-text-field type="date" name="tanggal_transfer" label="Tanggal Transfer" />
                    <x-input-photo foto="{{$foto_struk}}" path="{{optional($foto_struk_path)->temporaryUrl()}}"
                        name="foto_struk_path" label="Upload Photo" />

                    <div class="form-group">
                        <button type="button" wire:click="confirmPayment" class="btn btn-danger btn-sm">Simpan</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>