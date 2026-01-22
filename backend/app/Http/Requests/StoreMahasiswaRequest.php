<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nim' => ['required', 'string', 'unique:mahasiswa,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:mahasiswa,email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'prodi' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2099'],
            'status' => ['required', Rule::in(['aktif', 'cuti', 'lulus', 'dropout'])],
        ];
    }
}
