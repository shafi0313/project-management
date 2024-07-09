<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'job_name' => ['required', 'string', 'min:1', 'max:255'],
            'job_description' => ['nullable'],
            'start_date' => ['required', 'date'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'integer', 'min:1', 'max:127'],
            'user_id' => ['required', 'array'],
        ];
    }
}
