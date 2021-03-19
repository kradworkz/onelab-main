<?php

namespace App\Http\Controllers\Admin;

use App\Models\ListTestService;
use App\Models\ListMethodRef;
use App\Models\ListSampleTest;
use App\Models\ListPackage;
use App\Models\ListPackageList;
use App\Models\Dropdownlist;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\ts\MethodReferenceResource;
use App\Http\Resources\ts\TestServiceResource;
use App\Http\Resources\ts\PackageResource;
use App\Http\Requests\MethodRefRequest;
use App\Http\Requests\PackageRequest;
use App\Http\Requests\SampleTestRequest;

class TestserviceController extends Controller
{
   

    public function paginated($id,$agency)
    {
        $data = ListTestService::where('sampletype_id',$id)->where('agency_id',$agency)->orderBy('id','DESC')->paginate(10);
        return TestServiceResource::collection($data);
    }

    public function store(Request $request)
    {
        $data = $request->isMethod('put') ? ListTestService::findOrFail($request->input('id')) : new ListTestService;
        $data->agency_id = $request->input('agency');
        $data->sampletype_id = $request->input('sampletype');
        $data->testname_id = $request->input('testname');
        $data->method_id = $request->input('method');
        $data->reference_id = $request->input('reference');
        $data->fee = $request->input('fee');
        $data->save();

        return new MethodReferenceResource($data);
    }


    /////////////////////////
    //// Sample Testname ////
    /////////////////////////

    public function sampletest(SampleTestRequest $request)
    {   
        $data = $request->isMethod('put') ? ListSampleTest::findOrFail($request->input('id')) : new ListSampleTest;
        $data->name = ucwords($request->input('name'));
        $data->type = $request->input('type');
        $data->laboratory_id = $request->input('laboratory_id');
        $data->save();

        return new DefaultResource($data);
    }
    

    public function sampletestsearch(Request $request)
    {   
        $type= $request->input('type');
        $keyword = $request->input('word');
        $lab_id = $request->input('lab_id');

        $data = ListSampleTest::where('name', '!=','-')->where('laboratory_id',$lab_id)->where('type',$type)->where('name', 'LIKE', '%'.$keyword.'%')->orderBy('created_at','ASC')->take(5)->get();
        return DefaultResource::collection($data);
    }

    /////////////////////////
    //// Metho Reference ////
    /////////////////////////
    
    public function methodreference(MethodRefRequest $request)
    {   
        $data = $request->isMethod('put') ? ListMethodRef::findOrFail($request->input('id')) : new ListMethodRef;
        $data->name = ucwords($request->input('name'));
        $data->type = $request->input('type');
        $data->laboratory_id = $request->input('laboratory_id');
        $data->save();

        return new DefaultResource($data);
    }

    public function methodreferencesearch(Request $request)
    {   
        $type= $request->input('type');
        $keyword = $request->input('word');
        $lab_id = $request->input('laboratory_id');

        $data = ListMethodRef::where('name', '!=','-')->where('laboratory_id',$lab_id)->where('type',$type)->where('name', 'LIKE', '%'.$keyword.'%')->orderBy('created_at','ASC')->take(2)->get();
        return DefaultResource::collection($data);
    }

    public function methods($id,$agency,$sampletype)
    {
        $data = ListTestService::where('testname_id',$id)->where('agency_id',$agency)->where('sampletype_id',$sampletype)->orderBy('created_at','ASC')->get();
        return MethodReferenceResource::collection($data);
    }

    /////////////////////
    //// Test Services ////
    /////////////////////

    public function testservices($id,$agency)
    {
        $data = ListTestService::where('sampletype_id',$id)->where('agency_id',$agency)->orderBy('created_at','ASC')->get();
        return TestServiceResource::collection($data);
    }

    public function packages($lab_id,$agency)
    {
        // $lab = ListSampleTest::where('laboratory_id',$lab_id)->where('type','Sampletype')->select('id')->get();
    
        $data = ListPackage::where('sampletype_id',$lab_id)->where('agency_id',$agency)->orderBy('created_at','ASC')->paginate(5);
        return PackageResource::collection($data);
    }

    public function addons($lab_id,$agency)
    {
        // $lab = ListSampleTest::where('laboratory_id',$lab_id)->where('type','Sampletype')->select('id')->get();
    
        $data = ListTestService::where('sampletype_id',1)->where('testname_id',1)->where('agency_id',$agency)->orderBy('created_at','ASC')->paginate(5);
        return TestServiceResource::collection($data);
    }

    public function package(PackageRequest $request)
    {
        $data = $request->isMethod('put') ? ListPackage::findOrFail($request->input('id')) : new ListPackage;
        $data->name = ucwords($request->input('name'));
        $data->fee = $request->input('fee');
        $data->agency_id = $request->input('agency');
        $data->sampletype_id = $request->input('sampletype');
        
        if($data->save()){
            if(!$request->isMethod('put')){
                $testservices = $request->input('testservices');
                if(!empty($testservices)){
                    foreach($testservices as $testservice)
                    {
                        $package = new ListPackageList;
                        $package->package_id = $data->id;
                        $package->testservice_id = $testservice;
                        $package->save();
                    }
                }
            }
        }

        return new PackageResource($data);
    }
}
