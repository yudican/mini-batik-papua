<?php

namespace App\Http\Livewire\Table;

use App\Exports\OrderExport;
use App\Models\HideableColumn;
use App\Models\Order;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use App\Http\Livewire\Table\LivewireDatatable;
use App\Models\ConfirmPayment;
use Maatwebsite\Excel\Facades\Excel;

class OrderTable extends LivewireDatatable
{
    protected $listeners = ['refreshTable'];
    public $hideable = 'select';
    public $table_name = 'tbl_orders';
    public $hide = [];
    public $exportable = true;

    public function builder()
    {
        $role = auth()->user()->role;
        if ($role->role_type == 'member') {
            $this->exportable = false;
            return Order::query()->where('user_id', auth()->user()->id);
        }
        return Order::query();
    }

    public function columns()
    {
        $this->hide = HideableColumn::where(['table_name' => $this->table_name, 'user_id' => auth()->user()->id])->pluck('column_name')->toArray();
        $role = auth()->user()->role;
        if ($role->role_type == 'member') {
            return [
                Column::name('id')->label('No.'),
                Column::name('kode_order')->label('Kode Order')->searchable(),
                Column::callback('total_order', function ($total) {
                    return 'Rp. ' . number_format($total);
                })->label('Total Order')->searchable(),
                Column::name('metode_pembayaran')->label('Type Pembayaran')->searchable(),
                Column::name('kode_unik')->label('Kode Unik')->searchable(),
                Column::name('tanggal_order')->label('Tanggal Order')->searchable(),
                Column::name('paymentMethod.nama_bank')->label('Metode Pembayaran')->searchable(),
                Column::name('status')->label('Status')->searchable(),
                Column::name('catatan')->label('Catatan')->searchable(),

                Column::callback(['tbl_orders.id', 'tbl_orders.status'], function ($id, $status) {
                    $order = Order::find($id);
                    $aksi = '';
                    if ($status == 'Ditolak') {
                        $aksi .= '<a href="' . route('checkout.payment', ['order_id' => $id]) . '"><button type="button" class="btn btn-danger btn-sm" id="btn-reconfirm-' . $id . '">Konfirmasi Ulang Pembayaran</button></a>';
                    } else if ($status == 'Selesai') {
                        if (!$order->rate) {
                            $aksi .= '<button id="btn-rate-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getDataById(' . $id . ')" id="btn-rating-' . $id . '">Beri Ulasan</button>';
                        }
                    } else if ($status == 'Belum Bayar') {
                        $aksi .= '<a href="' . route('checkout.payment', ['order_id' => $id]) . '"><button type="button" class="btn btn-success btn-sm" id="btn-confirm-' . $id . '">Konfirmasi Pembayaran</button></a>';
                    }
                    $aksi .= '<a href="' . route('invoice', ['order_id' => $id]) . '" target="_blank"><button id="btn-invoice-' . $id . '" type="button" class="btn btn-primary btn-sm" id="btn-invoice-' . $id . '">Lihat Invoice</button></a>';

                    return $aksi;
                })->label(__('Aksi')),
            ];
        }
        return [
            Column::name('kode_order')->label('Kode Order')->searchable(),
            Column::name('user.name')->label('Pelanggan')->searchable(),
            Column::callback('total_order', function ($total) {
                return 'Rp. ' . number_format($total);
            })->label('Total Order')->searchable(),
            Column::name('metode_pembayaran')->label('Type Pembayaran')->searchable(),
            Column::name('kode_unik')->label('Kode Unik')->searchable(),
            Column::name('tanggal_order')->label('Tanggal Order')->searchable(),
            Column::name('paymentMethod.nama_bank')->label('Metode Pembayaran')->searchable(),
            Column::name('status')->label('Status')->searchable(),
            Column::name('catatan')->label('Catatan')->searchable(),

            Column::callback(['tbl_orders.id', 'tbl_orders.status', 'tbl_orders.metode_pembayaran'], function ($id, $status, $metode_pembayaran) {
                $role = auth()->user()->role;
                $aksi = '';
                $aksi .= '<a href="' . route('invoice', ['order_id' => $id]) . '" target="_blank"><button id="btn-invoice-' . $id . '" type="button" class="btn btn-primary btn-sm">Lihat Invoice</button></a>';

                if ($role->role_type == 'admin') {
                    if ($metode_pembayaran == 'cash') {
                        if (in_array($status, ['Selesai', 'Diambil'])) {
                            $aksi .= '<button id="btn-detail-' . $id . '" type="button" class="btn btn-success btn-sm" wire:click="getDataById(' . $id . ')">Detail Pesanan</button>';
                        } else {
                            $aksi .= '<button id="btn-update-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getDataById(' . $id . ')">Update Status Pesanan</button>';
                        }
                    } else {
                        $confirmPayment = ConfirmPayment::where('order_id', $id)->first();
                        if ($confirmPayment) {
                            if ($status == 'Diproses') {
                                $aksi .= '<button id="btn-update-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getConfirmPaymentId(' . $confirmPayment->id . ')">Lihat Pembayaran</button>';
                            } else if (in_array($status, ['Selesai', 'Diambil'])) {
                                $aksi .= '<button  id="btn-detail-' . $id . '" type="button" class="btn btn-success btn-sm" wire:click="getConfirmPaymentId(' . $confirmPayment->id . ')">Detail Pesanan</button>';
                            } else {
                                $aksi .= '<button id="btn-update-' . $id . '" type="button" class="btn btn-primary btn-sm" wire:click="getConfirmPaymentId(' . $confirmPayment->id . ')">Update Status Pesanan</button>';
                            }
                        }
                    }
                }

                return $aksi;
            })->label(__('Aksi')),
        ];
    }

    public function getDataById($id)
    {
        $this->emit('getDataOrderById', $id);
    }

    public function getId($id)
    {
        $this->emit('getOrderId', $id);
    }
    public function getConfirmPaymentId($id)
    {
        $this->emit('getDataConfirmPayment', $id);
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

    public function export()
    {
        return Excel::download(new OrderExport(), 'data-transaction.xlsx');
    }
}
