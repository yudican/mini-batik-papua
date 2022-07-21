<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('cart')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>Checkout</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-light">
                        <thead class="thead-light">
                            <tr>
                                <td>Produk</td>
                                <td>Jumlah</td>
                                <td>harga</td>
                                <td>Subtotal</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carts as $cart)
                            <tr>
                                <td>{{ $cart->produk->nama_produk }}</td>
                                <td>{{ $cart->qty }}</td>
                                <td>Rp. {{ number_format($cart->produk->harga_produk) }}</td>
                                <td>Rp. {{ number_format($cart->produk->harga_produk*$cart->qty) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-text-field type="text" name="kode_order" label="Kode Order" readonly />
                    <x-text-field type="text" name="total_order_temp" label="Total Order" readonly />
                    {{--
                    <x-text-field type="text" name="total_bayar" label="Total Bayar" readonly /> --}}
                    {{--
                    <x-text-field type="text" name="nomor_hp" label="Nomor Hp" />
                    <x-text-field type="text" name="alamat" label="Alamat" /> --}}
                    <x-select name="metode_pembayaran" label="Metode Pembayaran">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                    </x-select>
                    @if ($metode_pembayaran == 'transfer')
                    <x-select name="payment_method_id" label="Bank Pembayaran">
                        <option value="">Pilih Bank Pembayaran</option>
                        @foreach ($payment_methods as $payment_method)
                        <option value="{{$payment_method->id}}">{{$payment_method->nama_bank}}</option>
                        @endforeach
                    </x-select>
                    @endif

                    <div class="form-group">
                        <button class="btn btn-primary btn-sm" wire:click="prosesCheckout">Proses</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>