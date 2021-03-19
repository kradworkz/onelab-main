<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgencyRequest extends FormRequest
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
            'name' => 'required|string|unique:agencies,name,'.$this->id,
            'acronym' => 'required|string|max:20|unique:agencies,acronym,'.$this->id,
            'code' => 'required|string|max:10|unique:agencies,code,'.$this->id,
            'website' => 'required|string|max:100|unique:agencies,website,'.$this->id,
            'type' => 'required|integer',
            'region' => 'required|integer',
            'province' => 'required|integer',
            'municipality' => 'required|integer',
            'address' => 'required|string|max:200',
        ];
    }
}
