<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
require '../vendor/autoload.php';

use Smalot\PdfParser\Parser;

$url_parser = $_GET['url'];
//'https://www.komida.co.id/mdismo/verifile.php?kode=038yfhszhywxhamnzywmrfbnzwtcrzypafbxzbmhngwwgrwnmcbwx';
$data = [];
$html = file_get_contents($url_parser);
if ($html) {
    $dom = new DOMDocument;

    libxml_use_internal_errors(true);

    $dom->loadHTML($html);

    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    $javascriptString = $html;

    $pattern = '/location:\s*{url:\s*"([^"]+)"/';
    preg_match($pattern, $javascriptString, $matches);

    $url_pdf = isset($matches[1]) ? $matches[1] : '';

    if ($url_pdf == "") {
        $pesan = "FILE tidak ditemukan!";
        $status = 'error';
    } else {
        $pdfParser = new Parser();

        $pdf = $pdfParser->parseFile($url_pdf);

        $allLines = [];
        foreach ($pdf->getPages() as $page) {
            // Pisahkan teks menjadi baris-baris
            $lines = explode("\n", $page->getText());

            // Gabungkan baris-baris ke dalam array utama
            $allLines = array_merge($allLines, $lines);
        }

        $bulanArray = array(
            'Januari' => '01',
            'Februari' => '02',
            'Maret' => '03',
            'April' => '04',
            'Mei' => '05',
            'Juni' => '06',
            'Juli' => '07',
            'Agustus' => '08',
            'September' => '09',
            'Oktober' => '10',
            'November' => '11',
            'Desember' => '12'
        );
        // Loop melalui elemen-elemen array
        foreach ($allLines as $elemen) {
            // Cocokkan format tanggal dengan ekspresi reguler
            if (preg_match('/\b(\d{2} [A-Za-z]+ \d{4})\b/', $elemen, $matches)) {
                $tanggal = $matches[1];
                break; // Hentikan iterasi setelah menemukan tanggal
            }
        }
        $tanggal = explode(" ", $tanggal);
        $bulan = sprintf('%02d', $bulanArray[$tanggal[1]][1]);
        $tahun = $tanggal[2];
        $tgl = $tanggal[0];

        $tanggal = $tahun . '-' . $bulan . '-' . $tgl;
        // echo $tanggal;
        $niknamacabang = explode(' - ', $allLines[1]);
        $nik = $niknamacabang[0];
        $nama = $niknamacabang[1];
        $cabang = strpos($niknamacabang[2], '(');
        $cabang = trim(substr($niknamacabang[2], 0, $cabang));
        $data_dtc = [];
        foreach ($allLines as $line) {
            $baris = $line;
            $pecah = explode(' ', $baris);
            if (count($pecah) > 11) {
                $center = (int) $pecah[0] + 0;
                if ($center > 0) {
                    $dtc['center'] = "$center";
                    $dtc['anggota'] = $pecah[1];
                    $dtc['hadir'] = $pecah[2];
                    $dtc['bayar'] = $pecah[3];
                    $dtc['tidak_bayar'] = $pecah[4];
                    $dtc['pencairan'] = angka_mentah($pecah[5]);
                    $dtc['dnr'] = angka_mentah($pecah[6]);
                    $dtc['drop_masuk'] = angka_mentah($pecah[7]);
                    $dtc['drop_keluar'] = angka_mentah($pecah[8]);
                    $dtc['angsuran'] = angka_mentah($pecah[9]);
                    $dtc['simpanan_masuk'] = angka_mentah($pecah[10]);
                    $dtc['simpanan_keluar'] = angka_mentah($pecah[11]);
                    $dtc['jumlah_pengambil_simpanan'] = angka_mentah($pecah[12]);
                    $dtc['jumlah_anggota_keluar'] = angka_mentah($pecah[13]);
                    $dtc['total_pendapatan'] = angka_mentah($pecah[14]);
                    array_push($data_dtc, $dtc);
                }
            }
        }

        $pesan = "Data berhasil diload";
        $status = 'succes';
        $data = array(
            'nik' => $nik,
            'nama' => $nama,
            'cabang' => $cabang,
            'tanggal' => $tanggal,
            'url_pdf' => $url_pdf,
            'detail' => $data_dtc

        );
    }
} else {
    $pesan = "URL tidak ditemukan";
    $status = 'error';
}


echo json_encode(array('status' => $status, 'message' => $pesan, 'data' => $data));