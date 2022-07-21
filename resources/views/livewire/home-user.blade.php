<div class="page-inner">
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <button class="list-group-item list-group-item-action {{$selectedJenis == 'all' ? 'active' : ''}}" wire:click="filterProduk('{{$selected}}','all','{{$search}}')">
                    Semua Kategori
                </button>
                @foreach ($jenis_produk as $jenis)
                <button class="list-group-item list-group-item-action {{$selectedJenis == $jenis->id ? 'active' : ''}}" wire:click="filterProduk('{{$selected}}','{{$jenis->id}}','{{$search}}')">
                    {{$jenis->nama_jenis}}
                </button>
                @endforeach
            </div>
        </div>
        <div class="col-md-8">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">

                    @foreach ($banners as $key => $banner)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" class="@if ($key == 0) active @endif"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($banners as $key => $banner)
                    <div class="carousel-item @if ($key == 0) active @endif">
                        <img class="d-block w-100" src="{{asset('storage/'.$banner->banner_image)}}" style="height: 20%;object-fit:cover;" alt="{{$banner->banner_title}}">
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="form-check form-check-inline">
                        <button class="btn btn-{{$selected == 'all' ? 'primary' : 'primary-outline'}} btn-sm mr-2" wire:click="filterProduk('all','{{$selectedJenis}}','{{$search}}')">Semua</button>
                        @foreach ($katalog_produk as $katalog)
                        @if ($selected == $katalog->id ?? 'all')
                        <button class="btn btn-primary btn-sm mr-2" wire:click="filterProduk('{{$katalog->id}}','{{$selectedJenis}}','{{$search}}')">{{$katalog->nama_katalog}}</button>
                        @else
                        <button class="btn btn-primary-outline btn-sm mr-2" wire:click="filterProduk('{{$katalog->id}}','{{$selectedJenis}}','{{$search}}')">{{$katalog->nama_katalog}}</button>
                        @endif

                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <label for="table">Cari Produk</label>
                        <input type="text" wire:model="search" wire:keyup="filterProduk('{{$selected}}','{{$selectedJenis}}',$event.target.value)" class="form-control">
                    </div>

                </div>
            </div>
        </div>
        @foreach ($katalogs as $katalog)
        @if ($katalog->produk->count() > 0)
        <div class="col-md-12 mt-4">
            <h1>{{$katalog->nama_katalog}}</h1>
            <div class="row">
                @foreach ($katalog->produk as $product)
                @if ($selectedJenis == 'all' || $product->jenis_produk_id == $selectedJenis || $product->jenis_produk_id == 1)
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <a href="{{route('product-detail', ['produk_id' => $product->id])}}" style="text-decoration: none;;color:#000;">
                        <div class="card p-0">
                            <div class="card-body p-0  text-center">
                                @if ($product->stok > 0)
                                <label class="imagecheck mb-1">
                                    <figure class="imagecheck-figure text-center">
                                        <img src="{{asset('storage/'.$product->foto_produk)}}" style="height: 200px;" alt="title" class="imagecheck-image w-100">
                                    </figure>
                                    {{-- <span class="badge badge-success absolute">Stok
                                        {{$product->stok_produk}}</span> --}}
                                </label>
                                @else
                                <label class="imagecheck mb-1 cursor-default">
                                    <figure class="imagecheck-figure text-center">
                                        <img src="{{asset('storage/'.$product->foto_produk)}}" style="height: 200px;" alt="title" class="imagecheck-image w-100">
                                    </figure>
                                    {{-- <span class="badge badge-danger absolute">Habis</span> --}}
                                </label>
                                @endif
                            </div>
                            <div class="pl-3 pr-3 mb-0 pb-2">
                                <p class=" mb-0">{{$product->nama_produk}}</p>
                                <p class=" mb-0">Rp {{number_format($product->harga_produk)}}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>