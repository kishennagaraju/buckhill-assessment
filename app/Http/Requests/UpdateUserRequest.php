<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|exists:users,email',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
            'address' => 'required',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\.\(\)]*)$/|min:10',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First Name cannot be empty.',
            'last_name.required' => 'Last Name cannot be empty.',
            'email.required' => 'Email cannot be empty.',
            'email.unique' => 'Email Already Exists.',
            'email.email' => 'Email should be in the correct email format.',
            'password.required' => 'Password cannot be empty.',
            'password.min' => 'Password should be of minimum 8 characters.',
            'password.required_with' => 'Password and Password Confirmation values should be same.',
            'address.required' => 'Address cannot be empty.',
            'phone_number.required' => 'Phone Number cannot be empty.',
            'phone_number.regex' => 'Phone Number should be in the correct format.',
            'phone_number.min' => 'Phone Number should be of minimum 10 digits.',
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
