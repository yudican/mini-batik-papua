<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use App\Models\ProdukTransaksi;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'total' => ProdukTransaksi::count(),
            'masuk' => ProdukTransaksi::where('jenis_transaksi', 'transaksi-masuk')->count(),
            'keluar' => ProdukTransaksi::where('jenis_transaksi', 'transaksi-keluar')->count(),
            'produk' => Produk::count(),
        ]);
    }
}
