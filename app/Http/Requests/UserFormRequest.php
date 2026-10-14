<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('user');

        if ($this->isMethod('post')) {
            return [
                'name' => 'required|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'role' => 'required|in:Admin,User',
            ];
        }

        return [
            'name' => 'required|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|min:8',
            'role' => 'required|in:Admin,User',
        ];
    }
}
