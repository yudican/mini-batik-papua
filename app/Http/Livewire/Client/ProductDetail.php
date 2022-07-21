<?php

namespace App\Http\Livewire\Client;

use App\Models\Cart;
use App\Models\Chat;
use App\Models\Produk;
use App\Models\User;
use Livewire\Component;

class ProductDetail extends Component
{
    public $product;
    public function mount($produk_id)
    {
        $product = Produk::find($produk_id);

        if (!$product) {
            return abort(404);
        }

        $this->product = $product;
    }
    public function render()
    {
        return view('livewire.client.product-detail', [
            'seller' => User::whereHas('roles', function ($query) {
                return $query->where('role_type', 'admin');
            })->first()
        ])->layout('layouts.user');
    }

    public function addToCart()
    {
        $user = auth()->user();
        if (!$user) {
            return $this->emit('showAlertError', [
                'msg' => 'Silahkan Login Terlebih Dahulu.',
                'redirect' => true,
                'path' => 'login'
            ]);
        }

        $whereData = [
            'user_id' => $user->id,
            'produk_id' => $this->product->id,
        ];

        $cart = Cart::where($whereData)->first();
        $qty = $cart ? $cart->qty : 0;

        if ($qty >= $this->product->stok) {
            return $this->emit('showAlertError', [
                'msg' => 'Stok Produk Tidak Cukup.',
            ]);
        }

        Cart::updateOrCreate($whereData, [
            'user_id' => $user->id,
            'produk_id' => $this->product->id,
            'qty' => $qty + 1
        ]);

        return $this->emit('showAlert', [
            'msg' => 'Product Berhasil Ditambah Kekeranjang.',
        ]);
    }

    public function startChat($user_id)
    {
        $chat = Chat::updateOrCreate(['user_id' => auth()->user()->id, 'seller_id' => $user_id]);
        return redirect(route('chat.detail', ['chat_id' => $chat->id]));
    }
}
