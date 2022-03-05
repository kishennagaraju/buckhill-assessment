<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrder extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'order_status_uuid' => 'required|uuid|exists:order_statuses,uuid',
            'payment_uuid' => 'required|uuid|exists:payments,uuid',
            'products' => 'required|array',
            'products.*.product' => 'required|uuid|exists:products,uuid',
            'products.*.quantity' => 'required|numeric',
            'address' => 'required',
        ];
    }
}
