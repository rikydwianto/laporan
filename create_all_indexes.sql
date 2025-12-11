-- ============================================
-- SQL INDEX UNTUK OPTIMASI DATABASE
-- Dibuat untuk file: rekap_par, par, monitoring, dll
-- Jalankan satu per satu atau semua sekaligus
-- ============================================

-- Cek apakah index sudah ada sebelum membuat (optional)
-- SHOW INDEX FROM nama_tabel;

-- ============================================
-- 1. INDEX UNTUK TABEL DELIQUENCY
-- ============================================
CREATE INDEX IF NOT EXISTS idx_deliquency_tgl_cabang_loan ON deliquency(tgl_input, id_cabang, loan);
CREATE INDEX IF NOT EXISTS idx_deliquency_loan_tgl ON deliquency(loan, tgl_input);
CREATE INDEX IF NOT EXISTS idx_deliq_composite ON deliquency(id_cabang, tgl_input, loan);

-- ============================================
-- 2. INDEX UNTUK TABEL PINJAMAN
-- ============================================
CREATE INDEX IF NOT EXISTS idx_pinjaman_loan ON pinjaman(id_detail_pinjaman);
CREATE INDEX IF NOT EXISTS idx_pinjaman_cabang ON pinjaman(id_cabang);
CREATE INDEX IF NOT EXISTS idx_pinjaman_cabang_monitoring ON pinjaman(id_cabang, monitoring, input_mtr);
CREATE INDEX IF NOT EXISTS idx_pinjaman_tgl_cair ON pinjaman(tgl_cair);
CREATE INDEX IF NOT EXISTS idx_pinjaman_karyawan ON pinjaman(id_karyawan);
CREATE INDEX IF NOT EXISTS idx_pinjaman_detail ON pinjaman(id_detail_pinjaman, id_cabang);
CREATE INDEX IF NOT EXISTS idx_pinjaman_nasabah ON pinjaman(id_detail_nasabah);

-- INDEX COMPOSITE untuk pinjaman (PENTING!)
CREATE INDEX IF NOT EXISTS idx_pinjaman_composite ON pinjaman(id_cabang, monitoring, input_mtr, tgl_cair);
CREATE INDEX IF NOT EXISTS idx_pinjaman_monitoring_composite ON pinjaman(id_karyawan, monitoring, input_mtr, id_cabang);

-- INDEX untuk jenis_topup (OPTIMASI TERBARU!)
CREATE INDEX IF NOT EXISTS idx_pinjaman_jenis_topup ON pinjaman(jenis_topup, id_detail_nasabah);
CREATE INDEX IF NOT EXISTS idx_pinjaman_topup_update ON pinjaman(id_detail_nasabah, id_cabang, jenis_topup);

-- ============================================
-- 3. INDEX UNTUK TABEL ALASAN_PAR
-- ============================================
CREATE INDEX IF NOT EXISTS idx_alasan_par ON alasan_par(id_detail_pinjaman);
CREATE INDEX IF NOT EXISTS idx_alasan_par_cabang ON alasan_par(id_detail_pinjaman, id_cabang);

-- ============================================
-- 4. INDEX UNTUK TABEL ANGGOTA_PAR
-- ============================================
CREATE INDEX IF NOT EXISTS idx_anggota_par ON anggota_par(id_detail_pinjaman);
CREATE INDEX IF NOT EXISTS idx_anggota_par_cabang ON anggota_par(id_detail_pinjaman, id_cabang);

-- ============================================
-- 5. INDEX UNTUK TABEL TPK
-- ============================================
CREATE INDEX IF NOT EXISTS idx_tpk_nasabah ON tpk(id_detail_nasabah, id_cabang);
CREATE INDEX IF NOT EXISTS idx_tpk_detail_nasabah ON tpk(id_detail_nasabah);
CREATE INDEX IF NOT EXISTS idx_tpk_cabang ON tpk(id_cabang);

-- ============================================
-- 6. INDEX UNTUK TABEL KETERANGAN_TOPUP
-- ============================================
CREATE INDEX IF NOT EXISTS idx_topup_nasabah ON keterangan_topup(id_detail_nasabah, id_cabang);
CREATE INDEX IF NOT EXISTS idx_topup_detail_nasabah ON keterangan_topup(id_detail_nasabah);
CREATE INDEX IF NOT EXISTS idx_topup_cabang ON keterangan_topup(id_cabang);

-- ============================================
-- 7. INDEX UNTUK TABEL TEMP_ANGGOTA_KELUAR
-- ============================================
CREATE INDEX IF NOT EXISTS idx_temp_keluar ON temp_anggota_keluar(id_detail_pinjaman);
CREATE INDEX IF NOT EXISTS idx_temp_keluar_cabang ON temp_anggota_keluar(id_detail_pinjaman, id_cabang);

-- ============================================
-- 8. INDEX UNTUK TABEL MONITORING
-- ============================================
CREATE INDEX IF NOT EXISTS idx_monitoring_karyawan ON monitoring(id_karyawan, tgl_monitoring);
CREATE INDEX IF NOT EXISTS idx_monitoring_detail ON monitoring(id_detail_pinjaman);
CREATE INDEX IF NOT EXISTS idx_monitoring_cabang ON monitoring(id_cabang);
CREATE INDEX IF NOT EXISTS idx_monitoring_composite ON monitoring(id_karyawan, id_cabang, tgl_monitoring);

-- ============================================
-- 9. INDEX UNTUK TABEL CENTER
-- ============================================
CREATE INDEX IF NOT EXISTS idx_center_no ON center(no_center, id_cabang);
CREATE INDEX IF NOT EXISTS idx_center_karyawan ON center(id_karyawan, id_cabang);
CREATE INDEX IF NOT EXISTS idx_center_composite ON center(no_center, id_cabang, id_karyawan);

-- ============================================
-- 10. INDEX UNTUK TABEL KARYAWAN
-- ============================================
CREATE INDEX IF NOT EXISTS idx_karyawan_jabatan ON karyawan(id_jabatan, id_cabang, status_karyawan);
CREATE INDEX IF NOT EXISTS idx_karyawan_cabang ON karyawan(id_cabang, status_karyawan);
CREATE INDEX IF NOT EXISTS idx_karyawan_composite ON karyawan(id_cabang, id_jabatan, status_karyawan);

-- ============================================
-- 11. INDEX UNTUK TABEL BANDING_MONITORING
-- ============================================
CREATE INDEX IF NOT EXISTS idx_banding_detail ON banding_monitoring(id_detail_pinjaman);
CREATE INDEX IF NOT EXISTS idx_banding_status ON banding_monitoring(status, id_cabang);
CREATE INDEX IF NOT EXISTS idx_banding_composite ON banding_monitoring(id_cabang, status, id_detail_pinjaman);

-- ============================================
-- SELESAI!
-- ============================================
-- Untuk mengecek index yang sudah dibuat:
-- SHOW INDEX FROM pinjaman;
-- SHOW INDEX FROM deliquency;
-- SHOW INDEX FROM monitoring;
-- dll

-- Untuk menghapus index jika diperlukan:
-- DROP INDEX nama_index ON nama_tabel;

-- Untuk melihat performa query:
-- EXPLAIN SELECT * FROM pinjaman WHERE id_cabang='xxx' AND monitoring='belum';
