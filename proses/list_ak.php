<?php @$tgl_awal  = $_GET['tgl_awal'];
        @$tgl_akhir = $_GET['tgl_akhir']; ?>
        <div class='content table-responsive'>
    <h2 class='page-header'>REKAP ANGGOTA MASUK / ANGGOTA KELUAR </h2>
    <!-- <i>DAFTAR NASABAH/MANTAN NASABAH </i> -->
    <hr />
    <br/>
    <br/>
    <div class="col-lg-4">

        <!-- <form method="post"  enctype="multipart/form-data"> -->
        <form action="" method="get" id='form_ak'>
            <input type="hidden" name="menu" value="monitoring">
                <div class="col-md-8">
                    <h3>DARI</h3>
                    <input type="date" value="<?=($tgl_awal==""?date("Y-m-d"):$tgl_awal)?>" required name="tgl_awal" class='form-control' id="">
                    
                    
                </div>
                <div class="col-md-8">
                    <h3>SAMPAI</h3>
                    <input type="date" required value="<?=($tgl_akhir==""?date("Y-m-d"):$tgl_akhir)?>" name="tgl_akhir" class='form-control' id="">
                    <input type="submit" value="PRINT" name='daftar_pinjaman' class='btn btn-danger btn-md btn-info'>

                </div>
            </form>
    <script>
        var myForm = document.getElementById('form_ak');
        myForm.onsubmit = function() {
            var isi = $('#form_ak').serialize();
            // alert(isi);
            var w = window.open('<?=$url."print_ak.php?"?>'+isi,'DAFTAR ANGGOTA KELUAR','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=720,left = 0,top = 0');
            this.target = 'Popup_Window';
        };
    </script>


    
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
            let berdasarkan_hasil = $("#berdasarkan").find('option').filter(':selected').text();
            // alert(berdasarkan_hasil);
            $.get(url + "api/cek_nik.php?cari=" + cari + "&id="+cabang+"&kategori=" + kategori+"&berdasarkan=" + berdasarkan +"&berdasarkan_hasil=" + berdasarkan_hasil , function(data, status) {
                $("#hasil").html(data);

            });
        });
    });
</script>
