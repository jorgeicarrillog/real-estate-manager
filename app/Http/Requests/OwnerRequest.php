<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerRequest extends FormRequest
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
        if(in_array('PUT', \Route::current()->methods)){
            $owner_id = \Route::current()->parameters['owner'];
        }else{
            $owner_id = null;
        }
        $email = $this->email;
        $organization_id = $this->organization_id;

        return [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'organization_id' => ['required', 'exists:organizations,id'],
            'email' => ['required', 'max:50', 'email', Rule::unique('owners')->ignore($owner_id)->where(function ($query) use($email,$organization_id) {
                return $query->where('email', $email)
                ->where('organization_id', $organization_id);
            })],
            'photo' => ['nullable', 'image'],
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'El propietario del correo ya existe en la inmobiliaria seleccionada.',
        ];
    }
}
