<?php

namespace App\Http\Livewire\Table;

use App\Models\HideableColumn;
use App\Models\ProdukTransaksi;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;

class ProdukTransaksiTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable', 'filterProduct'];
    public $hideable = 'select';
    public $table_name = 'tbl_produk_transaksi';
    public $hide = [];
    public $params = [];
    public $filters = [];

    public function builder()
    {
        if (isset($this->filters['jenis_produk_id'])) {
            if ($this->filters['jenis_produk_id'] == 'all') {
                return  ProdukTransaksi::query()->where('jenis_transaksi', $this->params['type']);
            }
            return  ProdukTransaksi::query()->where('jenis_transaksi', $this->params['type'])->whereHas('produk', function ($query) {
                return $query->where('jenis_produk_id', $this->filters['jenis_produk_id']);
            });
        } else if (isset($this->params['jenis_produk_id'])) {
            return  ProdukTransaksi::query()->where('jenis_transaksi', $this->params['type'])->whereHas('produk', function ($query) {
                return $query->where('jenis_produk_id', $this->filters['jenis_produk_id']);
            });
        }
        return  ProdukTransaksi::query()->where('jenis_transaksi', $this->params['type']);
    }


    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        return [
            Column::name('id')->label('No.'),
            Column::name('jumlah')->label('Jumlah')->searchable(),
            Column::name('jenis_transaksi')->label('Jenis Transaksi')->searchable(),
            Column::name('tanggal_transaksi')->label('Tanggal Transaksi')->searchable(),
            Column::name('status_transaksi')->label('Status Transaksi')->searchable(),
            Column::name('produk.nama_produk')->label('Produk Id')->searchable(),

            Column::callback(['id'], function ($id) {
                return view('livewire.components.action-button', [
                    'id' => $id,
                    'segment' => $this->params['params']
                ]);
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataProdukTransaksiById', $id);
    }

    public function getId($id)
    {
        $this->emit('getProdukTransaksiId', $id);
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
