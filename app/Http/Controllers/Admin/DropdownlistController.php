<?php

namespace App\Http\Controllers\Admin;

use App\Models\DropdownList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Requests\DropdownListRequest;

class DropdownlistController extends Controller
{   

    public function index($type)
    {
        $data = DropdownList::where('type',$type)->paginate(10);
        return DefaultResource::collection($data);
    }

    public function search(Request $request)
    {   
        $keyword = $request->input('keyword');
        $type = $request->post('type');

        $data = DropdownList::where('type',$type)
        ->where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%')
            ->orWhere('code', 'LIKE', '%'.$keyword.'%');
        })->paginate(10);

        return DefaultResource::collection($data);
    }

    public function lists($type){
        $data = DropdownList::where('type',$type)->get();
        return DefaultResource::collection($data);
    }

    public function store(DropdownListRequest $request)
    {
        if($request->input('update') == 'update') // Updating thru an ID
        { 
            $data = DropdownList::findOrFail($request->input('id'));
            $data->name = ucwords(strtolower($request->input('name')));
            ($request->input('code')) ? $data->code = strtoupper($request->input('code')) : '';
            ($request->input('percentage')) ? $data->percentage = $request->input('percentage') : '';
            $data->save();

        }else{

            $lists = $request->input('lists'); // Adding thru array
            if(!empty($lists)){
                foreach($lists as $list)
                {
                    $data = new DropdownList;
                    $data->name = ucwords(strtolower($list['name']));
                    $data->type = $request->input('type');
                    (!empty($list['code'])) ? $data->code = strtoupper($list['code']) : '';
                    (!empty($list['percentage']))  ? $data->percentage = $list['percentage'] : '';
                    $data->save();
                }
            }
        }

        return new DefaultResource($data);
    }

}
