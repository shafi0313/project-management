<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'email' => ['required', 'string', 'min:1', 'max:64', 'unique:users,email,' . $this->admin_user->id . 'id'],
            'user_name' => ['nullable', 'string', 'min:1', 'max:32', 'unique:users,user_name,' . $this->admin_user->id . 'id'],
            'gender' => ['required', 'integer', 'in:1,2,3'],
            'mobile' => ['required', 'phone:BD'],
            'address' => ['required', 'string', 'min:1', 'max:191'],
            'section_id' => ['required', 'exists:sections,id'],
            'sub_section_id' => ['nullable', 'exists:sub_sections,id'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,JPG,png,webp,svg'],
        ];
    }
}
