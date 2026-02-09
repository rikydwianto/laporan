-- ============================================
-- REKOMENDASI INDEX UNTUK OPTIMASI DATABASE
-- Dibuat: 2026-02-09
-- Berdasarkan analisis query di list_par.php dan file terkait
-- Compatible: MySQL 5.5+ / MariaDB 10.0+
-- ============================================

-- PETUNJUK:
-- 1. Backup database terlebih dahulu
-- 2. Cek index yang sudah ada: SHOW INDEX FROM nama_tabel;
-- 3. Jalankan hanya index yang belum ada (skip jika error "Duplicate key name")
-- 4. Monitor performa setelah indexing

-- CARA CEK INDEX YANG SUDAH ADA:
-- SHOW INDEX FROM deliquency WHERE Key_name LIKE 'idx_%';

-- ============================================
-- A. INDEX PRIORITAS TINGGI (WAJIB)
-- ============================================

-- Index untuk tabel DELIQUENCY (table utama PAR)
-- -------------------------------------------------

-- Index composite untuk query list_par.php (PALING PENTING!)
-- Query: GROUP BY tgl_input dengan filter id_cabang
-- Jika error "Duplicate key name" berarti sudah ada, skip saja
CREATE INDEX idx_deliq_cabang_tgl_composite 
ON deliquency(id_cabang, tgl_input);

-- Index untuk SUM(sisa_saldo) optimization
CREATE INDEX idx_deliq_cabang_tgl_saldo 
ON deliquency(id_cabang, tgl_input, sisa_saldo);

-- Index untuk COUNT DISTINCT no_center dalam subquery
CREATE INDEX idx_deliq_cabang_tgl_center 
ON deliquency(id_cabang, tgl_input, no_center);

-- Index untuk filter minggu
CREATE INDEX idx_deliq_cabang_tgl_minggu 
ON deliquency(id_cabang, tgl_input, minggu);

-- Index untuk GROUP BY YEAR/MONTH(tgl_disburse)
CREATE INDEX idx_deliq_cabang_tgl_disburse 
ON deliquency(id_cabang, tgl_input, tgl_disburse);

-- Index untuk rekap_par.php dengan filter loan
CREATE INDEX idx_deliq_cabang_loan_tgl 
ON deliquency(id_cabang, loan, tgl_input);


-- ============================================
-- B. INDEX PRIORITAS SEDANG (DISARANKAN)
-- ============================================

-- Index untuk tabel CENTER
-- -------------------------------------------------

-- Join deliquency dengan center via no_center
CREATE INDEX idx_center_no_karyawan 
ON center(no_center, id_karyawan);

-- Join center dengan karyawan via id_karyawan
CREATE INDEX idx_center_cabang_karyawan 
ON center(id_cabang, id_karyawan);

-- Filter hari center
CREATE INDEX idx_center_no_hari 
ON center(no_center, hari);


-- Index untuk tabel KARYAWAN
-- -------------------------------------------------

-- Join dengan center atau pinjaman
CREATE INDEX idx_karyawan_id_cabang 
ON karyawan(id_karyawan, id_cabang);

-- Filter nama karyawan untuk order by
CREATE INDEX idx_karyawan_nama 
ON karyawan(id_cabang, nama_karyawan);


-- Index untuk tabel TEMP_BAYAR
-- -------------------------------------------------

-- Query: cek_par_hari_ini.php
CREATE INDEX idx_tempbayar_cabang_tgl 
ON temp_bayar(id_cabang, tgl_bayar);

-- Filter bayar <= 0
CREATE INDEX idx_tempbayar_cabang_tgl_bayar 
ON temp_bayar(id_cabang, tgl_bayar, bayar);

-- NOT IN loan subquery
CREATE INDEX idx_tempbayar_loan 
ON temp_bayar(loan_no, id_cabang);


-- Index untuk tabel PINJAMAN
-- -------------------------------------------------

-- Join dengan deliquency via loan (id_detail_pinjaman)
CREATE INDEX idx_pinjaman_detail_cabang 
ON pinjaman(id_detail_pinjaman, id_cabang);

-- Filter monitoring dan input_mtr
CREATE INDEX idx_pinjaman_cabang_monitoring 
ON pinjaman(id_cabang, monitoring, input_mtr);

-- Group by karyawan
CREATE INDEX idx_pinjaman_karyawan_cabang 
ON pinjaman(id_karyawan, id_cabang);

-- Filter jenis_topup untuk anal_topup.php
CREATE INDEX idx_pinjaman_nasabah_topup 
ON pinjaman(id_detail_nasabah, jenis_topup);


-- ============================================
-- C. INDEX PRIORITAS RENDAH (OPSIONAL)
-- ============================================

-- Index untuk tabel DAFTAR_NASABAH
-- -------------------------------------------------

-- Join dengan deliquency via id_detail_nasabah
CREATE INDEX idx_nasabah_detail_cabang 
ON daftar_nasabah(id_detail_nasabah, id_cabang);

