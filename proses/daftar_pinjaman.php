<?php @$tgl_awal  = $_GET['tgl_awal'];
        @$tgl_akhir = $_GET['tgl_akhir']; ?>
<form action="" method="get" id='form_pinjaman'>
    <input type="hidden" name="menu" value="monitoring">
        <div class="col-md-4">
            <h3>DARI</h3>
            <input type="date" value="<?=($tgl_awal==""?date("Y-m-d"):$tgl_awal)?>" required name="tgl_awal" class='form-control' id="">
            
            
        </div>
        <div class="col-md-4">
            <h3>SAMPAI</h3>
            <input type="date" required value="<?=($tgl_akhir==""?date("Y-m-d"):$tgl_akhir)?>" name="tgl_akhir" class='form-control' id="">
            <input type="submit" value="PRINT" name='daftar_pinjaman' class='btn btn-danger btn-md btn-info'>

        </div>
    </form>
    <script>
        var myForm = document.getElementById('form_pinjaman');
        myForm.onsubmit = function() {
            var isi = $('#form_pinjaman').serialize();
            // alert(isi);
            var w = window.open('<?=$url."print_pinjaman.php?"?>'+isi,'Print Daftar Pinjaman','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=720,left = 0,top = 0');
            this.target = 'Popup_Window';
        };
    </script>
