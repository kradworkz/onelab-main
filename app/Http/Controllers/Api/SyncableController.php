<?php

namespace App\Http\Controllers\Api;


use App\Models\Agency;
use App\Models\DropdownList;
use App\Models\ListTestService;
use App\Models\ListSampleTest;
use App\Models\ListMethodRef;
use App\Models\ListPackage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PublicAgencyResource;
use App\Http\Resources\PublicAgencyTypeResource;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\ts\TestServiceResource;
use App\Http\Resources\ts\PackagesResource;

class SyncableController extends Controller
{
    public function agencies($type)
    {
        $data = Agency::where('type_id',$type)->where('status','Active')->get();
        return PublicAgencyResource::collection($data);
    }

    public function agencytypes()
    {
        $data = DropdownList::where('type','Agency Type')->get();
        return PublicAgencyTypeResource::collection($data);
    }
    
    public function dropdownlists($type)
    {
        $data = DropdownList::where('type', $type)->get();
        return DefaultResource::collection($data);
    }

    public function sampletests()
    {
        $data = ListSampleTest::orderBy('id','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function methodreferences()
    {
        $data = ListMethodRef::orderBy('id','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function testservices()
    {
        $data = ListTestService::orderBy('id','ASC')->get();
        return TestServiceResource::collection($data);
    }

 public function packages()
    {
        $data = ListPackage::orderBy('id','ASC')->get();
        return PackagesResource::collection($data);
    }
}
