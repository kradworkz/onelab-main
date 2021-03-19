<?php

namespace App\Http\Controllers\Api;

use App\Models\Request as LabRequest;
use App\Models\ListSampleTest;
use App\Models\ListTestService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Api\SampletypeResource;

class ReferralController extends Controller
{
    public function check(){
        response()->json(['success' => 'success'], 200);
    }

    public function getSampletypes(Request $request)
    {
        $keyword = $request->input('word');
        $laboratory = $request->input('laboratory');
        $agency = $request->input('agency');

        // $data = ListSampleTest::where('name','like','%'.$keyword.'%')->where('name','!=','-')->where()->where('laboratory_id',$laboratory)->where('type','Sampletype')->take(3)->get();
        // return DefaultResource::collection($data);

        $data = ListTestService::where('agency_id','!=',$agency)
        ->whereHas('sampletype',function ($query) use ($keyword,$laboratory) {
            $query->where('name', 'LIKE', '%'.$keyword.'%')
            ->where('laboratory_id', $laboratory);
        })->take(3)->get();

        return SampletypeResource::collection($data);
    }

    public function store(Request $request)
    {
        $data = new LabRequest;
        $data->reference = $request->input('reference');
        $data->content = json_encode($request->input('content'));
        $data->from_id = $request->input('from');
        $data->to_id = $request->input('to');
        $data->save();
    }
}
