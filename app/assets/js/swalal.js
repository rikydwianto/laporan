// Buka modal saat tombol diklik
$(document).ready(function () {
  var $carousel = $("#birthdayCarousel");
  var $nextBtn = $("#nextBtn");
  var $prevBtn = $("#prevBtn");
  var tutup = $("#tutup");
  tutup.hide();
  // $prevBtn.hide();
  $carousel.on("slid.bs.carousel", function () {
    var $activeItem = $carousel.find(".carousel-item.active");
    var totalItems = $carousel.find(".carousel-item").length;
    var currentIndex = $carousel.find(".carousel-item").index($activeItem) + 1;

    // Jika slide terakhir aktif, jalankan proses AJAX
    if (currentIndex === totalItems) {
      tutup.show();
    } else {
      tutup.hide();
    }
    // if (currentIndex === 1) {
    //   $prevBtn.hide(); // Nonaktifkan tombol "Sebelumnya"
    // } else {
    //   $prevBtn.show(); // Nonaktifkan tombol "Sebelumnya"
    // }
  });

  tutup.on("click", function () {
    $.ajax({
      url: url + "api/proses_tutup.php",
      type: "GET",
      success: function (data, status) {
        setTimeout(() => {
          window.location.reload();
        }, "1000");
      },
      error: function (xhr, status, error) {
        console.error("Request failed: " + status + ", " + error);
      },
    });
  });
  $("#openModalBtn").on("click", function () {
    Swal.fire({
      title: "Apakah kamu yakin melanjutkan ini?",
      text: "Apa kamu yakin lagi sendiri?",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, saya sendiri",
    }).then((result) => {
      if (result.isConfirmed) {
        var birthdayModal = new bootstrap.Modal(
          document.getElementById("birthdayModal"),
          {
            backdrop: "static",
            keyboard: false,
          }
        );
        birthdayModal.show();
      } else {
        Swal.fire("Pastikan dibuka dengan sendiri ya");
      }
    });
  });
});
