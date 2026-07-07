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
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis');
            $table->string('kode_surat');
            $table->string('template_file')->nullable();
            $table->string('active_template_file')->nullable();
            $table->text('template_html')->nullable();
            $table->longText('template_json')->nullable();
            $table->string('penandatangan_nama')->nullable();
            $table->string('penandatangan_nip')->nullable();
            $table->string('penandatangan_jabatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
