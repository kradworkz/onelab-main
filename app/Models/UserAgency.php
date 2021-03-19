<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgency extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id','agency_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function main()
    {
        return $this->belongsTo('App\Models\Address', 'agency_id', 'id');
    }
}
