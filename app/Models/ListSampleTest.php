<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListSampleTest extends Model
{
    use HasFactory;

    public function laboratory()
    {
        return $this->belongsTo('App\Models\DropdownList', 'laboratory_id', 'id');
    }

    public function testname()
    {
        return $this->hasMany('App\Models\ListSampleTest', 'sampletype_id');
    }

    public function sample()
    {
        return $this->hasMany('App\Models\ListTestService', 'sampletype_id');
    }

    public function test()
    {
        return $this->hasMany('App\Models\ListTestService', 'testname_id');
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
