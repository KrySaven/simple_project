<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storePaymentRequest extends FormRequest
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
            'actual_date'   => 'date_format:d-m-Y|required',
            'pay_amount'    => 'required',
            'payment_type'  => 'required',
            'description'   => 'nullable|max:255',
        ];
    }
}
