<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pinjaman extends Model
{
    // match the actual table name in your DB
    protected $table = 'pinjaman'; // or 'pinjaman' if you rename the table
    protected $fillable = [
        'user_id', 'asset_id', 'quantity', 'status', 'purpose', 'requested_at', 'approved_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
