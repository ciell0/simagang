<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('divisi');
            $table->unsignedInteger('kuota');
            $table->date('deadline');
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->timestamps();
        });

        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lowongan_id')->constrained('lowongan')->cascadeOnDelete();
            $table->string('cv_path');
            $table->decimal('ipk', 3, 2);
            $table->unsignedTinyInteger('semester');
            $table->unsignedTinyInteger('pengalaman_organisasi');
            $table->decimal('skor', 8, 2)->nullable();
            $table->enum('status', ['diproses', 'diterima', 'ditolak'])->default('diproses');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lowongan_id']);
        });

        Schema::create('log_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->string('status_lama', 20);
            $table->string('status_baru', 20);
            $table->timestamp('diubah_pada')->useCurrent();
        });

        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('method', 10);
            $table->unsignedSmallInteger('status_code');
            $table->unsignedInteger('durasi_ms');
            $table->timestamp('created_at')->useCurrent()->index();
        });

        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat', 20);
            $table->text('pesan');
            $table->unsignedInteger('jumlah_pengulangan')->default(0);
            $table->timestamp('terakhir_dikirim')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
        Schema::dropIfExists('request_logs');
        Schema::dropIfExists('log_status');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('lowongan');
    }
};
