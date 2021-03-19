<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SampleTestRequest extends FormRequest
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
        return [
            'name' => 'required|string|unique:list_sample_tests,name,NULL,id,laboratory_id,'.$this->laboratory_id.',type,'.$this->type,
            'type' => 'required|string',
            'laboratory_id' => 'required|integer'
        ];
    }
}
