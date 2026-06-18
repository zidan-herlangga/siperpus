<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title'      => 'sometimes|string|max:255',
            'cover_image'=> 'nullable|string',
            'author'     => 'sometimes|string|max:255',
            'publisher'  => 'sometimes|string|max:255',
            'year'       => 'sometimes|integer|digits:4',
            'isbn'       => 'nullable|string|unique:books,isbn,' . $this->id,
            'category_id' => 'sometimes|integer|exists:categories,id',
            'synopsis'   => 'nullable|string',
            'shelf_code' => 'sometimes|string|max:50',
            'stock'      => 'sometimes|integer|min:0',
        ];
    }

}
