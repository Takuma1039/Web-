<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('get')) return [];
        
        return [
            'title' => 'required|max:50',
            'comment' => 'required|string|max:500',
            'review' => 'required|numeric|between:1,5',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,webp,avif,AVIF|max:2048',
            'new_image_names.*' => 'nullable|string|max:255', // 画像名のバリデーション
            'is_anonymous' => 'nullable|boolean',
            'image_ids.*' => 'nullable|integer|exists:review_images,id', // 画像IDのバリデーション
        ];
    }
}
