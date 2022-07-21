<?php

namespace App\Http\Livewire\Chat;

use App\Models\Chat;
use App\Models\ChatDetail as ModelsChatDetail;
use Livewire\Component;

class ChatDetail extends Component
{
    public $chat_id;
    public $chat;
    public $pesan;
    public function mount($chat_id)
    {
        $this->chat_id = $chat_id;
        $this->chat = Chat::find($chat_id);
    }
    public function render()
    {
        return view('livewire.chat.chat-detail', [
            'chats' => ModelsChatDetail::with('childrens')->where('chat_id', $this->chat_id)->whereNull('parent_id')->get()
        ]);
    }

    public function send()
    {
        $this->validate(['pesan' => 'required']);
        $role = auth()->user()->role->role_type;
        $sender_id = $role == 'admin' ? $this->chat->seller_id : $this->chat->user_id;
        $receiver_id = $role == 'admin' ? $this->chat->user_id : $this->chat->seller_id;

        $parent_id = null;
        $parent = ModelsChatDetail::where('chat_id', $this->chat_id)->orderBy('created_at', 'DESC')->first();

        if ($parent) {
            if ($role == 'member') {
                if ($parent->sender_id == $this->chat->user_id) {
                    if (!$parent->parent_id) {
                        $parent_id = $parent->id;
                    } else {
                        $parent_id = $parent->parent_id;
                    }
                }
            } else if ($role == 'admin') {
                if ($parent->sender_id == $this->chat->seller_id) {
                    if (!$parent->parent_id) {
                        $parent_id = $parent->id;
                    } else {
                        $parent_id = $parent->parent_id;
                    }
                }
            }
        }

        ModelsChatDetail::create([
            'chat_id' => $this->chat_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'parent_id' => $parent_id,
            'pesan' => $this->pesan,
        ]);

        $parent_id = null;
        $this->pesan = null;
    }
}
