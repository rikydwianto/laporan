
function pilih_prov(){
    $(document).ready(function(){
        $.get(url_link+"api/peta_wilayah.php", function(data, status){
           $("#provinsi").html(data);
          });
    });
  }
  pilih_prov();
  function pilih_kab(){
      var prov = $("#prov").val();
      var kab = $("#kab").val();
      $.get(url_link+"api/peta_wilayah.php?prov=" + prov, function(data, status){
        $("#kabupaten").html(data);
       });
  }
  function pilih_kec(){
      var kab = $("#kab").val();
      $.get(url_link+"api/peta_wilayah.php?kab=" + kab, function(data, status){
        $("#kecamatan").html(data);
       });
  }
  function pilih_desa(){
      var kec = $("#kec").val();
      $.get(url_link+"api/peta_wilayah.php?kec=" + kec, function(data, status){
        $("#desa1").html(data);
       });
  }