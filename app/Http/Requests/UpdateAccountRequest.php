<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (User::find($this->route('user')->id)->id == auth()->user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'        => 'required|max:255',
            'last_name'         => 'required|max:255',
            'email'             => 'required|email',
            'new_password'      => 'confirmed',
            'current_password'  => ['sometimes', 'required', Password::defaults()],
        ];
    }
}
