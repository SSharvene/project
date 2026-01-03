<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stoks';

    protected $fillable = [
        'nama','kod','kategori','kuantiti','lokasi','nota'
    ];

    public function requests()
    {
        return $this->hasMany(StokRequest::class, 'stok_id');
    }
}
