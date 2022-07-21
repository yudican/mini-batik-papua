<?php

namespace App\Http\Livewire\Master;

use App\Models\ProdukKatalog;
use Livewire\Component;


class ProdukKatalogController extends Component
{
    
    public $tbl_produk_katalog_id;
    public $nama_katalog;
    
   

    public $route_name = false;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataProdukKatalogById', 'getProdukKatalogId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.master.tbl-produk-katalog', [
            'items' => ProdukKatalog::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        
        $data = ['nama_katalog'  => $this->nama_katalog];

        ProdukKatalog::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = ['nama_katalog'  => $this->nama_katalog];
        $row = ProdukKatalog::find($this->tbl_produk_katalog_id);

        

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        ProdukKatalog::find($this->tbl_produk_katalog_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_katalog'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataProdukKatalogById($tbl_produk_katalog_id)
    {
        $this->_reset();
        $tbl_produk_katalog = ProdukKatalog::find($tbl_produk_katalog_id);
        $this->tbl_produk_katalog_id = $tbl_produk_katalog->id;
        $this->nama_katalog = $tbl_produk_katalog->nama_katalog;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getProdukKatalogId($tbl_produk_katalog_id)
    {
        $tbl_produk_katalog = ProdukKatalog::find($tbl_produk_katalog_id);
        $this->tbl_produk_katalog_id = $tbl_produk_katalog->id;
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
        $this->tbl_produk_katalog_id = null;
        $this->nama_katalog = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
