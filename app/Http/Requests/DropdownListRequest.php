<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DropdownListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(!empty($this->lists)){
            $rules = [
                'type' => 'required|string',
            ];

            foreach($this->lists as $key => $staff) {
                if ( array_key_exists('id', $staff) && $staff['id'] ) { // if have an id, means an update, so add the id to ignore
                    $rules = array_merge($rules, ['lists.'.$key.'.name' => 'required|unique:dropdown_lists,name,'.$staff['id']]);
                    $rules = array_merge($rules, ['lists.'.$key.'.code' => 'sometimes|required|unique:dropdown_lists,code,'.$staff['id']]);
                    $rules = array_merge($rules, ['lists.'.$key.'.percentage' => 'sometimes|required|numeric|max:100']);
                } else {  // just check if the email it's not unique
                    $rules = array_merge($rules, ['lists.'.$key.'.name' => 'required|unique:dropdown_lists,name']);
                    $rules = array_merge($rules, ['lists.'.$key.'.code' => 'sometimes|required|unique:dropdown_lists,code']);
                    $rules = array_merge($rules, ['lists.'.$key.'.percentage' => 'sometimes|required|numeric|max:100']);
                }
            }
        }else{
            $rules = [
                'name' => 'required|unique:dropdown_lists,name,'.$this->id,
                'code' => 'sometimes|required|unique:dropdown_lists,code,'.$this->id,
                'percentage' => 'sometimes|required|numeric|max:100',
            ];
        }

        return $rules;
    }
}
