<?php

namespace App\Http\Livewire\Order;

use App\Models\ConfirmPayment;
use App\Models\Order;
use App\Models\Rate;
use Livewire\Component;


class OrderController extends Component
{

    public $order_id;
    public $user_id;
    public $confirm_payment_id;
    public $nama_bank;
    public $nomor_rekening_bank;
    public $nama_rekening_bank;
    public $jumlah_transfer;
    public $tanggal_transfer;
    public $foto_struk;
    public $status;
    public $order;
    public $catatan;

    // rate
    public $rate;
    public $description;



    public $form_active = false;
    public $form = true;
    public $update_mode = false;
    public $modal = false;

    protected $listeners = ['getDataConfirmPayment', 'getDataOrderById'];

    public function render()
    {
        return view('livewire.order.tbl-orders', [
            'items' => Order::all()
        ]);
    }

    public function update()
    {
        $this->_validate();

        $row = ConfirmPayment::find($this->confirm_payment_id);
        $data = [
            'status'  => $this->status,
            'catatan'  => $this->catatan
        ];
        $row->update($data);
        $row->order()->update(['status'  => $this->status]);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function updateOrder()
    {
        $order = Order::find($this->order->id);
        $order->update(['status'  => $this->status, 'catatan' => $this->catatan]);
        if ($order->payment_method == 'transfer') {
            $this->getDataConfirmPayment($this->confirm_payment_id);
        } else {
            $this->getDataOrderById($order->id);
        }
        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function rateOrder()
    {
        foreach ($this->order->orderDetails as $order) {
            Rate::create([
                'user_id' => auth()->user()->id,
                'produk_id' => $order->produk_id,
                'order_id' => $this->order_id,
                'rate' => $this->rate,
                'description' => $this->description
            ]);
        }
        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Penilaian Berhasil']);
    }

    public function delete()
    {
        Order::find($this->order_id)->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'status'  => 'required',
        ];

        if ($this->status == 'Ditolak') {
            $rule['catatan'] = 'required';
        }

        return $this->validate($rule);
    }

    public function getDataConfirmPayment($confirm_payment_id)
    {

        $confirm_payment = ConfirmPayment::find($confirm_payment_id);

        $this->confirm_payment_id = $confirm_payment->id;
        $this->nama_bank = $confirm_payment->nama_bank;
        $this->nomor_rekening_bank = $confirm_payment->nomor_rekening_bank;
        $this->nama_rekening_bank = $confirm_payment->nama_rekening_bank;
        $this->jumlah_transfer = $confirm_payment->jumlah_transfer;
        $this->tanggal_transfer = $confirm_payment->tanggal_transfer;
        $this->foto_struk = $confirm_payment->foto_struk;
        $this->catatan = $confirm_payment->order->catatan;
        $this->order = $confirm_payment->order;
        $this->rate = $confirm_payment->order->rate ? $confirm_payment->order->rate->rate : null;
        $this->description = $confirm_payment->order->rate ? $confirm_payment->order->rate->description : null;

        $this->emit('showModalConfirm');
    }

    public function getDataOrderById($order_id)
    {
        $order = Order::find($order_id);
        $this->order = $order;
        $this->order_id = $order->id;
        $this->catatan = $order->catatan;
        $this->rate = $order->rate ? $order->rate->rate : null;
        $this->description = $order->rate ? $order->rate->description : null;
        $this->emit('showModalConfirm');
    }


    public function _reset()
    {
        $this->emit('closeModal');
        $this->emit('refreshTable');
        $this->order_id = null;
        $this->user_id = null;
        $this->confirm_payment_id = null;
        $this->nama_bank = null;
        $this->nomor_rekening_bank = null;
        $this->nama_rekening_bank = null;
        $this->jumlah_transfer = null;
        $this->tanggal_transfer = null;
        $this->foto_struk = null;
        $this->status = null;
        $this->catatan = null;
        $this->order = null;
    }
}
