<?php

namespace App\Http\Requests;

use App\Rules\StorageFileExists;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:32',
            'surname' => 'nullable|string|min:3|max:32',
            'date_of_birth' => 'nullable|date',
            'avatar_path' => ['nullable', new StorageFileExists],
            'remove_avatar' => 'nullable|boolean',
        ];
    }
}
