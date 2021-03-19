<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationMunicipality extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id', 'id');
    }

    public function address()
    {
        return $this->hasMany('App\Models\AgencyAddress', 'province_id');
    }
}
