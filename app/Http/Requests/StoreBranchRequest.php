<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
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
            'name_en'       => 'required',
            'name_kh'       => 'nullable|max:255',
            'owner_name_en' => 'nullable|max:255',
            'owner_name_kh' => 'nullable|max:255',
            'phone'         => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'email'         => 'nullable|email|max:255',
            'map'           => 'nullable|max:255',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'facebook'      => 'nullable|max:255',
            'line'          => 'nullable|max:255',
            'address'       => 'nullable|max:255',
            'description'   => 'nullable|max:255'
        ];
    }
}
