// Buka modal saat tombol diklik
$(document).ready(function () {
  var $carousel = $("#birthdayCarousel");
  var $nextBtn = $("#nextBtn");
  var $prevBtn = $("#prevBtn");
  var tutup = $("#tutup");
  tutup.hide();
  tutup.prop("disabled", true);
  $prevBtn.hide();
  $carousel.on("slid.bs.carousel", function () {
    var $activeItem = $carousel.find(".carousel-item.active");
    var totalItems = $carousel.find(".carousel-item").length;
    var currentIndex = $carousel.find(".carousel-item").index($activeItem) + 1;

    // Jika slide terakhir aktif, jalankan proses AJAX
    if (currentIndex === totalItems) {
      tutup.show();
    } else {
      tutup.hide();
      tutup.prop("disabled", true);
    }
    if (currentIndex === 1) {
      $prevBtn.hide(); // Nonaktifkan tombol "Sebelumnya"
    } else {
      $prevBtn.show(); // Nonaktifkan tombol "Sebelumnya"
    }
  });

  var forbiddenWords = [
    "terimakasih",
    "terima kasih",
    "thanks",
    "thanksalot",
    "thankyou",
    "thanks a lot",
    "gak ada",
    "dll",
    "nothing",
    "empty",
    "no",
    "none",
    "null",
    "kosong",
    "na",
    "tidak ada komentar",
    "tidak ada informasi",
    "tidak berlaku",
    "tidak ditentukan",
    "tidak tersedia",
    "tidak relevan",
    "tidak penting",
    "tidak ada input",
    "kurangnya input",
    "hanya karena",
    "hanya",
    "apa saja",
    "semua",
    "beberapa",
    "banyak",
    "sedikit",
    "semua orang",
    "tidak ada",
    "siapa saja",
    "tidak ada yang spesial",
    "tidak ada lagi",
    "tidak masalah",
    "acuh tak acuh",
    "opsional",
    "tidak perlu",
    "apa pun",
    "tidak banyak",
    "tidak tahu",
    "tidak peduli",
    "apa saja",
    "entah",
    "tidak pasti",
    "tidak penting",
    "tidak relevan",
    "tidak berlaku",
  ];

  $("#harapan").on("input", function () {
    var harapan = $(this).val().trim();
    var errorMessage = "";
    var words = harapan.split(/\s+/); // Split by any whitespace

    // Check if field is empty
    if (harapan === "") {
      errorMessage = "Harapan tidak boleh kosong.";
    }
    // Check if field contains only "-"
    else if (harapan === "-") {
      errorMessage = 'Harapan tidak boleh hanya berupa "-".';
    }
    // Check if field contains any forbidden words
    else if (forbiddenWords.includes(harapan.toLowerCase())) {
      errorMessage = `Harapan tidak boleh hanya "${harapan}" kata-kata tertentu.`;
    } else if (words.length < 3) {
      errorMessage = "Harapan harus terdiri dari minimal 3 kata.";
    }

    // Display error message or clear it
    if (errorMessage) {
      tutup.prop("disabled", true);
      $("#error-message").text(errorMessage);
    } else {
      tutup.prop("disabled", false);
      $("#error-message").text(""); // Clear error message if input is valid
    }
  });

  tutup.on("click", function () {
    let timerInterval;
    Swal.fire({
      title: "Just drop me a message to respond to, whatever it is! :)",
      html: "Tunggu sampe proses selesai <b></b> milliseconds.",
      timer: 5000,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading();
        const timer = Swal.getPopup().querySelector("b");
        timerInterval = setInterval(() => {
          timer.textContent = `${Swal.getTimerLeft()}`;
        }, 100);
      },
      willClose: () => {
        clearInterval(timerInterval);
      },
    }).then((result) => {
      var hope = $("#harapan").val();
      /* Read more about handling dismissals below */
      if (result.dismiss === Swal.DismissReason.timer) {
        $.ajax({
          url: url + "api/proses_tutup.php",
          data: { hope: hope },
          type: "GET",
          success: function (data, status) {
            location.reload();
          },
          error: function (xhr, status, error) {
            console.log(xhr);
          },
        });
      }
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
