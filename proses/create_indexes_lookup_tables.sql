-- ========================================
-- SQL INDEX UNTUK TABEL LOOKUP (par.php)
-- File: create_indexes_lookup_tables.sql
-- Purpose: Optimize lookup queries in par.php
-- Date: 2025-12-10
-- ========================================

-- PASTIKAN ANDA SUDAH BACKUP DATABASE SEBELUM MENJALANKAN SCRIPT INI!
-- Script ini melengkapi create_indexes.sql untuk tabel lookup

-- ========================================
-- STEP 1: Index untuk Tabel ALASAN_PAR
-- ========================================

-- Index untuk query lookup alasan berdasarkan loan
CREATE INDEX idx_alasan_par_loan_cabang 
ON alasan_par(id_loan, id_cabang);

-- Index untuk filter per cabang
CREATE INDEX idx_alasan_par_cabang 
ON alasan_par(id_cabang);

-- ========================================
-- STEP 2: Index untuk Tabel ANGGOTA_PAR
-- ========================================

-- Index untuk cek status PAR berdasarkan nasabah
CREATE INDEX idx_anggota_par_nasabah_cabang 
ON anggota_par(id_detail_nasabah, id_cabang);

-- Index untuk lookup berdasarkan loan
CREATE INDEX idx_anggota_par_loan_cabang 
ON anggota_par(loan, id_cabang);

-- ========================================
-- STEP 3: Index untuk Tabel TEMP_ANGGOTA_KELUAR
-- ========================================

-- Index untuk cek anggota keluar
CREATE INDEX idx_temp_anggota_keluar_nasabah_cabang 
ON temp_anggota_keluar(id_nasabah, id_cabang);

-- Index tambahan jika query menggunakan status
CREATE INDEX idx_temp_anggota_keluar_status 
ON temp_anggota_keluar(id_cabang, status);

-- ========================================
-- STEP 4: Index untuk Tabel TPK
-- ========================================

-- Index untuk cek TPK berdasarkan nasabah
CREATE INDEX idx_tpk_nasabah_cabang 
ON tpk(id_detail_nasabah, id_cabang);

-- Index untuk filter per cabang
CREATE INDEX idx_tpk_cabang 
ON tpk(id_cabang);

-- ========================================
-- STEP 5: Index untuk Tabel KETERANGAN_TOPUP
-- ========================================

-- Index untuk lookup topup berdasarkan nasabah
CREATE INDEX idx_keterangan_topup_nasabah_cabang 
ON keterangan_topup(id_detail_nasabah, id_cabang);

-- Index untuk lookup berdasarkan pinjaman
CREATE INDEX idx_keterangan_topup_pinjaman_cabang 
ON keterangan_topup(id_detail_pinjaman, id_cabang);

-- Index untuk filter berdasarkan tanggal topup
CREATE INDEX idx_keterangan_topup_tgl 
ON keterangan_topup(id_cabang, tgl_topup);

-- ========================================
-- STEP 6: Analyze Tables
-- ========================================

ANALYZE TABLE alasan_par;
ANALYZE TABLE anggota_par;
ANALYZE TABLE temp_anggota_keluar;
ANALYZE TABLE tpk;
ANALYZE TABLE keterangan_topup;

-- ========================================
-- STEP 7: Verify Index Creation
-- ========================================

-- Cek index untuk alasan_par
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'alasan_par'
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

-- Cek index untuk anggota_par
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'anggota_par'
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

-- Cek index untuk temp_anggota_keluar
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'temp_anggota_keluar'
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

-- Cek index untuk tpk
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'tpk'
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

-- Cek index untuk keterangan_topup
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX,
    CARDINALITY
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'keterangan_topup'
ORDER BY INDEX_NAME, SEQ_IN_INDEX;

-- ========================================
-- STEP 8: Test Query Performance
-- ========================================

-- Test query alasan_par (ganti dengan loan ID real)
EXPLAIN 
SELECT id_loan, alasan, penyelesaian_par 
FROM alasan_par 
WHERE id_loan IN ('LOAN001', 'LOAN002', 'LOAN003') 
AND id_cabang = '1';

-- Test query anggota_par
EXPLAIN 
SELECT id_detail_nasabah 
FROM anggota_par 
WHERE id_detail_nasabah IN ('AGT-001', 'AGT-002', 'AGT-003') 
AND id_cabang = '1';

-- Test query TPK
EXPLAIN 
SELECT id_detail_nasabah 
FROM tpk 
WHERE id_detail_nasabah IN ('AGT-001', 'AGT-002') 
AND id_cabang = '1';

-- ========================================
-- MONITORING
-- ========================================

