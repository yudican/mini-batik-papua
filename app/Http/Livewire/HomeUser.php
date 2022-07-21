<?php

namespace App\Http\Livewire;

use App\Models\Banner;
use App\Models\JenisProduk;
use App\Models\ProdukKatalog;
use Livewire\Component;

class HomeUser extends Component
{
    public $selected = 'all';
    public $selectedJenis = 'all';
    public $search;


    public function render()
    {
        $jenis_id = $this->selectedJenis;
        $search = $this->search;
        $katalogs = [];
        if ($this->selected == 'all' && $jenis_id == 'all') {
            $katalogs = ProdukKatalog::all();
        } elseif ($this->selected == 'all' && $jenis_id != 'all') {
            $katalogs = ProdukKatalog::whereHas('produk', function ($query) use ($search, $jenis_id) {
                return $query->where('jenis_produk_id', intval($jenis_id))->where('nama_produk', 'like', '%' . $search . '%');
            })->get();
        } elseif ($this->selected != 'all' && $jenis_id == 'all') {
            $katalogs = ProdukKatalog::whereId($this->selected)->get();
        } else {
            $katalogs = ProdukKatalog::whereId($this->selected)->whereHas('produk', function ($query) use ($search, $jenis_id) {
                return $query->where('jenis_produk_id', intval($jenis_id))->where('nama_produk', 'like', '%' . $search . '%');
            })->get();
        }
        return view('livewire.home-user', [
            'jenis_produk' => JenisProduk::all(),
            'banners' => Banner::all(),
            'katalog_produk' => ProdukKatalog::all(),
            'katalogs' => $katalogs,
        ])->layout('layouts.user');
    }

    public function filterProduk($value = 'all', $jenis_id = 'all', $search = null)
    {
        $this->selected = $value;
        $this->selectedJenis = $jenis_id;
        $this->search = $search;
    }
}
