<div class='content table-responsive'>
    <h2 class='page-header'>CARI NASABAH / MANTAN NASABAH </h2>
    <i>DAFTAR NASABAH/MANTAN NASABAH </i>
    <hr />
    <br/>
    <br/>
    <br/>
    <div class="col-lg-4">

        <!-- <form method="post"  enctype="multipart/form-data"> -->
            <div class="input-group">
               
            <span class="input-group-text">CARI BERDASARKAN NIK, NAMA, SUAMI, ID NASABAH</span>
            <select name="" id="kategori" class='btn btn-info'>
                <option value="aktif">NASABAH AKTIF</option>
                <option value="mantan">MANTAN NASABAH</option>
            </select>
            <select id='berdasarkan' class='btn btn-success '>
                <option value="no_ktp">NIK</option>
                <option value="id_nasabah">ID NASABAH</option>
                <option value="nama_nasabah">NAMA</option>
                <option value="suami_nasabah">SUAMI</option>
                <option value="no_center">CENTER</option>
                <option value="hp_nasabah">HP</option>
                <option value="alamat_nasabah">ALAMAT NASABAH</option>

            </select>
            <input type="text" id='cari' aria-label="First name" class="form-control">
            </div>

        <!-- </form> -->
    </div>
    <div class="col-lg-12">
        <div id="hasil">

        </div>
    </div>

    
</div>
<script>
    var url = "<?= $url ?>";
    var cabang = "<?= $id_cabang ?>";
    $(document).ready(function(){
        $("#cari").on('keyup',function(event){
            event.preventDefault();
            let cari = $(this).val();
            let kategori = $("#kategori").val();
            let berdasarkan = $("#berdasarkan").val();
            $.get(url + "api/cek_nik.php?cari=" + cari + "&id="+cabang+"&kategori=" + kategori+"&berdasarkan=" + berdasarkan , function(data, status) {
                $("#hasil").html(data);

            });
        });
    });
</script>