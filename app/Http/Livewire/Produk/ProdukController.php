<?php

namespace App\Http\Livewire\Produk;

use App\Models\JenisProduk;
use App\Models\Produk;
use App\Models\ProdukKatalog;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ProdukController extends Component
{
    use WithFileUploads;
    public $tbl_produk_id;
    public $nama_produk;
    public $foto_produk;
    public $harga_produk;
    public $deskripsi_produk;
    public $jenis_produk_id;
    public $produk_katalog_id;
    public $foto_produk_path;


    public $route_name = false;
    public $filter = [];

    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataProdukById', 'getProdukId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.produk.tbl-produk', [
            'items' => Produk::all(),
            'jenis_produks' => JenisProduk::all(),
            'katalog_produks' => ProdukKatalog::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        $foto_produk = $this->foto_produk_path->store('upload', 'public');
        $data = [
            'nama_produk'  => $this->nama_produk,
            'foto_produk'  => $foto_produk,
            'harga_produk'  => $this->harga_produk,
            'deskripsi_produk'  => $this->deskripsi_produk,
            'jenis_produk_id'  => $this->jenis_produk_id,
            'produk_katalog_id'  => $this->produk_katalog_id
        ];

        Produk::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'nama_produk'  => $this->nama_produk,
            'foto_produk'  => $this->foto_produk,
            'harga_produk'  => $this->harga_produk,
            'deskripsi_produk'  => $this->deskripsi_produk,
            'jenis_produk_id'  => $this->jenis_produk_id,
            'produk_katalog_id'  => $this->produk_katalog_id
        ];
        $row = Produk::find($this->tbl_produk_id);


        if ($this->foto_produk_path) {
            $foto_produk = $this->foto_produk_path->store('upload', 'public');
            $data = ['foto_produk' => $foto_produk];
            if (Storage::exists('public/' . $this->foto_produk)) {
                Storage::delete('public/' . $this->foto_produk);
            }
        }

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        Produk::find($this->tbl_produk_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_produk'  => 'required',
            'harga_produk'  => 'required',
            'deskripsi_produk'  => 'required',
            'jenis_produk_id'  => 'required',
            'produk_katalog_id'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataProdukById($tbl_produk_id)
    {
        $this->_reset();
        $tbl_produk = Produk::find($tbl_produk_id);
        $this->tbl_produk_id = $tbl_produk->id;
        $this->nama_produk = $tbl_produk->nama_produk;
        $this->foto_produk = $tbl_produk->foto_produk;
        $this->harga_produk = $tbl_produk->harga_produk;
        $this->deskripsi_produk = $tbl_produk->deskripsi_produk;
        $this->jenis_produk_id = $tbl_produk->jenis_produk_id;
        $this->produk_katalog_id = $tbl_produk->produk_katalog_id;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getProdukId($tbl_produk_id)
    {
        $tbl_produk = Produk::find($tbl_produk_id);
        $this->tbl_produk_id = $tbl_produk->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->tbl_produk_id = null;
        $this->nama_produk = null;
        $this->foto_produk = null;
        $this->foto_produk_path = null;
        $this->harga_produk = null;
        $this->deskripsi_produk = null;
        $this->jenis_produk_id = null;
        $this->produk_katalog_id = null;
        $this->form = true;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = false;
    }

    public function filterProduct($type, $value)
    {
        $this->emit('filterProduct', [
            'jenis_produk_id' => $value,
        ]);

        $this->filter = [
            'jenis_produk_id' => $value,
        ];
    }
}
