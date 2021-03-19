<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroupConversation extends Model
{
    use HasFactory;

    protected $table;

    protected $fillable = [
        'name', 'status'
    ];

    public function messages()
    {
        return $this->morphMany('App\Models\ChatMessage', 'messageable');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'user_id');
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
