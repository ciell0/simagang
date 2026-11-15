-- Indeks status mempercepat penyaringan pendaftaran berdasarkan proses seleksi.
CREATE INDEX idx_pendaftaran_status ON pendaftaran (status);

-- Indeks lowongan_id mempercepat pencarian pendaftar per lowongan.
CREATE INDEX idx_pendaftaran_lowongan_id ON pendaftaran (lowongan_id);

-- users.nim sudah memiliki indeks unik dari migration create_users_table.
SHOW INDEX FROM users WHERE Column_name = 'nim';

-- View rekap menggabungkan profil pelamar, lowongan yang dipilih, dan hasil seleksi.
CREATE OR REPLACE VIEW v_rekap_pelamar AS
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
INNER JOIN lowongan AS l ON l.id = p.lowongan_id;