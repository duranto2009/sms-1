<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LibrarianCreateRequest extends FormRequest
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
            "email"       =>"required|string|unique:librarians,email",
            "password"    =>"required|string",
            "phone"       =>"required|digits:11|unique:librarians,phone",
            "gender"      =>"required|string",
            "blood"       =>"required|string",
            "address"     =>"required|string",
            "image"       =>"required|mimes:png,jpg,gif,jpeg|max:2040"
        ];
    }
}
