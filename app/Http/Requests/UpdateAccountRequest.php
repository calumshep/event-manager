<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($this->route('user')->id === auth()->user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             =>
                ['required', 'string', 'max:255', 'email',
                Rule::unique('users')->ignore($this->route('user'))],
            'phone_number'      => 'nullable|string|',
            'new_password'      => ['nullable', 'confirmed', Password::defaults()],
            'current_password'  => 'required',
        ];
    }
}
