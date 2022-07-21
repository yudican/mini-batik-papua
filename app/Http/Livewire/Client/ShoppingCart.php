<?php

namespace App\Http\Livewire\Client;

use App\Models\Cart;
use Livewire\Component;

class ShoppingCart extends Component
{

    public function render()
    {
        $carts = Cart::where('user_id', auth()->user()->id);
        return view('livewire.client.shopping-cart', [
            'carts' => $carts,
            'total_price' => $this->_getTotal($carts->get()),
        ])->layout('layouts.user');
    }

    public function removeCart($cart_id)
    {
        $cart = Cart::find($cart_id);

        if (!$cart) {
            return $this->emit('showAlertError', [
                'msg' => 'Keranjang gagal dihapus',
            ]);
        }

        $cart->delete();
        return $this->emit('showAlert', [
            'msg' => 'Keranjang berhasil dihapus',
        ]);
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
