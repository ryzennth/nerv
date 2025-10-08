<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan semua user yang terautentikasi untuk membuat artikel
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
            'title'       => 'required|string|max:255|unique:articles,title',
            'content'     => 'required|string',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=700,min_height=450',
            'category_id' => 'required|exists:categories,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id', // Validasi setiap item di dalam array 'tags'
        ];
    }
}