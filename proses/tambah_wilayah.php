<div class='content table-responsive'>
    <h2 class='page-header'>DAFTAR WILAYAH </h2>
    <i>Menambahkan KECAMATAN untuk cabang ini</i>
    <form action="<?=$url?>proses/proses_tambah_wilayah.php" method="get">
        <input type="hidden" name="menu" value='daftar_wilayah'>
        <table class='table'>
            <tr>
                <td>Provinsi</td>
                <td>
                    <div id='provinsi'>
                        </div:id>

                </td>
            </tr>
            <tr>
                <td>Kabupaten</td>
                <td>
                    <div id='kabupaten'></div>
                </td>
            </tr>

            <tr>
                <td>Kecamatan</td>
                <td>
                    <div id='kecamatan'></div>
                </td>
            </tr>
            <tr>
                <td>DESA</td>
                <td>
                    <div id='desa1'></div>
                </td>
            </tr>

        </table>

    </form>
</div>

