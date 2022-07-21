<div class="page-inner">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="page-pretitle">
                        Pembayaran
                    </h6>
                    <h4 class="page-title">Invoice #{{$order->kode_order}}</h4>
                </div>
                <div class="col-auto">
                    <a href="{{route('order')}}" class="btn btn-light btn-border">
                        Kembali
                    </a>
                </div>
            </div>
            <div class="page-divider"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-invoice">
                        <div class="card-header">
                            <div class="invoice-header">
                                <h3 class="invoice-title">
                                    Invoice
                                </h3>
                                <div class="invoice-logo">
                                    <span><strong>MINI BATIK PAPUA</strong></span>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="separator-solid"></div>
                            <div class="row">
                                <div class="col-md-4 info-invoice">
                                    <h5 class="sub">Date</h5>
                                    <p>{{date('l,d M Y',strtotime($order->tanggal_order))}}</p>
                                </div>
                                <div class="col-md-4 info-invoice">
                                    <h5 class="sub">Invoice ID</h5>
                                    <p>#{{$order->kode_order}}</p>
                                </div>
                                <div class="col-md-4 info-invoice">
                                    <h5 class="sub">Invoice To</h5>
                                    <p>
                                        {{$order->user->userDetail->alamat}}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="invoice-detail">
                                        <div class="invoice-top">
                                            <h3 class="title"><strong>Rincian Order</strong></h3>
                                        </div>
                                        <div class="invoice-item">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <td><strong>Item</strong></td>
                                                            <td class="text-center"><strong>Price</strong></td>
                                                            <td class="text-center"><strong>Quantity</strong></td>
                                                            <td class="text-right"><strong>Totals</strong></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->orderDetails as $item)
                                                        <tr>
                                                            <td>{{$item->produk->nama_produk}}</td>
                                                            <td class="text-center">{{number_format($item->produk->harga_produk)}}</td>
                                                            <td class="text-center">{{$item->qty}}</td>
                                                            <td class="text-right">{{number_format($item->produk->harga_produk*$item->qty)}}</td>
                                                        </tr>
                                                        @endforeach


                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-center"><strong>Subtotal</strong></td>
                                                            <td class="text-right">{{number_format($order->total)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator-solid  mb-3"></div>
                                </div>
                            </div>
                        </div>
                        @if ($order->paymentMethod)
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-7 col-md-5 mb-3 mb-md-0 transfer-to">
                                    <h5 class="sub">Bank Transfer</h5>
                                    <div class="account-transfer">
                                        <div><span>Account Name:</span><span>{{$order->paymentMethod->nama_rekening_bank}}</span></div>
                                        <div><span>Account Number:</span><span>{{$order->paymentMethod->nomor_rekening_bank}}</span></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>