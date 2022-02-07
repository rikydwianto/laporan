<?php @$tgl_awal  = $_GET['tgl_awal'];
        @$tgl_akhir = $_GET['tgl_akhir']; ?>
<!-- <form action="" method="get" id='form_pinjaman'> -->
    <input type="hidden" name="menu" value="monitoring">
        <div class="col-md-4">
            <h3>DARI</h3>
            <input type="date" id="tgl1" value="<?=($tgl_awal==""?date("Y-m-d"):$tgl_awal)?>" required name="tgl_awal" class='form-control' >
            
            
        </div>
        <div class="col-md-4">
            <h3>SAMPAI</h3>
            <input type="date"  id="tgl2" required value="<?=($tgl_akhir==""?date("Y-m-d"):$tgl_akhir)?>" name="tgl_akhir" class='form-control' >
            <!-- <input type="submit" value="PRINT" name='daftar_pinjaman' class='btn btn-danger btn-md btn-info'> -->
            <!-- <a href="<?=$url."print_pinjaman.php?menu=monitoring&tgl_awal=".date("Y-m-d")."&tgl_akhir=".date("Y-m-d")?>" id='btn_print1' class="btn btn-primary">Print Daftar pinjaman</a> -->
            <button onclick="btn_click()" class='btn btn-danger' id='print_biasa'>Print 1</button>
            <button onclick="btn_click2()" class='btn btn-primary' id='print_biasa'>Print v.2</button>
        </div>
    <!-- </form> -->
    <script>
        var url = "<?=$url?>";
        $("#tgl1").on("change",function(){
            // alert(1)
        })
        var url_upk = url ;
                // $("#link_upk").attr("href",url_upk);

        var myForm = document.getElementById('form_pinjaman');
        myForm.onsubmit = function() {
            var isi = $('#form_pinjaman').serialize();
            // alert(isi);
            var w = window.open('<?=$url."print_pinjaman.php?"?>'+isi,'Print Daftar Pinjaman','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=720,left = 0,top = 0');
            this.target = 'Popup_Window';
        };
        $("#btn_print1").on("click",function() {
            var isi = $('#form_pinjaman').serialize();
            // alert(isi);
            var w = window.open('<?=$url."print_pinjaman.php?"?>'+isi,'Print Daftar Pinjaman','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=720,left = 0,top = 0');
            this.target = 'Popup_Window';
        });
        function btn_click(){
            // alert(00);
            var tgl1 = $("#tgl1").val();
            var tgl2 = $("#tgl2").val();
            // alert(tgl1 + tgl2)
            var w = window.open('<?=$url?>print_pinjaman.php?menu=monitoring&tgl_awal='+tgl1+'&tgl_akhir=' + tgl2,'Print Daftar Pinjaman','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=720,left = 0,top = 0');

        }
        function btn_click2(){
            // alert(00);
            var tgl1 = $("#tgl1").val();
            var tgl2 = $("#tgl2").val();
            // alert(tgl1 + tgl2)
            var w = window.open('<?=$url?>print_pinjaman1.php?menu=monitoring&tgl_awal='+tgl1+'&tgl_akhir=' + tgl2,'Print Daftar Pinjaman','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1200,height=720,left = 0,top = 0');

        }
    </script>
