<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:' . User::USUARIO . ',' . User::ADMINISTRADOR,
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El formato del email no es el correcto',
            'password.required' => 'El password es obligatorio',
            'password.min' => 'El mínimo para el password son 6 caracteres',
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol debe ser: usuario o administrador',
        ];
    }
}
