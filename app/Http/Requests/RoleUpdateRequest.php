<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name'     => 'required',
            'role_note'     => 'nullable|string',
            'permissions'   => 'required|array',
            'permissions.*' => 'integer',
        ];
    }
}
