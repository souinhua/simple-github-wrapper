<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'name' => 'required|max:255',
            'password' => 'required|min:8|max:255'
        ];
    }

    /**
     * The error messages
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'email.required' => "Your unique email is required.",
            'email.unique' => "The email you're trying to register is already in use.",
            'email.email' => "The email is invalid",
        ];
    }
}
