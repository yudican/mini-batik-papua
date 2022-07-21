<?php

namespace App\Http\Livewire\Chat;

use App\Models\Chat as ModelsChat;
use Livewire\Component;

class Chat extends Component
{
    public function render()
    {
        $role = auth()->user()->role->role_type;
        $chat_lists = ModelsChat::where($role == 'admin' ? 'seller_id' : 'user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();
        return view('livewire.chat.chat', [
            'chats' => $chat_lists,
            'role' => $role
        ]);
    }
}
