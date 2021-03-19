<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $table;

    protected $fillable = [
        'first_id', 'second_id', 'is_accepted', 'is_read', 'status'
    ];

    
    public function first()
    {
        return $this->belongsTo('App\Models\User', 'first_id', 'id');
    }

    public function second()
    {
        return $this->belongsTo('App\Models\User', 'second_id', 'id');
    }

    public function messages()
    {
        return $this->morphMany('App\Models\ChatMessage', 'messageable');
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
