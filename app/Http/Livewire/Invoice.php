<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class Invoice extends Component
{
    public $order;
    public function mount($order_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return abort(404);
        }
        $this->order = $order;
    }
    public function render()
    {
        return view('livewire.invoice');
    }
}
