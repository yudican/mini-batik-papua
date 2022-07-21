<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('home.user')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>Keranjang Saya</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if ($carts->count() > 0)
                    <table class="table table-light">
                        <thead class="thead-light">
                            <tr>
                                <td></td>
                                <td>Produk</td>
                                <td>Jumlah</td>
                                <td>harga</td>
                                <td>Subtotal</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carts->get() as $cart)
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        wire:click="removeCart('{{ $cart->id }}')" id="btn-delete-{{ $cart->id }}"><i
                                            class="fas fa-trash"></i></button>

                                </td>
                                <td>{{ $cart->produk->nama_produk }}</td>
                                <td>{{ $cart->qty }}</td>
                                <td>Rp. {{ number_format($cart->produk->harga_produk) }}</td>
                                <td>Rp. {{ number_format($cart->produk->harga_produk*$cart->qty) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Total</b></td>
                                <td><b>Rp. {{number_format($total_price)}}</b></td>
                            </tr>
                        </tbody>
                    </table>

                    <a href="{{route('checkout')}}">
                        <button class="btn btn-primary float-right">Proses
                            Pembayaran</button>
                    </a>
                    @else
                    <div class="text-center">
                        <h1>Keranjang masih kosong</h1>
                        <a href="{{route('home.user')}}"><button class="btn btn-primary btn-sm">Belanja
                                Sekarang</button></a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>