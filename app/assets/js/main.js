$(document).ready(function () {
  ctx = document.getElementById("chartEmail").getContext("2d");

  let tiga_hari = $("#tiga_hari").val();
  let kurang_normal = $("#kurang_normal").val();
  let normal = $("#normal").val();
  myChart = new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["3-14 Hari", "0-3 Hari", "Lebih dari 14 hari"],
      datasets: [
        {
          label: "Monitoring",
          pointRadius: 0,
          pointHoverRadius: 0,
          backgroundColor: ["#4acccd", "#fcc468", "#ef8157"],
          borderWidth: 0,
          data: [normal, tiga_hari, kurang_normal],
        },
      ],
    },

    options: {
      legend: {
        display: true,
      },

      pieceLabel: {
        render: "percentage",
        fontColor: ["white"],
        precision: 2,
      },

      tooltips: {
        enabled: true,
      },

      scales: {
        yAxes: [
          {
            ticks: {
              display: false,
            },
            gridLines: {
              drawBorder: false,
              zeroLineColor: "transparent",
              color: "rgba(255,255,255,0.05)",
            },
          },
        ],

        xAxes: [
          {
            barPercentage: 1.6,
            gridLines: {
              drawBorder: false,
              color: "rgba(255,255,255,0.1)",
              zeroLineColor: "transparent",
            },
            ticks: {
              display: false,
            },
          },
        ],
      },
    },
  });
});

function pindahhalaman(url) {
  const progressBarContainer = document.getElementById(
    "progress-bar-container"
  );
  const progressBar = document.getElementById("progress-bar");

  progressBarContainer.style.display = "block"; // Tampilkan progress bar
  let width = 0;

  const interval = setInterval(function () {
    if (width >= 100) {
      clearInterval(interval);
      window.location.href = url; // Pindah halaman setelah progress bar penuh
    } else {
      width += 10; // Kecepatan progres bisa disesuaikan
      progressBar.style.width = width + "%";
    }
  }, 100); // Interval waktu antara setiap increment bisa disesuaikan
}

// Cara penggunaan
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll("a"); // Ambil semua elemen <a>

  links.forEach(function (link) {
    link.addEventListener("click", function (e) {
      // Cek apakah link memiliki href dan tidak mengarah ke anchor link (#)
      if (link.href && link.href !== "#") {
        e.preventDefault(); // Mencegah aksi default
        pindahhalaman(link.href); // Panggil fungsi smoothTransitionWithProgress dengan href sebagai argumen
      }
    });
  });
});

