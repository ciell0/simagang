-- Menghapus trigger lama sebelum membuat definisi terbaru.
DROP TRIGGER IF EXISTS trg_log_status;

-- Mengatur pembatas untuk badan trigger.
DELIMITER //

-- Mencatat perubahan status pendaftaran ke log_status secara otomatis.
CREATE TRIGGER trg_log_status
AFTER UPDATE ON pendaftaran
FOR EACH ROW
BEGIN
    IF OLD.status <> NEW.status THEN
        INSERT INTO log_status (pendaftaran_id, status_lama, status_baru, diubah_pada)
        VALUES (NEW.id, OLD.status, NEW.status, NOW());
    END IF;
END//

-- Mengembalikan pembatas ke titik koma untuk perintah berikutnya.
DELIMITER ;