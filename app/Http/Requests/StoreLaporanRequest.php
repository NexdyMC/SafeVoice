<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
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
            'jenis_bullying' => ['required', 'string', 'in:verbal,fisik,sosial,cyber_bullying'],
            'terlapor' => ['nullable', 'string', 'max:150'],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
            'lokasi_kejadian' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'min:10'],
            'is_anonim' => ['nullable', 'boolean'],
            'bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,mp4', 'max:10240'],
            'korban_id' => ['nullable', 'integer', 'exists:tb_siswa,id'],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jenis_bullying.required' => 'Jenis perundungan wajib dipilih.',
            'jenis_bullying.in' => 'Jenis perundungan yang dipilih tidak valid.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh melewati hari ini.',
            'lokasi_kejadian.required' => 'Lokasi kejadian wajib diisi.',
            'deskripsi.required' => 'Cerita/deskripsi kejadian wajib diisi.',
            'deskripsi.min' => 'Deskripsi kejadian minimal 10 karakter.',
            'bukti.max' => 'Ukuran berkas bukti maksimal 10 MB.',
            'bukti.mimes' => 'Format berkas bukti harus berupa gambar (JPG, PNG), dokumen PDF, atau video MP4.',
        ];
    }
}
