<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokRequest extends Model
{
    protected $table = 'stok_requests';

    protected $fillable = [
        'stok_id',
        'requester_id',
        'requester_name',
        'jabatan',
        'purpose',
        'quantity',
        'status',
        'approved_quantity',
        'admin_note',
        'attachment',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'stok_id');
    }

    public function requester()
    {
        return $this->belongsTo(\App\Models\User::class, 'requester_id');
    }
}
