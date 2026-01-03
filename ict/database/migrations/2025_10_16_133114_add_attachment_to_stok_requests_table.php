<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stok_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('stok_requests', 'attachment')) {
                $table->string('attachment')->nullable()->after('admin_note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_requests', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }
};
