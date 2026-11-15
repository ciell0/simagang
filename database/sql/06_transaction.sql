-- Contoh transaksi berhasil: ganti angka 0 dengan ID lowongan uji yang memiliki kuota.
START TRANSACTION;

-- Mengurangi kuota hanya jika lowongan uji ditemukan dan kuotanya masih tersedia.
UPDATE lowongan
SET kuota = kuota - 1, updated_at = NOW()
WHERE id = 0 AND kuota > 0;

-- Menetapkan perubahan transaksi.
COMMIT;

-- Contoh transaksi batal: ganti angka 0 dengan ID lowongan uji.
START TRANSACTION;

-- Perubahan ini dibatalkan pada perintah ROLLBACK berikutnya.
UPDATE lowongan
SET status = 'tutup', updated_at = NOW()
WHERE id = 0;

-- Membatalkan semua perubahan sejak START TRANSACTION terakhir.
ROLLBACK;