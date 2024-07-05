<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'required'],
            'last_name' => ['string', 'required'],
            'email' => ['email', 'required', 'unique:users,email,'.$this->user()->id],
            'password' => ['string', 'required', 'min:8', 'confirmed'],
            'address' => ['string', 'required'],
            'phone_number' => ['string', 'required'],
            'avatar' => ['string', 'exists:files,uuid'],
            'is_marketing' => ['boolean'],
        ];
    }
}
