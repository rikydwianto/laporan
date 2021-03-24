
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




var map = L.map('map').setView([latdb,lngdb], 14);
//Tambh lokasi sekarang
map.addControl(L.control.locate({
       locateOptions: {
               enableHighAccuracy: true}

}));

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

//setting ICON
var kantor = L.icon({
    iconUrl: url_link+'assets/img/icon/kantor.png',
    iconSize:     [38, 45] // size of the icon
     // point from which the popup should open relative to the iconAnchor
});

var center = L.icon({
    iconUrl: url_link+'assets/img/icon/center.png',
    iconSize:     [38, 45] // size of the icon
     // point from which the popup should open relative to the iconAnchor
});

var anggota = L.icon({
    iconUrl: url_link+'assets/img/icon/anggota.png',
    iconSize:     [38, 45] // size of the icon
     // point from which the popup should open relative to the iconAnchor
});

var lainya = L.icon({
    iconUrl: url_link+'assets/img/icon/informasi.png',
    iconSize:     [38, 45] // size of the icon
    // point from which the popup should open relative to the iconAnchor
});

var icon='anggota';
$.getJSON( url_link + "api/cabang.php", function( data ) {
          var items = [];
          $.each( data, function( i, field ) {
            if(data[i]['latitude'] == null)
            {

            }
            else
            {
                 L.marker([data[i]['latitude'],data[i]['longitude']],{icon: kantor}).addTo(map)
            .bindPopup("<h4>KANTOR CABANG - " + data[i]['nama_cabang'].toUpperCase() + "</h4>");
            
            }
           

          });
         
          
        });

    // ini untuk center aja
    var text ='';
    $.getJSON( url_link + "api/peta.php", function( data ) {
		  var items = [];
		  $.each( data, function( i, field ) {
		  	text = "<h2>"+data[i]['nama_lokasi']+"</h2><br> Kategori "+ data[i]['kategori']+" <br/>Center : " + data[i]['center']+" <br/> Keterangan : " + data[i]['keterangan']+" <br/> Alamat : " + data[i]['alamat'];
            text += "<br/><small>staff : "+data[i]['nama_karyawan']+"</small>"; 
            text += "<br/><a href='"+data[i]['link_google']+"'> Direct</a>"; 
	   	   
           if(data[i]['kategori']=='center'){
            ikon = center;
           }
           else if(data[i]['kategori']=='anggota')
           {
            ikon=anggota;
           }
           else if(data[i]['kategori']=='pu'){
            ikon = lainya;
           }
            
            L.marker([data[i]['latitude'],data[i]['longitude']],{icon:ikon}).addTo(map)
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
    map.panTo( L.LatLng(position.lat, position.lng)).bindPopup('coba');
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


var text = "<h3>Tambah Lokasi</h3> ";
text += "<br><a class='btn' href='"+ url_link+"index.php?menu=lokasi&pilih=center" +"&lat="+e.latlng.lat+"&lng="+e.latlng.lng+"'>Center</a>";
text += "<br><a class='btn' href='"+ url_link+"index.php?menu=lokasi&pilih=anggota" +"&lat="+e.latlng.lat+"&lng="+e.latlng.lng+"'>ANGGOTA</a>";
text += "<br><a class='btn' href='"+ url_link+"index.php?menu=lokasi&pilih=pu" +"&lat="+e.latlng.lat+"&lng="+e.latlng.lng+"'>INFORMASI LAINYA</a>";


L.marker([e.latlng.lat,e.latlng.lng]).addTo(map)
    .bindPopup( text)
    .openPopup();


    $("#center").attr('href',center +"&lat="+lat+"&lng="+lng);
    $("#anggota").attr('href',anggota +"&lat="+lat+"&lng="+lng);
    $("#pu").attr('href',pu +"&lat="+lat+"&lng="+lng);
});


