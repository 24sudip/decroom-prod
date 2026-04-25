<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name'     => 'required|unique:roles|max:100',
            'role_note'     => 'nullable|string',
            'permissions'   => 'required|array',
            'permissions.*' => 'integer',
        ];
    }
}
