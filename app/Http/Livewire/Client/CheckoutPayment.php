<?php

namespace App\Http\Livewire\Client;

use App\Models\ConfirmPayment;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithFileUploads;

class CheckoutPayment extends Component
{
    use WithFileUploads;
    public $order;

    public $nama_bank;
    public $nomor_rekening_bank;
    public $nama_rekening_bank;
    public $jumlah_transfer;
    public $tanggal_transfer;
    public $foto_struk;
    public $foto_struk_path;

    public $tab = 1;
    public function mount($order_id)
    {
        $this->order = Order::whereId($order_id)->whereUserId(auth()->user()->id)->first();

        if (!$this->order) {
            return abort(404);
        }
        if ($this->order->status == 'Ditolak') {
            return $this->tab = 2;
        } else if ($this->order->status != 'Belum Bayar') {
            return redirect('/');
        }
    }
    public function render()
    {
        return view('livewire.client.checkout-payment')->layout('layouts.user');
    }

    public function confirmPayment()
    {
        $foto_struk = $this->foto_struk_path->store('upload', 'public');
        ConfirmPayment::updateOrCreate([
            'user_id' => auth()->user()->id,
            'order_id' => $this->order->id,
        ], [
            'user_id' => auth()->user()->id,
            'order_id' => $this->order->id,
            'nama_bank' => $this->nama_bank,
            'nomor_rekening_bank' => $this->nomor_rekening_bank,
            'nama_rekening_bank' => $this->nama_rekening_bank,
            'jumlah_transfer' => $this->jumlah_transfer,
            'tanggal_transfer' => $this->tanggal_transfer,
            'foto_struk' => $foto_struk,
        ]);

        Order::find($this->order->id)->update(['status' => 'Diproses']);

        return $this->emit('showAlert', [
            'msg' => 'Terima kasih, Pembayaran berhasil dikonfirmasi.',
            'redirect' => true,
            'path' => '/order'
        ]);
    }

    public function moveTab($tab = 1)
    {
        $this->tab = $tab;
    }
}
