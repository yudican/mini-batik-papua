<?php

namespace App\Http\Livewire\Client;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProdukTransaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    public $user_id;
    public $total_order;
    public $total_order_temp;
    public $total_ongkir;
    public $total_bayar;
    public $kode_order;
    public $metode_pembayaran;
    public $tanggal_order;
    public $kode_unik;
    public $payment_method_id;
    public $status;
    public $catatan;
    public $carts;

    public function mount($order_id = null)
    {
        $this->kode_order = 'INV-' . date('d-mHi') . '-' . rand(123, 999) . '-' . date('Y');
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        $this->carts = $carts;
        $this->total_order_temp = 'Rp. ' . number_format($this->_getTotal($carts));
        $this->total_order = $this->_getTotal($carts);
        $this->total_bayar = $this->_getTotal($carts);
        if ($order_id) {
            $order = Order::find($order_id);
            if ($order) {
                $this->user_id = $order->user_id;
                $this->total_order_temp = 'Rp. ' . number_format($order->total_order);
                $this->total_order = $order->total_order;
                $this->kode_order = $order->kode_order;
                $this->metode_pembayaran = $order->metode_pembayaran;
                $this->tanggal_order = $order->tanggal_order;
                $this->kode_unik = $order->kode_unik;
                $this->payment_method_id = $order->payment_method_id;
                $this->status = $order->status;
                $this->catatan = $order->catatan;
                $this->carts = $order->orderDetails;
            }
        }
    }

    public function render()
    {

        return view(
            'livewire.client.checkout',
            [
                // 'total_price' => $this->_getTotal($carts),
                'payment_methods' => PaymentMethod::all()
            ]
        )->layout('layouts.user');
    }

    public function _validate()
    {
        $rule = [
            'metode_pembayaran'  => 'required',
        ];

        if ($this->metode_pembayaran == 'transfer') {
            $rule['payment_method_id'] = 'required';
        }

        return $this->validate($rule);
    }

    public function prosesCheckout()
    {
        $this->_validate();
        try {
            DB::beginTransaction();
            $kode_unik = rand(100, 999);
            $total_bayar = $this->total_order +  $kode_unik;
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'total_order' => $total_bayar,
                'kode_order' => $this->kode_order,
                'metode_pembayaran' => $this->metode_pembayaran,
                'tanggal_order' => date('Y-m-d'),
                'kode_unik' => substr($total_bayar, -3),
                'payment_method_id' => $this->payment_method_id,
                'status' => $this->metode_pembayaran == 'cash' ? 'Diproses' : 'Belum Bayar'
            ]);

            foreach ($this->carts as $cart) {
                $order->orderDetails()->create([
                    'produk_id' => $cart->produk_id,
                    'qty' => $cart->qty,
                    'subtotal' => $cart->qty * $cart->produk->harga_produk,
                ]);
                ProdukTransaksi::create([
                    'jumlah'  => $cart->qty,
                    'jenis_transaksi'  => 'transaksi-keluar',
                    'tanggal_transaksi'  => Carbon::now(),
                    'status_transaksi'  => 'diproses',
                    'produk_id'  => $cart->produk_id
                ]);
                $cart->delete();
            }



            DB::commit();
            if ($this->metode_pembayaran == 'transfer') {
                return $this->emit('showAlert', [
                    'msg' => 'Transaksi Berhasil, Silahkan Lakukan Pembayaran.',
                    'redirect' => true,
                    'path' => 'selesaikan-pesanan/' . $order->id
                ]);
            }
            return $this->emit('showAlert', [
                'msg' => 'Transaksi Berhasil, Silahkan Lakukan Pembayaran.',
                'redirect' => true,
                'path' => 'order'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
            return $this->emit('showAlertError', [
                'msg' => 'Transaksi Gagal, silahkan coba lagi.',
            ]);
        }
    }

    public function _getTotal($carts = [])
    {
        $total = 0;
        foreach ($carts as $cart) {
            $total += $cart->total_price;
        }

        return $total;
    }
}
