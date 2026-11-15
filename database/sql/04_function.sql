-- Menghapus fungsi lama agar definisi dapat dijalankan ulang.
DROP FUNCTION IF EXISTS fn_hitung_skor;

-- Fungsi menghitung skor sesuai bobot yang ditentukan pada spesifikasi.
CREATE FUNCTION fn_hitung_skor(
    p_ipk DECIMAL(3, 2),
    p_semester TINYINT UNSIGNED,
    p_pengalaman TINYINT UNSIGNED
)
RETURNS DECIMAL(8, 2)
DETERMINISTIC
NO SQL
RETURN (p_ipk * 20) + (p_semester * 2) + (p_pengalaman * 5);