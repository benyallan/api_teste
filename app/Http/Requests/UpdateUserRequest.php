<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cpf' => ['required', 'string', 'max:14', 'unique:users,cpf,'.$this->user->id],
            'login' => ['required', 'string', 'max:255', 'unique:users,login,'.$this->user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->user->id],
        ];
    }
}
