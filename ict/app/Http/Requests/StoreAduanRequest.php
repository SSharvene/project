<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAduanRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            // complainant info (auto-filled but still validated)
            'nama_penuh' => 'required|string|max:255',
            'jawatan'    => 'nullable|string|max:255',
            'bahagian'   => 'nullable|string|max:255',
            'emel'       => 'nullable|email|max:255',
            'telefon'    => 'nullable|string|max:50',

            'tarikh_masa' => 'required|date',
            'jenis_masalah' => 'required|string|max:150',
            'jenis_peranti' => 'nullable|string|max:150',
            'jenama_model'  => 'nullable|string|max:200',
            'nombor_siri_aset' => 'nullable|string|max:200',

            'lokasi' => 'required|string|max:255',
            'lokasi_level' => 'nullable|in:Level 8,Level 9,Level 10',

            'penerangan' => 'nullable|string|max:2000',

            'attachments.*' => 'nullable|image|max:5120', // each file <= 5MB
        ];
    }

    public function messages()
    {
        return [
            'tarikh_masa.required' => 'Sila pilih tarikh & masa aduan.',
            'jenis_masalah.required' => 'Sila pilih jenis masalah.',
            'lokasi.required' => 'Sila masukkan lokasi peranti.',
            'attachments.*.image' => 'Lampiran mesti dalam format imej.',
            'attachments.*.max' => 'Setiap lampiran mestilah kurang daripada 5MB.',
        ];
    }
}
