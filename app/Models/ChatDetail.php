<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the sender that owns the ChatDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver that owns the ChatDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the childrens that owns the ChatDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function childrens()
    {
        return $this->hasMany(ChatDetail::class, 'parent_id');
    }
}
