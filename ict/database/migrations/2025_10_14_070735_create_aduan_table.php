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
        Schema::create('aduans', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('nama_penuh')->nullable();
    $table->string('jawatan')->nullable();
    $table->string('bahagian')->nullable();
    $table->string('emel')->nullable();
    $table->string('telefon')->nullable();
    $table->timestamp('tarikh_masa')->nullable();
    $table->string('jenis_masalah')->nullable();
    $table->string('jenis_peranti')->nullable();
    $table->string('jenama_model')->nullable();
    $table->string('nombor_siri_aset')->nullable();
    $table->string('lokasi')->nullable();
    $table->string('lokasi_level')->nullable();
    $table->text('penerangan')->nullable();
    $table->text('attachments')->nullable(); // store json array of file paths
    $table->string('status')->default('Menunggu');
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aduan');
    }
};
