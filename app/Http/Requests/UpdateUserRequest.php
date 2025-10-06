<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userToUpdate = $this->route('user');
        return $this->user()->can('update', $userToUpdate);
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'filled|string|max:255',
            'username' => ['filled', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users')->ignore($userId)],
            'email' => ['filled', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_active' => 'required|boolean',
            'verified_email' => 'required|boolean',
            'roles' => 'required|array',
        ];
    }

    public function getValidatedData(): array
    {
        $data = $this->validated();
        
        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'is_active' => $data['is_active'],
            'email_verified_at' => $data['verified_email'] ? now() : null,
        ];
        
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        return $updateData;
    }
}