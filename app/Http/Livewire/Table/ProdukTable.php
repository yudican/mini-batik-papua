<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\Produk;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class ProdukTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable', 'filterProduct'];
    public $hideable = 'select';
    public $table_name = 'tbl_produk';
    public $hide = [];
    public $params = [];
    public $filters = [];
    // public $exportable = true;

    public function builder()
    {
        if (isset($this->filters['jenis_produk_id'])) {
            if ($this->filters['jenis_produk_id'] == 'all') {
                return Produk::query();
            }
            return Produk::query()->where('jenis_produk_id', $this->filters['jenis_produk_id']);
        } else if (isset($this->params['jenis_produk_id'])) {
            return Produk::query()->where('jenis_produk_id', $this->params['jenis_produk_id']);
        }
        return Produk::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('nama_produk')->label('Nama Produk')->searchable(),
            Column::callback(['foto_produk'], function ($image) {
                return view('livewire.components.photo', [
                    'image_url' => asset('storage/' . $image),
                ]);
            })->label(__('Foto Produk')),
            Column::name('harga_produk')->label('Harga Produk')->searchable(),
            Column::callback('id', 'getStok')->label('Stok Produk'),
            // Column::name('deskripsi_produk')->label('Deskripsi Produk')->searchable(),
            Column::name('jenisProduk.nama_jenis')->label('Jenis Produk')->searchable(),
            Column::name('produkKatalog.nama_katalog')->label('Produk Katalog')->searchable(),

            Column::callback(['id'], function ($id) {
                return view('livewire.components.action-button', [
                    'id' => $id,
                    'segment' => $this->params
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getStok($produk_id)
    {
        $produk = Produk::find($produk_id);
        $masuk = $produk->produkTransaksi()->whereJenisTransaksi('transaksi-masuk')->sum('jumlah');
        $keluar = $produk->produkTransaksi()->whereJenisTransaksi('transaksi-keluar')->sum('jumlah');

        return $masuk - $keluar;
    }

    public function getDataById($id)
    {
        $this->emit('getDataProdukById', $id);
    }

    public function getId($id)
    {
        $this->emit('getProdukId', $id);
    }

    public function refreshTable()
    {
        $this->emit('refreshLivewireDatatable');
    }

    public function toggle($index)
    {
        if ($this->sort == $index) {
            $this->initialiseSort();
        }

        $column = HideableColumn::where([
            'table_name' => $this->table_name,
            'column_name' => $this->columns[$index]['name'],
            'index' => $index,
            'user_id' => auth()->user()->id
        ])->first();

        if (!$this->columns[$index]['hidden']) {
            unset($this->activeSelectFilters[$index]);
        }

        $this->columns[$index]['hidden'] = !$this->columns[$index]['hidden'];

        if (!$column) {
            HideableColumn::updateOrCreate([
                'table_name' => $this->table_name,
                'column_name' => $this->columns[$index]['name'],
                'index' => $index,
                'user_id' => auth()->user()->id
            ]);
        } else {
            $column->delete();
        }
    }

    public function filterProduct($data)
    {
        $this->filters = $data;
    }
}