-- Join dengan center via no_center
CREATE INDEX idx_nasabah_center_cabang 
ON daftar_nasabah(no_center, id_cabang);

-- Group by kelompok
CREATE INDEX idx_nasabah_center_kelompok 
ON daftar_nasabah(no_center, kelompok);


-- Index untuk tabel ALASAN_PAR
-- -------------------------------------------------

-- Join dengan pinjaman
CREATE INDEX idx_alasan_detail_cabang 
ON alasan_par(id_detail_pinjaman, id_cabang);


-- Index untuk tabel ANGGOTA_PAR
-- -------------------------------------------------

-- Join dengan pinjaman
CREATE INDEX idx_anggotapar_detail_cabang 
ON anggota_par(id_detail_pinjaman, id_cabang);


-- ============================================
-- D. ANALISIS DAN MONITORING
-- ============================================

-- Cek ukuran index setelah dibuat:
/*
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    ROUND(SUM(STAT_VALUE * @@innodb_page_size) / 1024 / 1024, 2) AS size_mb
FROM mysql.innodb_index_stats
WHERE DATABASE_NAME = DATABASE()
    AND TABLE_NAME IN ('deliquency', 'center', 'pinjaman', 'karyawan', 'temp_bayar')
GROUP BY TABLE_NAME, INDEX_NAME
ORDER BY TABLE_NAME, size_mb DESC;
*/

-- Cek index yang tidak terpakai (jalankan setelah 1 minggu):
/*
SELECT 
    object_schema,
    object_name,
    index_name
FROM performance_schema.table_io_waits_summary_by_index_usage
WHERE index_name IS NOT NULL
    AND count_star = 0
    AND object_schema = DATABASE()
    AND index_name != 'PRIMARY'
ORDER BY object_schema, object_name;
*/


-- ============================================
-- E. CATATAN PENTING
-- ============================================

/*
1. URUTAN KOLOM DALAM INDEX SANGAT PENTING:
   - Kolom filter WHERE harus di depan
   - Kolom GROUP BY di tengah
   - Kolom SELECT di belakang

2. INDEX COMPOSITE vs MULTIPLE INDEX:
   ✓ BAIK: (id_cabang, tgl_input, sisa_saldo)
   ✗ BURUK: (sisa_saldo, tgl_input, id_cabang)
   
3. INDEX TERLALU BANYAK = LAMBAT INSERT/UPDATE:
   - Index mempercepat SELECT
   - Index memperlambat INSERT/UPDATE/DELETE
   - Jangan buat index untuk kolom yang jarang difilter

4. MONITORING PERFORMA:
   - Gunakan EXPLAIN ANALYZE sebelum dan sesudah indexing
   - Monitor slow query log
   - Cek index yang tidak terpakai setiap bulan

5. MAINTENANCE INDEX:
   - Jalankan ANALYZE TABLE secara berkala
   - Rebuild index jika data sudah banyak berubah
   - Hapus index yang tidak terpakai

6. ESTIMASI DAMPAK:
   Prioritas Tinggi (A):
   - list_par.php: 50-80% lebih cepat
   - rekap_par.php: 40-70% lebih cepat
   - Query dengan GROUP BY: 60-90% lebih cepat
   
   Prioritas Sedang (B):
   - Query dengan JOIN: 30-50% lebih cepat
   - Filter berdasarkan karyawan: 40-60% lebih cepat
   
   Prioritas Rendah (C):
   - Query detail nasabah: 20-40% lebih cepat
   - Report khusus: 30-50% lebih cepat
*/


-- ============================================
-- F. CONTOH PENGGUNAAN EXPLAIN ANALYZE
-- ============================================

/*
-- SEBELUM INDEX:
EXPLAIN ANALYZE
SELECT tgl_input, COUNT(*) as hitung
FROM deliquency
WHERE id_cabang='XXX'
GROUP BY tgl_input;

-- Output: 
-- -> Table scan on deliquency (cost=1000 rows=10000) (actual time=0.5..50 rows=10000)
-- Execution time: 50ms

-- SESUDAH INDEX idx_deliq_cabang_tgl_composite:
-- -> Index range scan on idx_deliq_cabang_tgl_composite (cost=100 rows=1000) (actual time=0.1..5 rows=1000)
-- Execution time: 5ms

-- IMPROVEMENT: 90% lebih cepat! (50ms → 5ms)
*/


-- ============================================
-- G. SCRIPT UNTUK CEK INDEX YANG SUDAH ADA
-- ============================================

/*
-- Jalankan ini untuk cek index deliquency:
SHOW INDEX FROM deliquency;

-- Jalankan ini untuk cek apakah index sudah optimal:
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    SEQ_IN_INDEX,
    COLUMN_NAME,
    CARDINALITY,
    INDEX_TYPE
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'deliquency'
ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX;
*/
