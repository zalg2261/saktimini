<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mahasiswaId = $this->route('mahasiswa');
        $mahasiswa = \App\Models\Mahasiswa::find($mahasiswaId);
        
        return [
            'nim' => ['required', 'string', Rule::unique('mahasiswa', 'nim')->ignore($mahasiswaId)],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('mahasiswa', 'email')->ignore($mahasiswaId), Rule::unique('users', 'email')->ignore($mahasiswa?->email, 'email')],
            'password' => ['nullable', 'string', 'min:8'],
            'prodi' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:2099'],
            'status' => ['required', Rule::in(['aktif', 'cuti', 'lulus', 'dropout'])],
        ];
    }
}
