<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class storeLoanRequest extends FormRequest
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
    public function rules():array
    {
        return [
            
         
            'collateral_id'  => 'required',
            'branch_id'             => 'required',
            'customer'              => 'required',
            // 'dealer'             => 'nullable',
            'co_user'               => 'required',
            'guarantor'             => 'nullable',
            'currency_type'         => [Rule::in('riel')],
            'contract_type'         => ['required',Rule::in('contract_1','contract_2')],
            'loan_amount'           => 'required',
            'interest'              => 'required',
            'loan_term'             => 'required',
            'loan_type'             => 'required',
            'operation_fee'         => 'required',
            // 'operation_fee_amount'  => 'required',
            'sale_date'             => 'required',
            'first_payment_date'    => 'required',
            'duration_type'         => 'required',
            'description'           => 'nullable|max:255',
            'relation'              => 'sometimes|required_with:guarantor',
            'other_relation'        => "required_if:relation,other",
           
            // 'first_payment'         => 'required'
        ];
    }
    public function attributes()
    {
      
        return [
            'collateral_id'         => 'Collateral',
            'branch_id'             => trans('app.branch'),
            'customer'              => trans('app.customer'),
            // 'dealer'             => trans('app.dealer'),
            'co_user'               => trans('app.co_user'),
            'guarantor'             => trans('app.guarantor'),
            'currency_type'         => trans('app.currency_type'),
            'contract_type'         => trans('app.contract_type'),
            'loan_amount'           => trans('app.loan_amount'),
            'interest'              => trans('app.interest'),
            'loan_term'             => trans('app.loan_term'),
            'loan_type'             => trans('app.loan_type'),
            'operation_fee'         => trans('app.operation_fee'),
            'sale_date'             => trans('app.sale_date'),
            'first_payment_date'    => trans('app.first_payment_date'),
            'duration_type'         => trans('app.duration_type'),
            'description'           => trans('app.description'),
            'relation'              => trans('app.relation'),
            'other_relation'        => trans('app.other_relation'),
            // 'first_payment'         => 'First Payment',
        ];
    }
}
