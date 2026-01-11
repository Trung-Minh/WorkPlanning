<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TẠO CÁC BẢNG (Theo thứ tự để không lỗi Khóa ngoại)
        DB::unprepared("
            CREATE TABLE NGUOI_DUNG_CA_NHAN (
                ID_USER CHAR(8) PRIMARY KEY,
                MAT_KHAU VARCHAR(100) NOT NULL,
                HO_TEN VARCHAR(100) NOT NULL,
                NGAY_SINH DATE,
                GIOI_TINH VARCHAR(10),
                EMAIL VARCHAR(100) NOT NULL UNIQUE,
                AVATAR VARCHAR(200),
                ANH_BIA VARCHAR(200),
                MO_TA TEXT
            ) ENGINE=InnoDB;

            CREATE TABLE NHOM_LAM_VIEC (
                ID_NHOM CHAR(8) PRIMARY KEY,
                ID_NHOM_TRUONG CHAR(8),
                NGAY_TAO DATETIME,
                MO_TA_NHOM VARCHAR(255),
                AVATAR_NHOM VARCHAR(200),
                TEN_NHOM VARCHAR(100),
                FOREIGN KEY(ID_NHOM_TRUONG) REFERENCES NGUOI_DUNG_CA_NHAN(ID_USER)
            ) ENGINE=InnoDB;

            CREATE TABLE KE_HOACH (
                ID_KH CHAR(8) PRIMARY KEY,
                TEN_KE_HOACH VARCHAR(100),
                NGUOI_TAO CHAR(8) NOT NULL,
                ID_NHOM CHAR(8) NULL,
                FOREIGN KEY(NGUOI_TAO) REFERENCES NGUOI_DUNG_CA_NHAN(ID_USER),
                FOREIGN KEY(ID_NHOM) REFERENCES NHOM_LAM_VIEC(ID_NHOM)
            ) ENGINE=InnoDB;

            CREATE TABLE CONG_VIEC (
                ID_CV CHAR(8) PRIMARY KEY,
                TEN_CV VARCHAR(255) NOT NULL,
                TIEN_DO DECIMAL(5,2),
                DO_UU_TIEN INT,
                ID_KH CHAR(8),
                FOREIGN KEY (ID_KH) REFERENCES KE_HOACH(ID_KH)
            ) ENGINE=InnoDB;

            CREATE TABLE MUC_CONG_VIEC (
                ID_MUC CHAR(8) PRIMARY KEY,
                ID_CV CHAR(8),
                TEN_MUC VARCHAR(255) NOT NULL,
                NOI_DUNG_CHI_TIET TEXT,
                THOI_HAN_HOAN_THANH DATETIME,
                DO_UU_TIEN_MUC INT,
                TRANG_THAI TINYINT(1) DEFAULT 0,
                FOREIGN KEY (ID_CV) REFERENCES CONG_VIEC(ID_CV)
            ) ENGINE=InnoDB;

            CREATE TABLE CAU_HINH_THONG_BAO (
                ID_CAUHINH CHAR(8) PRIMARY KEY,
                THOI_GIAN_TRUOC_HAN DATE,
                ID_USER CHAR(8) NOT NULL,
                THOI_DIEM_THONG_BAO DATETIME NULL,
                ID_MUC CHAR(8) NULL,
                FOREIGN KEY(ID_USER) REFERENCES NGUOI_DUNG_CA_NHAN(ID_USER),
                FOREIGN KEY (ID_MUC) REFERENCES MUC_CONG_VIEC(ID_MUC)
            ) ENGINE=InnoDB;

            CREATE TABLE THONG_BAO (
                ID_TB CHAR(8) PRIMARY KEY,
                THOI_GIAN_GUI DATETIME,
                ID_CAUHINH CHAR(8),
                FOREIGN KEY(ID_CAUHINH) REFERENCES CAU_HINH_THONG_BAO(ID_CAUHINH)
            ) ENGINE=InnoDB;

            CREATE TABLE THONG_BAO_NGUOI_DUNG (
                ID_TB CHAR(8),
                ID_USER CHAR(8),
                NOI_DUNG TEXT,
                PRIMARY KEY (ID_TB, ID_USER),
                FOREIGN KEY(ID_TB) REFERENCES THONG_BAO(ID_TB),
                FOREIGN KEY(ID_USER) REFERENCES NGUOI_DUNG_CA_NHAN(ID_USER)
            ) ENGINE=InnoDB;

            CREATE TABLE NHOM_THANH_VIEN (
                ID_NHOM CHAR(8) NOT NULL,
                ID_USER CHAR(8) NOT NULL,
                PRIMARY KEY(ID_USER, ID_NHOM),
                FOREIGN KEY(ID_NHOM) REFERENCES NHOM_LAM_VIEC(ID_NHOM),
                FOREIGN KEY(ID_USER) REFERENCES NGUOI_DUNG_CA_NHAN(ID_USER)
            ) ENGINE=InnoDB;

            CREATE TABLE LOI_MOI (
                ID_USER CHAR(8),
                ID_NHOM CHAR(8),
                TRANG_THAI_LOI_MOI BOOLEAN DEFAULT NULL,
                PRIMARY KEY (ID_USER, ID_NHOM),
                FOREIGN KEY (ID_USER) REFERENCES NGUOI_DUNG_CA_NHAN(ID_USER),
                FOREIGN KEY (ID_NHOM) REFERENCES NHOM_LAM_VIEC(ID_NHOM)
            ) ENGINE=InnoDB;
        ");

        // 2. TẠO CÁC TRIGGER SINH ID TỰ ĐỘNG
        DB::unprepared("
            CREATE TRIGGER trg_generate_id_user BEFORE INSERT ON NGUOI_DUNG_CA_NHAN FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_USER, 5) AS UNSIGNED)), 0) INTO max_id FROM NGUOI_DUNG_CA_NHAN;
                SET NEW.ID_USER = CONCAT('NDCN', LPAD(max_id + 1, 4, '0'));
            END;

            CREATE TRIGGER trg_nlv_insert BEFORE INSERT ON NHOM_LAM_VIEC FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_NHOM, 4) AS UNSIGNED)), 0) INTO max_id FROM NHOM_LAM_VIEC;
                SET NEW.ID_NHOM = CONCAT('NLV', LPAD(max_id + 1, 5, '0'));
            END;

            CREATE TRIGGER trg_kh_insert BEFORE INSERT ON KE_HOACH FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_KH, 3) AS UNSIGNED)), 0) INTO max_id FROM KE_HOACH;
                SET NEW.ID_KH = CONCAT('KH', LPAD(max_id + 1, 6, '0'));
            END;

            CREATE TRIGGER trg_cv_insert BEFORE INSERT ON CONG_VIEC FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_CV, 3) AS UNSIGNED)), 0) INTO max_id FROM CONG_VIEC;
                SET NEW.ID_CV = CONCAT('CV', LPAD(max_id + 1, 6, '0'));
            END;

            CREATE TRIGGER trg_mcv_insert BEFORE INSERT ON MUC_CONG_VIEC FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_MUC, 4) AS UNSIGNED)), 0) INTO max_id FROM MUC_CONG_VIEC;
                SET NEW.ID_MUC = CONCAT('MUC', LPAD(max_id + 1, 5, '0'));
            END;

            CREATE TRIGGER trg_ch_insert BEFORE INSERT ON CAU_HINH_THONG_BAO FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_CAUHINH, 5) AS UNSIGNED)), 0) INTO max_id FROM CAU_HINH_THONG_BAO;
                SET NEW.ID_CAUHINH = CONCAT('CHTB', LPAD(max_id + 1, 4, '0'));
            END;

            CREATE TRIGGER trg_tb_insert BEFORE INSERT ON THONG_BAO FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                SELECT IFNULL(MAX(CAST(SUBSTRING(ID_TB, 3) AS UNSIGNED)), 0) INTO max_id FROM THONG_BAO;
                SET NEW.ID_TB = CONCAT('TB', LPAD(max_id + 1, 6, '0'));
            END;
        ");

        // 3. CẬP NHẬT BẢNG SESSIONS (Dành cho Authentication)
        if (Schema::hasTable('sessions')) {
            DB::statement('ALTER TABLE sessions MODIFY COLUMN user_id CHAR(8) NULL;');
        }
    }

    public function down(): void
    {
        // Xóa theo thứ tự ngược lại để tránh lỗi Foreign Key
        DB::unprepared("
            DROP TABLE IF EXISTS LOI_MOI;
            DROP TABLE IF EXISTS NHOM_THANH_VIEN;
            DROP TABLE IF EXISTS THONG_BAO_NGUOI_DUNG;
            DROP TABLE IF EXISTS THONG_BAO;
            DROP TABLE IF EXISTS CAU_HINH_THONG_BAO;
            DROP TABLE IF EXISTS MUC_CONG_VIEC;
            DROP TABLE IF EXISTS CONG_VIEC;
            DROP TABLE IF EXISTS KE_HOACH;
            DROP TABLE IF EXISTS NHOM_LAM_VIEC;
            DROP TABLE IF EXISTS NGUOI_DUNG_CA_NHAN;
        ");
    }
};
