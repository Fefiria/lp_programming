<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DivisionCreateRequest extends FormRequest
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
            'name' => [
                'required',
                'max:100'
            ],
            'description' => [
                'required',
            ],
            'id_division' => [
                'required',
            ],
            'status' => [
                'required',
            ],
            'logo_url' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi !',
            'name.max' => 'jumlah karakter maksimal 100',
            'description.required'     => 'Deskripsi wajib di isi !',
            'file.mimes'    => 'Format file harus berupa: jpg, jpeg, png, pdf, doc, docx, xls, atau xlsx.',
            'file.max'      => 'Ukuran file tidak boleh lebih dari 2 MB.',
        ];
    }
}
