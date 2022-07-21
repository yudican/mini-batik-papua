<?php

namespace App\Http\Livewire\Master;

use App\Models\JenisProduk;
use Livewire\Component;


class JenisProdukController extends Component
{
    
    public $tbl_jenis_produk_id;
    public $nama_jenis;
    
   

    public $route_name = false;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataJenisProdukById', 'getJenisProdukId'];

    public function mount()
    {
        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        return view('livewire.master.tbl-jenis-produk', [
            'items' => JenisProduk::all()
        ]);
    }

    public function store()
    {
        $this->_validate();
        
        $data = ['nama_jenis'  => $this->nama_jenis];

        JenisProduk::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = ['nama_jenis'  => $this->nama_jenis];
        $row = JenisProduk::find($this->tbl_jenis_produk_id);

        

        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        JenisProduk::find($this->tbl_jenis_produk_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'nama_jenis'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataJenisProdukById($tbl_jenis_produk_id)
    {
        $this->_reset();
        $tbl_jenis_produk = JenisProduk::find($tbl_jenis_produk_id);
        $this->tbl_jenis_produk_id = $tbl_jenis_produk->id;
        $this->nama_jenis = $tbl_jenis_produk->nama_jenis;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getJenisProdukId($tbl_jenis_produk_id)
    {
        $tbl_jenis_produk = JenisProduk::find($tbl_jenis_produk_id);
        $this->tbl_jenis_produk_id = $tbl_jenis_produk->id;
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
        $this->tbl_jenis_produk_id = null;
        $this->nama_jenis = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
