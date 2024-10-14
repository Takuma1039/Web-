<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanpostRequest extends FormRequest
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
        return [
            'planpost.title' => 'required|string|max:255',
            'planpost.comment' => 'required|string|max:300',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,AVIF,avif|max:2048',
        ];
    }
}
