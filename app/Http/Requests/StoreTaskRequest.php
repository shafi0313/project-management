<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'project_id' => ['required', 'exists:projects,id'],
            'name'       => ['required', 'string', 'min:1', 'max:255'],
            'start_date'   => ['required', 'date'],
            'due_date'   => ['nullable', 'date'],
            'priority'   => ['required', 'string', 'in:low,medium,high'],
            'status'   => ['required'],
            'content'    => ['nullable'],
            'is_active'  => ['nullable', 'in:0,1']
        ];
    }
}
