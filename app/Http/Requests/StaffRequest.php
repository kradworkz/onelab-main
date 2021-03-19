<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
        // return [
        //     'firstname' => 'required|max:100',
        //     'lastname' => 'required|max:100',
        //     'type' => 'required|max:100',
        //     'agency' => 'required_if:type,!=,Secretary|integer',
        //     'birthday' => 'required',
        //     'gender' => 'required|max:100',
        //     'email' => 'required|string|max:150|unique:users,email,'.$this->id,
        //     'avatar' => 'nullable|image64:jpeg,jpg',
        // ];

        $rules = [
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'type' => 'required|max:100',
            'birthday' => 'required',
            'gender' => 'required|max:100',
            'email' => 'required|string|max:150|unique:users,email,'.$this->id,
            'avatar' => 'nullable|image64:jpeg,jpg',
        ];

        if ($this->type == "Secretary" || $this->type == "" ) {
            $rules['agency'] = 'nullable|integer';
        }else{
            $rules['agency'] = 'required|integer';
        }

        return $rules;
    }
}