$(document).ready(function () {
  var table = $("#daftar_staff").DataTable({
    paging: false, // Nonaktifkan pagination
    lengthChange: false, // Sembunyikan opsi "entries per page"
    info: false, // Nonaktifkan informasi "Showing X to Y of Z entries"
    searching: false, // Tetap aktifkan pencarian
    ordering: true, // Tetap aktifkan sorting
    autoWidth: true, // Otomatis atur lebar kolom
    language: {
      emptyTable: "Tidak ada data yang tersedia", // Pesan jika tidak ada data
    },
  });
  function formatDetail(data) {
    // Menggunakan data detail yang disimpan dalam atribut data
    return `
    <table class='table table-bordered '>
          <tr>
            <th>Total Anggota</th>
            <th>${data.total_anggota}</th>
          </tr>
          <tr>
            <td> - Pinjaman Umum</td>
            <td>${data.pu}</td>
          </tr>
          <tr>
            <td> - Pinjaman Mikrobisnins</td>
            <td>${data.pmb}</td>
          </tr>
          <tr>
            <td> - Pinjaman Sanitasi</td>
            <td>${data.psa}</td>
          </tr>
          <tr>
            <td> - Pinjaman Pendidikan</td>
            <td>${data.ppd}</td>
          </tr>
          <tr>
            <td> - Pinjaman Arta</td>
            <td>${data.arta}</td>
          </tr>
          <tr>
            <td> - Pinjaman Renovasi Rumah</td>
            <td>${data.prr}</td>
          </tr>
          <tr>
            <th>Total Monitoring</th>
            <th>${data.total_monitoring}</th>
          </tr>
          <tr>
            <th>Keterangan</th>
            <th>${data.keterangan}</th>
          </tr>
        </table>`;
  }
  // Event listener untuk tombol "Detail"
  $("#daftar_staff tbody").on("click", ".detail-btn", function () {
    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Jika baris detail sedang ditampilkan, sembunyikan
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Tampilkan baris detail
      var detailData = $(tr).data("detail"); // Ambil data detail dari atribut data
      row.child(formatDetail(detailData)).show();
      tr.addClass("shown");
    }
  });
});
$(document).ready(function () {
  var table = $("#list_monitoring").DataTable({
    paging: true, // Nonaktifkan pagination
    lengthChange: false, // Sembunyikan opsi "entries per page"
    info: false, // Nonaktifkan informasi "Showing X to Y of Z entries"
    searching: true, // Tetap aktifkan pencarian
    ordering: true, // Tetap aktifkan sorting
    autoWidth: true, // Otomatis atur lebar kolom
    language: {
      emptyTable: "Tidak ada data yang tersedia", // Pesan jika tidak ada data
    },
  });
  function formatDetail(data) {
    // Menggunakan data detail yang disimpan dalam atribut data
    /* 
     'id_anggota' => ($pinjaman['id_detail_nasabah']),
      'disburse' => angka($pinjaman['jumlah_pinjaman']),
      'produk' => $pinjaman['produk'],
      'tujuan_pinjaman' => $pinjaman['tujuan_pinjaman'],
      'pinjaman_ke' => $pinjaman['pinjaman_ke'],
      'tgl_cair' => $pinjaman['tgl_cair'],
      'no_hp' => $pinjaman['no_hp'],
      'jk_waktu' => $pinjaman['jk_waktu'],
    */
    return `
    <table class='table table-bordered '>
          <tr>
            <th>ID Anggota</th>
            <td>${data.id_anggota}</td>
          </tr>
          <tr>
            <th>Produk Pinjaman</th>
            <td>${data.produk}</td>
          </tr>
          <tr>
            <th>Total Cair</th>
            <td>${data.disburse}</td>
          </tr>
          <tr>
            <th>Pinjaman Ke</th>
            <td>${data.pinjaman_ke}</td>
          </tr>
          <tr>
            <th>Jangka Waktu</th>
            <td>${data.jk_waktu}</td>
          </tr>
        
          <tr>
            <th>Tujuan Pinjaman</th>
            <td>${data.tujuan_pinjaman}</td>
          </tr>
        
          <tr>
            <th>Tanggal Cair</th>
            <td>${data.tgl_cair}</td>
          </tr>
        
        
          <tr>
            <th>Kontak</th>
            <td>
            <a aria-label="Chat on WhatsApp" href="https://wa.me/62${data.no_hp}">${data.no_hp}<a />
            </td>
          </tr>
          
          <tr>
            <th>Tandai Sudah Monitoring</th>
            <td>
            <button class="btn btn-danger btn-sm" id='cek_${data.id_pinjaman}' value='${data.status}' onclick="monitoring('${data.id_pinjaman}', '${data.loanno}')">Belum Monitoring</button>

            </td>
          </tr>
          
          
        </table>`;
  }
  // Event listener untuk tombol "Detail"
  $("#list_monitoring tbody").on("click", ".detail-btn", function () {
    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Jika baris detail sedang ditampilkan, sembunyikan
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Tampilkan baris detail
      var detailData = $(tr).data("detail"); // Ambil data detail dari atribut data
      row.child(formatDetail(detailData)).show();
      tr.addClass("shown");
    }
  });
});

function monitoring(id, detail) {
  var cek = $("#cek_" + id).val();
  // alert(url + "api/monitoring.php");
  // return;

  if (cek == "belum") {
    Swal.fire({
      title: "Apakah kamu yakin?",
      text: "Kamu yakin akan menandai ini sudah dimonitoring?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yaa, Monitoring!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: url + "api/monitoring.php",
          type: "GET",
          data: {
            mtr: "sudah",
            id: id,
            detail: detail,
            newapp: "ada",
          },
          success: function (data, status) {
            setTimeout(function () {
              $("#cek_" + id).val("sudah");
              $("#cek_" + id).removeClass("btn-danger");
              $("#cek_" + id).addClass("btn-info");
              $("#cek_" + id).html("Sudah Dimonitoring");
            }, 100);
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Berhasil",
              showConfirmButton: false,
              timer: 1000,
            });
          },
          error: function (xhr, status, error) {
            console.error("Request failed: " + status + ", " + error);
          },
        });
      }
    });
  } else {
    Swal.fire({
      title: "Apakah kamu yakin?",
      text: "Kamu yakin akan menandai ini sudah dimonitoring?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yaa, Batalkan Monitoring!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: url + "api/monitoring.php",
          type: "GET",
          data: {
            mtr: "belum",
            id: id,
            detail: detail,
            newapp: "ada",
          },
          success: function (data, status) {
            setTimeout(function () {
              $("#cek_" + id).val("belum");
              $("#cek_" + id).removeClass("btn-info");
              $("#cek_" + id).addClass("btn-danger");
              $("#cek_" + id).html("Belum dimonitoring");
            }, 100);
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Berhasil",
              showConfirmButton: false,
              timer: 1000,
            });
          },
          error: function (xhr, status, error) {
            console.error("Request failed: " + status + ", " + error);
          },
        });
      }
    });
  }
}

