-- Contoh INSERT: menambahkan lowongan latihan dengan judul khusus.
INSERT INTO lowongan (judul, deskripsi, divisi, kuota, deadline, status, created_at, updated_at)
VALUES ('Contoh DML SIMAGANG', 'Data latihan DML yang dapat dihapus setelah praktikum.', 'Latihan SQL', 1, DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'buka', NOW(), NOW());

-- Contoh UPDATE: menutup lowongan latihan tersebut.
UPDATE lowongan
SET status = 'tutup', updated_at = NOW()
WHERE judul = 'Contoh DML SIMAGANG';

-- Contoh SELECT: membaca lowongan latihan berdasarkan judul.
SELECT id, judul, divisi, kuota, deadline, status
FROM lowongan
WHERE judul = 'Contoh DML SIMAGANG';

-- Contoh SELECT dengan JOIN: menampilkan data pendaftar, lowongan, dan statusnya.
SELECT u.name AS nama_pelamar, u.nim, l.judul AS judul_lowongan, p.status, p.skor
FROM pendaftaran AS p
INNER JOIN users AS u ON u.id = p.user_id
INNER JOIN lowongan AS l ON l.id = p.lowongan_id;

-- Contoh DELETE: menghapus hanya data latihan, bukan lowongan lain.
DELETE FROM lowongan
WHERE judul = 'Contoh DML SIMAGANG';