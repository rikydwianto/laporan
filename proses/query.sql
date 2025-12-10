CREATE view bayar as select laporan.id_laporan,sum(detail_laporan.total_agt) as total_agt, sum(detail_laporan.total_bayar) as total_bayar, sum(detail_laporan.total_tidak_bayar) as total_tidak_bayar, round((total_bayar/total_agt)*100) as persen from laporan, detail_laporan where laporan.id_laporan=detail_laporan.id_laporan group by detail_laporan.id_laporan ;


CREATE view rekap_center as 
SELECT laporan.id_karyawan, detail_laporan.no_center FROM laporan, detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.status_laporan='sukses' GROUP by detail_laporan.no_center,laporan.id_karyawan order by laporan.id_karyawan asc ;

create view rekap_bayar as select laporan.id_laporan,sum(detail_laporan.total_agt) as total_agt, sum(detail_laporan.total_bayar) as total_bayar, sum(detail_laporan.total_tidak_bayar) as total_tidak_bayar, round((total_bayar/total_agt)*100) as persen
, laporan.id_karyawan,laporan.tgl_laporan
from laporan, detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.status_laporan='sukses' group by detail_laporan.id_laporan;