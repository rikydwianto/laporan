
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


var map = L.map('map').setView([-6.449471595334012,107.81619415504505], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);


map.on('click',function (e) { 
    alert( 'singleclick', e ); 
    L.popup().setLatLng( e.latlng )
        .setContent( '<p><code>singleclick</code> at ' + e.latlng ) 
        .openOn(map);
} );


L.marker([-6.449471595334012,107.81619415504505]).addTo(map)
    .bindPopup('KOMIDA PAGADEN')
    .openPopup();

    // ini untuk center aja
    var text ='';
    $.getJSON( url_link + "api/peta.php", function( data ) {
		  var items = [];
		  $.each( data, function( i, field ) {
		  	text = "<h2>"+data[i]['nama_lokasi']+"</h2><br> Kategori "+ data[i]['kategori']+" <br/>Center : " + data[i]['center']+" <br/> Keterangan : " + data[i]['keterangan']+" <br/> Alamat : " + data[i]['alamat']+ "<a href='"+data[i]['link_google']+"'> Direct</a>"; 
	   	
L.marker([data[i]['latitude'],data[i]['longitude']]).addTo(map)
		    .bindPopup(text);

		  });
		 
		  
		});


var marker1 = L.marker([0, 0]).addTo(map);

        map.on('click', function () {
          map.removeLayer(marker1);
        });
        
function onMapClick(e) {
    
  marker = new L.marker(e.latlng, {draggable:'true'});

  marker.on('dragend', function(event){
    var marker = event.target;
    var position = marker.getLatLng();

    marker.setLatLng(L.LatLng(position.lat, position.lng),{draggable:'true'});
    map.panTo( L.LatLng(position.lat, position.lng)).bindPopup('coba').openPopup();
  });
  map.addLayer(marker);

};


map.on('click',onMapClick);
map.on('click', function(e) {
    // alert("Lat, Lon : " + e.latlng.lat + ", " + e.latlng.lng);
    $("#latitude").val(e.latlng.lat);
    $("#longitude").val(e.latlng.lng);
    var center = $("#link #center").data('link');
    var anggota = $("#link #anggota").data('link');
    var pu = $("#link #pu").data('link');
    var lat = $("#latitude").val();
    var lng = $("#longitude").val();

    $("#center").attr('href',center +"&lat="+lat+"&lng="+lng);
    $("#anggota").attr('href',anggota +"&lat="+lat+"&lng="+lng);
    $("#pu").attr('href',pu +"&lat="+lat+"&lng="+lng);
});


