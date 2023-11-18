<?php

namespace App\Http\Requests\Users\User\Profile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PatchControllerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'age' => 'required|integer|min:0|max:100',
            'sex' => 'required|in:0,1,2',
            'blood_type' => 'required|string|in:A,B,AB,O',
            'birthday' => 'required|date_format:Y-m-d',
        ];
    }
}