function kembali() {
  // Kembali ke halaman sebelumnya
  window.history.back(); // atau window.history.go(-1);
}

$(document).ready(function () {
  var table = $("#rekap_monitoring").DataTable({
    paging: false, // Nonaktifkan pagination
    lengthChange: false, // Sembunyikan opsi "entries per page"
    info: false, // Nonaktifkan informasi "Showing X to Y of Z entries"
    searching: true, // Tetap aktifkan pencarian
    ordering: true, // Tetap aktifkan sorting
    autoWidth: true, // Otomatis atur lebar kolom
    language: {
      emptyTable: "Tidak ada data yang tersedia", // Pesan jika tidak ada data
    },
  });
  function formatDetail(data) {
    let tableHTML = "";
    data.monitoring.forEach((row) => {
      tableHTML += `
          <tr>
              <td><a href='index.php?menu=pinjaman&filter&staff=${row.id}'>${row.staff}</a></td>
              <td>${row.total}</td>
          </tr>
      `;
    });

    return `
    <table class='table table-bordered '>
     <tr>
              <th>Staff</th>
              <th>Total</th>
          </tr>
      ${tableHTML}
          
          
        </table>`;
  }
  // Event listener untuk tombol "Detail"
  $("#rekap_monitoring tbody").on("click", ".detail-btn", function () {
    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Jika baris detail sedang ditampilkan, sembunyikan
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Tampilkan baris detail
      var detailData = $(tr).data("detail"); // Ambil data detail dari atribut data
      row.child(formatDetail(detailData)).show();
      tr.addClass("shown");
    }
  });
});

$(document).ready(function () {
  var table = $("#list_rekap_minggu").DataTable({
    paging: false, // Nonaktifkan pagination
    lengthChange: false, // Sembunyikan opsi "entries per page"
    info: false, // Nonaktifkan informasi "Showing X to Y of Z entries"
    searching: true, // Tetap aktifkan pencarian
    ordering: true, // Tetap aktifkan sorting
    autoWidth: true, // Otomatis atur lebar kolom
    language: {
      emptyTable: "Tidak ada data yang tersedia", // Pesan jika tidak ada data
    },
  });
  function formatDetail(data) {
    let tableHTML = "";
    console.log(data);
    data.minggu.forEach((row) => {
      tableHTML += `
          <tr>
              <td><a href='index.php?menu=pinjaman&filter_minggu&tgl_1=${row.tgl_awal}&tgl_2=${row.tgl_akhir}'>${row.minggu_ke}</a></td>
              <td><a href='index.php?menu=pinjaman&filter_minggu&tgl_1=${row.tgl_awal}&tgl_2=${row.tgl_akhir}'>${row.tgl_awal} sd ${row.tgl_akhir}</a></td>
              <td>${row.total}</td>
          </tr>
      `;
    });

    console.log(tableHTML);
    return `
    <table class='table table-bordered '>
     <tr>
              <th>Minggu ke</th>
              <th>Priode</th>
              <th>Total</th>
          </tr>
      ${tableHTML}
          
          
        </table>`;
  }
  // Event listener untuk tombol "Detail"
  $("#list_rekap_minggu tbody").on("click", ".detail-btn", function () {
    var tr = $(this).closest("tr");
    var row = table.row(tr);

    if (row.child.isShown()) {
      // Jika baris detail sedang ditampilkan, sembunyikan
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Tampilkan baris detail
      var detailData = $(tr).data("detail_json"); // Ambil data detail dari atribut data
      row.child(formatDetail(detailData)).show();
      tr.addClass("shown");
    }
  });
});
