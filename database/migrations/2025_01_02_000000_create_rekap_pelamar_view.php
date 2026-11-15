<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE VIEW v_rekap_pelamar AS
            SELECT
                p.id AS pendaftaran_id,
                u.id AS user_id,
                u.name AS nama,
                u.nim,
                u.email,
                u.no_hp,
                l.id AS lowongan_id,
                l.judul AS judul_lowongan,
                l.divisi,
                p.ipk,
                p.semester,
                p.pengalaman_organisasi,
                p.skor,
                p.status,
                p.catatan_admin,
                p.created_at AS tanggal_pendaftaran
            FROM pendaftaran AS p
            INNER JOIN users AS u ON u.id = p.user_id
            INNER JOIN lowongan AS l ON l.id = p.lowongan_id
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_rekap_pelamar');
    }
};
