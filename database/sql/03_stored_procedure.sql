-- Menghapus prosedur lama agar definisi dapat diperbarui dengan aman.
DROP PROCEDURE IF EXISTS sp_terima_pelamar;

-- Mengatur pembatas agar titik koma di dalam badan prosedur tidak mengakhiri definisi.
DELIMITER //

-- Prosedur menerima satu pendaftar dan mengurangi kuota dalam koneksi/transaksi pemanggil.
CREATE PROCEDURE sp_terima_pelamar(IN p_id BIGINT UNSIGNED)
BEGIN
    DECLARE v_status VARCHAR(20) DEFAULT NULL;
    DECLARE v_kuota INT UNSIGNED DEFAULT NULL;

    SELECT p.status, l.kuota
    INTO v_status, v_kuota
    FROM pendaftaran AS p
    INNER JOIN lowongan AS l ON l.id = p.lowongan_id
    WHERE p.id = p_id
    FOR UPDATE;

    IF v_status IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pendaftaran tidak ditemukan.';
    END IF;

    IF v_status <> 'diproses' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pendaftaran bukan berstatus diproses.';
    END IF;

    IF v_kuota IS NULL OR v_kuota < 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kuota lowongan sudah habis.';
    END IF;

    UPDATE pendaftaran
    SET status = 'diterima', updated_at = NOW()
    WHERE id = p_id;

    UPDATE lowongan AS l
    INNER JOIN pendaftaran AS p ON p.lowongan_id = l.id
    SET l.kuota = l.kuota - 1, l.updated_at = NOW()
    WHERE p.id = p_id;
END//

-- Mengembalikan pembatas ke titik koma untuk perintah berikutnya.
DELIMITER ;