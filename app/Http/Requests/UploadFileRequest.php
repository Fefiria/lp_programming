<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
                'max:2048'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File wajib diunggah.',
            'file.file'     => 'Format yang diunggah harus berupa file.',
            'file.mimes'    => 'Format file harus berupa: jpg, jpeg, png, pdf, doc, docx, xls, atau xlsx.',
            'file.max'      => 'Ukuran file tidak boleh lebih dari 2 MB.',
        ];
    }
}
