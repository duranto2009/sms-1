<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('admin.permission');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"        => 'required',
            "email"       => 'required|email|unique:students,email,'.$this->student->id,
            "dob"         => 'required|date',
            "gender"      => 'required',
            "address"     => 'required',
            "phone"       => 'required|digits:11',
            "image"       => 'sometimes|mimes:png,jpg,jpeg,gif|max:2400'
        ];
    }
}
