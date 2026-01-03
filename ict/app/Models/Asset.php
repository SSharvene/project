<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
  'nama_aset','kategori','jenama','no_siri','keterangan','bilangan','status','gambar'
];

}
