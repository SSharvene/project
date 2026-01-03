<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('stok_id')->nullable()->constrained('stoks')->nullOnDelete();
    $table->unsignedBigInteger('requester_id')->nullable();
    $table->string('requester_name')->nullable();
    $table->string('jabatan')->nullable();
    $table->text('purpose')->nullable();
    $table->integer('quantity')->default(1);
    $table->string('status')->default('pending'); // pending / approved / rejected
    $table->integer('approved_quantity')->nullable();
    $table->text('admin_note')->nullable();
    $table->unsignedBigInteger('handled_by')->nullable();
    $table->timestamp('handled_at')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_requests');
    }
};
