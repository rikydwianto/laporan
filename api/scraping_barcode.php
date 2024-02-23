<?php

// URL yang akan di-scrape
$url = 'https://www.komida.co.id/mdismo/verifile.php?kode=038yfhszhywxhamnzywmrfbnzwtcrzypafbxzbmhngwwgrwnmcbwx';

// Ambil konten halaman web
$html = file_get_contents($url);

// Cek apakah konten berhasil diambil
if ($html) {
    // Buat objek DOMDocument
    $dom = new DOMDocument;

    // Tampilkan error jika parsing HTML gagal
    libxml_use_internal_errors(true);

    // Memuat HTML ke dalam objek DOMDocument
    $dom->loadHTML($html);

    // Menghentikan tampilan error parsing HTML
    libxml_clear_errors();

    // Buat objek DOMXPath untuk query XPath
    $xpath = new DOMXPath($dom);
    var_dump($xpath->query("script"));

    $javascriptString = '
    document.addEventListener("adobe_dc_view_sdk.ready", function(){ 
        var adobeDCView = new AdobeDC.View({clientId: "9ff59aaa5ba649a589ec7373b230864b", divId: "adobe-dc-view"});
        adobeDCView.previewFile({
            content:{location: {url: "https://www.komida.co.id/mdismo/report/transaksi/2024-02-05/038_009366_2022_2024-02-05.pdf"}},
            metaData:{fileName: "transaksi_038_009366_2022_2024-02-05.pdf"}
        }, {});
    });
';

    // Temukan URL dengan ekspresi reguler
    $pattern = '/location:\s*{url:\s*"([^"]+)"/';
    preg_match($pattern, $javascriptString, $matches);

    // Ambil URL dari hasil pencocokan
    $url = isset($matches[1]) ? $matches[1] : '';

    // Tampilkan URL
    echo "URL: $url\n";
} else {
    echo "Gagal mengambil konten dari $url";
}