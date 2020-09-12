<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherCreateRequest extends FormRequest
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
            "name"        =>"required|string",
            "email"       =>"required|string|unique:teachers,email",
            "password"    =>"required|string",
            "designation" =>"required|string",
            "department"  =>"required|string",
            "phone"       =>"required|digits:11|unique:teachers,phone",
            "gender"      =>"required|string",
            "blood"       =>"required|string",
            "facebook"    =>"required|string",
            "twitter"     =>"required|string",
            "linkedin"    =>"required|string",
            "address"     =>"required|string",
            "about"       =>"required|string",
            "show_web"    =>"required|string",
            "image"       =>"required|mimes:png,jpg,gif,jpeg|max:2040"
        ];
    }
}
