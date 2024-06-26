<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'start'             => 'required|date|after:today',
            'end'               => 'nullable|date|after:start',
            'short_desc'        => 'required|string|max:255',
            'type'              => 'required|string|max:255',
            'special_requests'  => 'nullable|string|max:255',
            'org'               => 'required|exists:App\Models\Organisation,id',
        ];
    }
}
