<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProducts extends FormRequest
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
            'category_uuid' => 'required|uuid|exists:categories,uuid',
            'uuid' => 'unique:products,uuid',
            'title' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'metadata' => 'required'
        ];
    }
}
