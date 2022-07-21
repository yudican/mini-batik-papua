<?php

namespace App\Http\Livewire\Transaksi;

use App\Models\JenisProduk;
use App\Models\Produk;
use App\Models\ProdukTransaksi;
use Carbon\Carbon;
use Livewire\Component;


class ProdukTransaksiController extends Component
{

    public $tbl_produk_transaksi_id;
    public $jumlah;
    public $jenis_transaksi;
    public $jenis_transaksi_data;
    public $tanggal_transaksi;
    public $status_transaksi;
    public $produk_id;
    public $jenis_produk_id;
    public $filter = [];

    public $route_name = null;
    public $label = null;

    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    protected $listeners = ['getDataProdukTransaksiById', 'getProdukTransaksiId'];

    public function mount()
    {
        $this->jenis_transaksi = request()->segment(1);
        $this->jenis_transaksi_data = request()->segment(1);
        $this->label = str_replace('-', ' ', $this->jenis_transaksi);

        $this->route_name = request()->route()->getName();
    }

    public function render()
    {
        $produk = Produk::all();
        if ($this->jenis_transaksi_data === 'transaksi-keluar') {
            $produk = Produk::whereHas('produkTransaksi')->get();
        }
        return view('livewire.transaksi.tbl-produk-transaksi', [
            'items' => ProdukTransaksi::all(),
            'produks' => $produk,
            'jenis_produks' => JenisProduk::all(),
        ]);
    }

    public function store()
    {
        $this->_validate();

        $data = [
            'jumlah'  => $this->jumlah,
            'jenis_transaksi'  => $this->jenis_transaksi_data,
            'tanggal_transaksi'  => Carbon::now(),
            'status_transaksi'  => 'berhasil',
            'produk_id'  => $this->produk_id
        ];

        ProdukTransaksi::create($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();

        $data = [
            'jumlah'  => $this->jumlah,
            'jenis_transaksi'  => $this->jenis_transaksi_data,
            'tanggal_transaksi'  => $this->tanggal_transaksi,
            'status_transaksi'  => $this->status_transaksi,
            'produk_id'  => $this->produk_id
        ];
        $row = ProdukTransaksi::find($this->tbl_produk_transaksi_id);



        $row->update($data);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        ProdukTransaksi::find($this->tbl_produk_transaksi_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'jumlah'  => 'required',
            'produk_id'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataProdukTransaksiById($tbl_produk_transaksi_id)
    {
        $this->_reset();
        $tbl_produk_transaksi = ProdukTransaksi::find($tbl_produk_transaksi_id);
        $this->tbl_produk_transaksi_id = $tbl_produk_transaksi->id;
        $this->jumlah = $tbl_produk_transaksi->jumlah;
        $this->jenis_transaksi = $tbl_produk_transaksi->jenis_transaksi;
        $this->tanggal_transaksi = date('Y-m-d', strtotime($tbl_produk_transaksi->tanggal_transaksi));
        $this->status_transaksi = $tbl_produk_transaksi->status_transaksi;
        $this->produk_id = $tbl_produk_transaksi->produk_id;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getProdukTransaksiId($tbl_produk_transaksi_id)
    {
        $tbl_produk_transaksi = ProdukTransaksi::find($tbl_produk_transaksi_id);
        $this->tbl_produk_transaksi_id = $tbl_produk_transaksi->id;
    }

    public function toggleForm($form)
    {
        $this->_reset();
        $this->tanggal_transaksi = date('Y-m-d');
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->_reset();
        $this->tanggal_transaksi = date('Y-m-d');
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->tbl_produk_transaksi_id = null;
        $this->jumlah = null;
        $this->jenis_transaksi = null;
        $this->tanggal_transaksi = null;
        $this->status_transaksi = null;
        $this->produk_id = null;
        $this->jenis_produk_id = null;
        $this->form = false;
        $this->filter = [];
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
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
