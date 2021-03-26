
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

//Tambah lokasi
function onLocationFound(e) {
    var radius = e.accuracy;

    L.marker(e.latlng).addTo(map)
        .bindPopup(".").openPopup();
map = L.map('map').setView([e.latlng.lat,e.latlng.lng], 14);

    L.circle(e.latlng, 2).addTo(map);
}

map.on('locationfound', onLocationFound);




var lc = map.addControl(L.control.locate({
       locateOptions: {
               enableHighAccuracy: true},
        position: 'topleft',  // set the location of the control
    drawCircle: true,  // controls whether a circle is drawn that shows the uncertainty about the location
    follow: false,  // follow the user's location
    setView: true, // automatically sets the map view to the user's location, enabled if `follow` is true
    stopFollowingOnDrag: false, // stop following when the map is dragged if `follow` is true (deprecated, see below)
    circleStyle: {},  // change the style of the circle around the user's location
    markerStyle: {},
    followCircleStyle: {},  // set difference for the style of the circle around the user's location while following
    followMarkerStyle: {},
    circlePadding: [0, 0], // padding around accuracy circle, value is passed to setBounds
    metric: true,  // use metric or imperial units
    onLocationError: function(err) {alert(err.message)},  // define an error callback function
    onLocationOutsideMapBounds:  function(context) { // called when outside map boundaries
            alert(context.options.strings.outsideMapBoundsMsg);
    },
    strings: {
        title: "disini",  // title of the locate control
        popup: "disini {distance} {unit} from this point",  // text to appear if user clicks on circle
        outsideMapBoundsMsg: "You seem located outside the boundaries of the map" // default message for onLocationOutsideMapBounds
    }
       
           

}));

map.on('startfollowing', function() {
    map.on('dragstart', lc.stopFollowing);
}).on('stopfollowing', function() {
    map.off('dragstart', lc.stopFollowing);
});



L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);



//setting ICON
var kantor = L.icon({
    iconUrl: url_link+'assets/img/icon/kantor.png',
    iconSize:     [25, 40] // size of the icon
     // point from which the popup should open relative to the iconAnchor
});

var center = L.icon({
    iconUrl: url_link+'assets/img/icon/center.png',
    iconSize:     [25, 40] // size of the icon
     // point from which the popup should open relative to the iconAnchor
});

var anggota = L.icon({
    iconUrl: url_link+'assets/img/icon/anggota.png',
    iconSize:     [25, 40] // size of the icon
     // point from which the popup should open relative to the iconAnchor
});

var lainya = L.icon({
    iconUrl: url_link+'assets/img/icon/informasi.png',
    iconSize:     [25, 40] // size of the icon
    // point from which the popup should open relative to the iconAnchor
});

var hitam = L.icon({
    iconUrl: url_link+'assets/img/icon/hitam.png',
    iconSize:     [25, 40] // size of the icon
    // point from which the popup should open relative to the iconAnchor
});
var kuning = L.icon({
    iconUrl: url_link+'assets/img/icon/kuning.png',
    iconSize:     [25, 35] // size of the icon
    // point from which the popup should open relative to the iconAnchor
});
var hijau = L.icon({
    iconUrl: url_link+'assets/img/icon/hijau.png',
    iconSize:     [25, 40] // size of the icon
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
    var isi='';
    var ikon_center='hitam';
    $.getJSON( url_link + "api/center.php", function( data ) {
      var items = [];
      $.each( data, function( i, field ) {
        var lat1 = data[i]['latitude'];
        var lng1 = data[i]['longitude'];
        var status = data[i]['status_center'];
          isi = "<h3> Center : "+data[i]['no_center']+"</h3>";
          isi += " Hari : "+ data[i]['hari'];
          isi += "<br> Warna  : "+ data[i]['status_center'];
          isi += "<br> Anggota : "+ data[i]['anggota_center']; 
          isi += "<br> JAM kumpulan : "+ data[i]['jam_center'] +" . ";
          isi += "<br> staff : "+ data[i]['nama_karyawan'];
          isi += "<br> Cabang : "+ data[i]['nama_cabang'];
          isi += "<br> <a href='https://www.google.com/maps/place/"+lat1+","+lng1+"/"+lat1+","+lng1+",17z/data=!3m1!4b1'>Direct</a>";
            if(status=='hijau'){
              ikon_center=hijau;
            }
            else if(status=='kuning'){
              ikon_center=kuning;
            }
            else if(status=='hitam'){
              ikon_center=hitam;
            }
            else if(status=='merah'){
              ikon_center=center;
            }
            
            L.marker([data[i]['latitude'],data[i]['longitude']],{icon:ikon_center}).addTo(map)
            .bindPopup(isi);
      });
          
     
      
    });



    //selain center
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


  var titik = L.marker([e.latlng.lat,e.latlng.lng]).addTo(map)
    .bindPopup( text)
    .openPopup();


    $("#center").attr('href',center +"&lat="+lat+"&lng="+lng);
    $("#anggota").attr('href',anggota +"&lat="+lat+"&lng="+lng);
    $("#pu").attr('href',pu +"&lat="+lat+"&lng="+lng);
});


