
	$(document).ready( function () {
    $('#data_karyawan').DataTable();
} );
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	$(document).ready( function () {
    $('#data_center').DataTable();
} );
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new bootstrap.Tooltip(tooltipTriggerEl)
	})


function ganti_net(id)
{
	masuk = $("#masuk" + id).val();
	keluar = $("#keluar" + id).val();
	$("#nett" + id).val(masuk - keluar);
}
function ganti_bayar(id)
{

	masuk = $("#agt-" + id).val();
	keluar = $("#bayar-" + id).val();
	hasil = masuk - keluar;
	if (hasil>0) {
		$("#tdk-" + id).val(hasil);
	}
	else
	{
		$("#tdk-" + id).val(0);
	}
}

 $(document).on("click","#tombol_edit", function(){
    var id = $(this).data('id');
    var center = $(this).data('center');
    var status = $(this).data('status');
    var agt = $(this).data('agt');
    var bayar = $(this).data('bayar');
    var tdk = $(this).data('tdk');

    $("#id").val(id);
    $("#center").val(center);
    $("#anggota").val(agt);
    $("#status").val(status);
    $("#bayar").val(bayar);
    $("#tdk").val(tdk);

  });

 $(document).on("click","#hapuscenter",function(e){
 	e.preventDefault();
 	var idd = $(this).data('idhapus');
 	$("#hapuscenter"+idd).val('');
 	$("#agt-"+idd).val(0);

    $("#bayar-"+idd).val(0);
    $("#tdk-"+idd).val(0);

 });


// $(document).on("change","#wilayah",function(){
// 	var id = $(this).val();
// 	alert(id)
// });