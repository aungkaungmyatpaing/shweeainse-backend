<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255|unique:brands,name,'
        ];

        if (request()->brand) {
            $rules = [
                'name' => 'required|max:255|unique:brands,name,' . request()->brand->id
            ];
        }

        return $rules;
    }
}
