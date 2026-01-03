<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    protected $table = 'aduans'; // adjust if different
    protected $fillable = [
        'user_id','nama_penuh','jawatan','bahagian','emel','telefon',
        'tarikh_masa','jenis_masalah','jenis_peranti','jenama_model','nombor_siri_aset',
        'lokasi','lokasi_level','penerangan','attachments','status'
    ];

    protected $casts = [
        'tarikh_masa' => 'datetime',
        'attachments' => 'array',
    ];

    // relation to user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
