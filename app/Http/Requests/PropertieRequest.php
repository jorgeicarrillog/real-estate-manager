<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertieRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'owner_id' => ['required', 'exists:owners,id'],
            'properties_type_id' => ['required', 'exists:properties_types,id'],
            'citie_id' => ['required', 'exists:cities,id'],
            'title' => ['required', 'max:250'],
            'description' => ['required', 'max:1250'],
            'address' => ['nullable', 'max:250'],
            'area' => ['nullable','numeric'],
            'bedrooms' => ['required','integer'],
            'toilets' => ['required','integer'],
            'value' => ['required','integer', 'min:1'],
            'floor' => ['nullable','integer'],
            'photo' => ['nullable', 'image'],
        ];
    }
}
