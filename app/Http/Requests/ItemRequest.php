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
            'spot.name' => 'required|string|max:255',
            'spot.body' => 'required|string',
            'spot.address' => 'required|string',
            'spot.access' => 'required|string',
            'spot.opendate' => 'required|string',
            'spot.closedate' => 'required|string',
            'spot.price' => 'required|string',
            'spot.site' => 'required|string',
            'spot.lat' => 'required|numeric',
            'spot.long' => 'required|numeric',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'spot.category_ids' => 'required|array',
            'spot.season_ids' => 'required|array',
            'spot.month_ids' => 'required|array',
        ];
    }
}
