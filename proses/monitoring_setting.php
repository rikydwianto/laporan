<div class='content table-responsive'>
    <a href="javascript:void(0)" onclick="konfirmasiUpload()" class="btn btn-danger">UBAH NAMA STAFF PADA MONITORING SESUAI PEMEGANG CENTER MEETING</a><br>
   <h2> <code>PASTIKAN SUDAH UPLOAD CENTER MEETING/NASABAH SUDAH TERUPDATE</code></h2>
</div>
<script> 
var url = "<?= $url ?>";
var menu = "<?= $menu ?>";
    promptTambahRandom();

    
function promptTambahRandom() {
    // Generate dua angka acak antara 1 dan 10
    var angka1 = Math.floor(Math.random() * 10) + 1;
    var angka2 = Math.floor(Math.random() * 10) + 1;

    var jawaban = prompt("Berapa " + angka1 + " + " + angka2 + "?");

    // Melakukan pengecekan jawaban
    if (parseInt(jawaban) === angka1 + angka2) {
        alert("Jawaban anda benar. Terimakasih");
        // Lanjutkan dengan proses lain jika diperlukan
    } else {
        alert("Jawaban salah. Proses dibatalkan.");
        window.location.href = url;// Gantilah "halaman_awal.html" dengan URL halaman awal Anda.

    }
}

    function konfirmasiUpload() {
    var jawabanPertama = prompt("Apakah Anda sudah UPLOAD CENTER MEETING/UPDATE NASABAH? (YA/NO)").toUpperCase();
    
    if (jawabanPertama.toLocaleLowerCase() === 'ya') {
        var jawabanKedua = prompt("Apakah Anda yakin akan melanjutkan proses ini? (YA/NO)").toUpperCase();
        
        if (jawabanKedua.toLocaleLowerCase() === 'ya') {
            alert("Proses akan dilanjutkan. klik ok lalu tunggu hingga selesai,\nJika selesai akan mengarah kehalaman selanjutnya");
            window.location.href = url+menu+"monitoring&proses_importan&rubah"; 
        } else if (jawabanKedua.toLocaleLowerCase() === 'no') {
            alert("Proses dibatalkan.");
        } else {
            alert("Jawaban tidak valid. Silakan jawab dengan 'ya' atau 'NO'.");
        }
    } else if (jawabanPertama.toLocaleLowerCase() === 'no') {
        alert("SILAHKAN UPDATE CENTER MEETING/NASABAH");
    } else {
        alert("Jawaban tidak valid. Silakan jawab dengan 'ya' atau 'NO'.");
    }
}
</script>