<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FetchGithubUsers extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logins' => 'required|array|max:10'
        ];
    }

    /**
     * The custom messages
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'logins.required' => 'GitHub logins is required',
            'logins.array' => 'GitHub logins must be an array'
        ];
    }
}
