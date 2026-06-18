<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title'      => 'required|string|max:255',
            'cover_image'=> 'nullable|string',
            'author'     => 'required|string|max:255',
            'publisher'  => 'required|string|max:255',
            'year'       => 'required|integer|digits:4',
            'isbn'       => 'nullable|string|unique:books,isbn',
            'category_id' => 'required|integer|exists:categories,id',
            'synopsis'   => 'nullable|string',
            'shelf_code' => 'required|string|max:50',
            'stock'      => 'required|integer|min:0',
        ];
    }

}
