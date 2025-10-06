<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/|unique:users',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_active' => 'required|boolean',
            'verified_email' => 'required|boolean',
            'roles' => 'required|array',
        ];
    }

    /**
     * Menyiapkan data yang sudah tervalidasi sebelum digunakan.
     */
    public function getValidatedData(): array
    {
        $data = $this->validated();
        
        return [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'],
            'email_verified_at' => $data['verified_email'] ? now() : null,
        ];
    }
}