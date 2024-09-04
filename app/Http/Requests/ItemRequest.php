<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'name' => 'required',
            'body' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'files.*.photo' => 'image|mimes:jpeg,bmp,png',
        ];
    }
}