-- Cek ukuran index (setelah dibuat)
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    ROUND(STAT_VALUE * @@innodb_page_size / 1024 / 1024, 2) AS size_mb
FROM mysql.innodb_index_stats
WHERE TABLE_NAME IN ('alasan_par', 'anggota_par', 'temp_anggota_keluar', 'tpk', 'keterangan_topup')
AND DATABASE_NAME = DATABASE()
ORDER BY TABLE_NAME, size_mb DESC;

-- Cek row count per tabel
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    ROUND(DATA_LENGTH / 1024 / 1024, 2) AS data_size_mb,
    ROUND(INDEX_LENGTH / 1024 / 1024, 2) AS index_size_mb,
    ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) AS total_size_mb
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('alasan_par', 'anggota_par', 'temp_anggota_keluar', 'tpk', 'keterangan_topup')
ORDER BY total_size_mb DESC;

-- ========================================
-- MAINTENANCE (Opsional - Jalankan per 3 bulan)
-- ========================================

-- Optimize tables untuk defragment index
-- OPTIMIZE TABLE alasan_par;
-- OPTIMIZE TABLE anggota_par;
-- OPTIMIZE TABLE temp_anggota_keluar;
-- OPTIMIZE TABLE tpk;
-- OPTIMIZE TABLE keterangan_topup;

-- ========================================
-- ROLLBACK (Jika Ada Masalah)
-- ========================================

-- Uncomment untuk drop semua index yang dibuat
/*
-- Drop index alasan_par
DROP INDEX idx_alasan_par_loan_cabang ON alasan_par;
DROP INDEX idx_alasan_par_cabang ON alasan_par;

-- Drop index anggota_par
DROP INDEX idx_anggota_par_nasabah_cabang ON anggota_par;
DROP INDEX idx_anggota_par_loan_cabang ON anggota_par;

-- Drop index temp_anggota_keluar
DROP INDEX idx_temp_anggota_keluar_nasabah_cabang ON temp_anggota_keluar;
DROP INDEX idx_temp_anggota_keluar_status ON temp_anggota_keluar;

-- Drop index tpk
DROP INDEX idx_tpk_nasabah_cabang ON tpk;
DROP INDEX idx_tpk_cabang ON tpk;

-- Drop index keterangan_topup
DROP INDEX idx_keterangan_topup_nasabah_cabang ON keterangan_topup;
DROP INDEX idx_keterangan_topup_pinjaman_cabang ON keterangan_topup;
DROP INDEX idx_keterangan_topup_tgl ON keterangan_topup;
*/

-- ========================================
-- NOTES & RECOMMENDATIONS
-- ========================================

/*
PENTING:
1. Index ini MELENGKAPI create_indexes.sql
2. Jalankan create_indexes.sql TERLEBIH DAHULU
3. Baru jalankan script ini

ESTIMASI UKURAN INDEX:
- alasan_par: ~5-20 MB (tergantung jumlah data)
- anggota_par: ~3-15 MB
- temp_anggota_keluar: ~2-10 MB
- tpk: ~2-8 MB
- keterangan_topup: ~3-12 MB
Total tambahan: ~15-65 MB

ESTIMASI WAKTU PEMBUATAN:
- <10K rows: 1-5 detik per tabel
- 10K-50K rows: 5-30 detik per tabel
- 50K-100K rows: 30-60 detik per tabel
- >100K rows: 1-5 menit per tabel

PERHATIAN:
- Tabel akan ter-lock saat membuat index
- Lakukan saat jam off-peak (malam/weekend)
- Monitor disk space sebelum membuat index
- Index memperlambat INSERT/UPDATE/DELETE ~5-15%
- Tapi mempercepat SELECT ~80-95%! 🚀

CARA MENGGUNAKAN:
1. Backup database terlebih dahulu
2. Jalankan: mysql -u username -p database_name < create_indexes_lookup_tables.sql
3. Monitor pembuatan index dengan: SHOW PROCESSLIST;
4. Verify dengan query di STEP 7
5. Test performa dengan par_optimized.php

REKOMENDASI INDEX PRIORITY:
- HIGH: alasan_par, anggota_par (sering digunakan)
- MEDIUM: tpk, keterangan_topup
- LOW: temp_anggota_keluar (jika jarang query)

MONITORING PERFORMANCE:
-- Cek slow queries
SELECT * FROM mysql.slow_log 
WHERE sql_text LIKE '%alasan_par%' 
ORDER BY query_time DESC 
LIMIT 10;

-- Cek index usage
SELECT * FROM sys.schema_unused_indexes 
WHERE object_schema = DATABASE();
*/

-- ========================================
-- END OF SCRIPT
-- ========================================
