<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class StorePublicHolidayRequest extends FormRequest
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
            'branch_id'  => 'required', 
            'name_en'    => 'required|max:255',
            'name_kh'    => 'nullable|max:255',
            'from_date'  => 'required|date|date_format:Y-m-d',
            'to_date'    => 'nullable|date|date_format:Y-m-d|after:from_date',
            'note'       => 'nullable|max:255',
        ];
    }
    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'branch_id'  => trans('app.branch'),
            'name_en'    => trans('app.holiday_name_en'),
            'name_kh'    => trans('app.holiday_name_kh'),
            'from_date'  => trans('app.from_date'),
            'to_date'    => trans('app.to_date'),
            'note'       => trans('app.note'),
        ];
    }

}
