<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Agency;
use App\Models\Address;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\AgencyResource;
use App\Http\Resources\AgencyListResource;
use App\Http\Resources\AgencyAddressResource;
use App\Http\Requests\AgencyRequest;

class AgencyController extends Controller
{
    public function paginated($type)
    {
        $data = Agency::where('type_id',$type)->orderBy('id','DESC')->paginate(5);
        return AgencyResource::collection($data);
    }

    public function lists($type)
    {
        $data = Agency::where('type_id',$type)->orderBy('id','ASC')->get();
        return AgencyListResource::collection($data);
    }

    public function agencyaddress()
    {
        $data = Agency::where('type_id',1)->orderBy('id','DESC')->get();
        return AgencyAddressResource::collection($data);
    }

    public function search(Request $request)
    {   
        $keyword = $request->input('keyword');
        $type = $request->post('type');

        $data = Agency::where('type_id',$type)
        ->where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%')
            ->orWhere('acronym', 'LIKE', '%'.$keyword.'%');
        })->paginate(5);

        return AgencyResource::collection($data);
    }

    public function store(AgencyRequest $request)
    {
        // $user = $request->isMethod('put') ? User::findOrFail($request->input('id')) : new User;
        
        // if(!$request->isMethod('put')){
        //     $user->email = trim(strtolower($request->input('acronym')),'').'@onelab.com';
        //     $user->type = 'Agency';
        //     $user->password = bcrypt(123456789);
        // }
        
        // if($user->save()){

        $data = $request->isMethod('put') ? Agency::findOrFail($request->input('id')) : new Agency;
        $data->name = ucwords($request->input('name'));
        $data->acronym = strtoupper($request->input('acronym'));
        $data->code = strtoupper($request->input('code'));
        $data->website = strtolower($request->input('website'));
        $data->type_id = $request->input('type');
        // $data->user_id = $user->id; //Not deployed
        
        if($data->save()){
            if($request->isMethod('put'))
            {
                $data->address()->update([
                    'address' => ucwords($request->input('address')),
                    'region_id' =>$request->input('region'),
                    'province_id' =>$request->input('province'),
                    'municipality_id' =>$request->input('municipality'),
                ]);
            }else{
                $data->address()->create([
                    'address' => ucwords($request->input('address')),
                    'region_id' =>$request->input('region'),
                    'province_id' =>$request->input('province'),
                    'municipality_id' =>$request->input('municipality'),
                ]);
            }
            
        }
        

        return new AgencyResource($data);
    }

    public function regions()
    {
        $data = LocationRegion::orderBy('id','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function provinces($id)
    {
        $data = LocationProvince::where('region_id',$id)->orderBy('name','ASC')->get();
        return DefaultResource::collection($data);
    }

    public function municipalities($id)
    {
        $data = LocationMunicipality::where('province_id',$id)->orderBy('name','ASC')->get();
        return DefaultResource::collection($data);
    }
}
