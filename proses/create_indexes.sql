-- ========================================
-- SQL INDEX OPTIMIZATION SCRIPT
-- File: create_indexes.sql
-- Purpose: Optimize rekap_par.php queries
-- Date: 2025-12-10
-- ========================================

-- PASTIKAN ANDA SUDAH BACKUP DATABASE SEBELUM MENJALANKAN SCRIPT INI!

-- ========================================
-- STEP 1: Cek Index yang Sudah Ada
-- ========================================
SHOW INDEX FROM deliquency;
SHOW INDEX FROM center;
SHOW INDEX FROM karyawan;

-- ========================================
-- STEP 2: Drop Index Lama (Jika Ada Duplikat)
-- ========================================
-- Uncomment jika perlu drop index lama yang tidak efisien
-- DROP INDEX idx_old_name ON deliquency;

-- ========================================
-- STEP 3: Buat Index untuk Tabel DELIQUENCY
-- ========================================

-- Index PALING PENTING: Filter utama (tgl_input, id_cabang, loan)
-- Digunakan di hampir semua query
CREATE INDEX idx_deliquency_tgl_cabang_loan 
ON deliquency(tgl_input, id_cabang, loan);

-- Index untuk subquery dengan loan
-- Sangat penting untuk query dengan IN (SELECT loan...)
CREATE INDEX idx_deliquency_loan_tgl 
ON deliquency(loan, tgl_input, id_cabang);

-- Index untuk JOIN dengan center
CREATE INDEX idx_deliquency_no_center 
ON deliquency(no_center);

-- Index komposit untuk aggregasi
-- Mempercepat SUM(sisa_saldo) dengan GROUP BY
CREATE INDEX idx_deliquency_composite 
ON deliquency(id_cabang, tgl_input, sisa_saldo);

-- Index untuk cover query lengkap (optional, gunakan jika masih lambat)
-- CREATE INDEX idx_deliquency_cover 
-- ON deliquency(tgl_input, id_cabang, no_center, loan, sisa_saldo);

-- ========================================
-- STEP 4: Buat Index untuk Tabel CENTER
-- ========================================

-- Index PRIMARY KEY untuk no_center (jika belum ada)
-- Skip jika sudah ada PRIMARY KEY atau UNIQUE
CREATE INDEX idx_center_no_center 
ON center(no_center);

-- Index komposit untuk filter dan JOIN
-- Sangat penting untuk mempercepat JOIN dengan deliquency
CREATE INDEX idx_center_cabang_karyawan 
ON center(id_cabang, id_karyawan);

-- Index tambahan jika diperlukan
CREATE INDEX idx_center_id_karyawan 
ON center(id_karyawan);

-- ========================================
-- STEP 5: Buat Index untuk Tabel KARYAWAN
-- ========================================

-- Index PRIMARY KEY (biasanya sudah ada)
-- Skip jika id_karyawan sudah PRIMARY KEY
CREATE INDEX idx_karyawan_id 
ON karyawan(id_karyawan);

-- Index untuk ORDER BY nama_karyawan
CREATE INDEX idx_karyawan_nama 
ON karyawan(nama_karyawan);

-- ========================================
-- STEP 6: Analyze Tables (Wajib Setelah Buat Index)
-- ========================================

-- Update statistik table agar MySQL bisa optimize query dengan baik
ANALYZE TABLE deliquency;
ANALYZE TABLE center;
ANALYZE TABLE karyawan;

-- ========================================
-- STEP 7: Verify Index Berhasil Dibuat
-- ========================================

SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('deliquency', 'center', 'karyawan')
ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX;

-- ========================================
-- STEP 8: Test Query Performance
-- ========================================

-- Test query untuk lihat apakah index terpakai
-- Harusnya muncul "Using index" atau "Using where; Using index"

EXPLAIN 
SELECT COUNT(d.id), SUM(d.sisa_saldo), k.nama_karyawan 
FROM deliquency d 
INNER JOIN center c ON c.no_center = d.no_center AND c.id_cabang = '1'
INNER JOIN karyawan k ON k.id_karyawan = c.id_karyawan
LEFT JOIN deliquency d2 ON d2.loan = d.loan 
    AND d2.tgl_input = '2024-12-01' 
    AND d2.id_cabang = '1'
WHERE d.tgl_input = '2024-11-24' 
    AND d.id_cabang = '1'
    AND d2.loan IS NULL
GROUP BY k.id_karyawan, k.nama_karyawan;

-- ========================================
-- MONITORING & MAINTENANCE
-- ========================================

-- Cek ukuran index
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    ROUND(STAT_VALUE * @@innodb_page_size / 1024 / 1024, 2) AS size_mb
FROM mysql.innodb_index_stats
WHERE TABLE_NAME IN ('deliquency', 'center', 'karyawan')
AND DATABASE_NAME = DATABASE()
ORDER BY size_mb DESC;

-- Cek index fragmentation (jalankan setiap 3 bulan)
-- OPTIMIZE TABLE deliquency;
-- OPTIMIZE TABLE center;
-- OPTIMIZE TABLE karyawan;

-- ========================================
-- TROUBLESHOOTING
-- ========================================

-- Jika query masih lambat, cek apakah index terpakai:
-- EXPLAIN FORMAT=JSON SELECT ...

-- Jika index tidak terpakai, cek statistik:
-- SHOW TABLE STATUS LIKE 'deliquency';

-- Force MySQL gunakan index tertentu (last resort):
-- SELECT ... FROM deliquency USE INDEX (idx_deliquency_tgl_cabang_loan) ...

-- ========================================
-- ROLLBACK (Jika Ada Masalah)
-- ========================================

-- Uncomment untuk drop semua index yang dibuat
/*
DROP INDEX idx_deliquency_tgl_cabang_loan ON deliquency;
DROP INDEX idx_deliquency_loan_tgl ON deliquency;
DROP INDEX idx_deliquency_no_center ON deliquency;
DROP INDEX idx_deliquency_composite ON deliquency;
DROP INDEX idx_center_no_center ON center;
DROP INDEX idx_center_cabang_karyawan ON center;
DROP INDEX idx_center_id_karyawan ON center;
DROP INDEX idx_karyawan_id ON karyawan;
DROP INDEX idx_karyawan_nama ON karyawan;
*/

-- ========================================
-- NOTES
-- ========================================
/*
1. Index akan memperlambat INSERT/UPDATE/DELETE sedikit (5-10%)
2. Index akan mempercepat SELECT drastis (80-95%)
3. Index memerlukan storage tambahan (~10-30% dari ukuran tabel)
4. Jalankan ANALYZE TABLE setiap 1 bulan untuk update statistik
5. Monitor query performance dengan slow query log
6. Backup database sebelum membuat index!

ESTIMASI WAKTU PEMBUATAN INDEX:
- 10K rows: ~5-10 detik
- 50K rows: ~30-60 detik
- 100K rows: ~1-2 menit
- 500K rows: ~5-10 menit
- 1M rows: ~15-30 menit

Selama pembuatan index, tabel akan ter-lock!
Sebaiknya dilakukan saat jam off-peak (malam hari atau weekend).
*/
