<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email cannot be empty.',
            'email.email' => 'Email should be in the correct email format.',
            'email.exists' => 'The specified admin does not exist.',
            'password.required' => 'Password cannot be empty.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
