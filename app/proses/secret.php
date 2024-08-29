<button type="button" id="openModalBtn" class="btn btn-primary">
    Ada sesuatu buat kamu
</button> <br>
<small>* oiya buka sendiri ya</small>

<div class="modal fade" id="birthdayModal" tabindex="-1" aria-labelledby="birthdayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class=" modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="birthdayModalLabel">Pesan Penting</h5>
            </div>
            <div class="modal-body">
                <div id="birthdayCarousel" class="carousel slide">
                    <div class="carousel-inner" style="text-align: justify;">
                        <div class="carousel-item active">
                            <p>Greeting card ini cuma muncul 1 kali ya
                            </p>
                        </div>
                        <div class="carousel-item">
                            <p>Pastiin ya kamu lagi sendiri, dan luangkan waktunya sebentar ya</p>
                        </div>
                        <div class="carousel-item">
                            <p>Haiii, apa kabar? 😊</p>
                        </div>
                        <div class="carousel-item ">
                            <p>Semoga kamu dalam keadaan baik!</p>
                            <p>Semoga hari-hari mu baik dan penuh kebahagiaan untukmu!</p>
                        </div>
                        <div class="carousel-item ">
                            <p>Aku dulu sering kesal sama kamu, tapi seiring waktu aku belajar banyak
                                tentang arti sabar dan menghargai.</p>
                            <p> Kamu telah membuktikan dirimu sebagai
                                seorang wanita yang kuat, kamu seorang yang luar biasa.
                            </p>
                        </div>
                        <div class="carousel-item ">
                            <p>
                                Aku ingin mau maaf atas sikap aku dulu. Seiring waktu, aku sadar
                                betapa berharganya kamu. Semua perasaan negatif itu berubah
                                menjadi rasa hormat dan apresiasi.

                            </p>
                        </div>
                        <div class="carousel-item ">
                            <p>Tak luput dari normalnya seorang lelaki, Ternyata aku suka kamu :(
                            </p>
                        </div>
                         <div class="carousel-item ">
                            <p> Aku malu banget, gak bisa jaga perasaan aku</p>
                            <p> Ini alesan aku kenapa aku nungguin kepastian dari pusat, saking malunya, takut kalau gak jadi disini terus ketemu kamu, aku malu</p>
                        </div>
                        <div class="carousel-item ">
                            <p>Maaf harus menyampaikan ini sekarang, 😊</p>
                            <p>Sekali lagi, maaf jika aku membuatmu merasa tidak nyaman.
                            </p>
                        </div>
                        <div class="carousel-item ">
                            <p>Oiya, masih ada lagi 😁</p>
                        </div>
                        <div class="carousel-item ">
                            <p>Selamat H-<?= $hitung_hari ?> ulang tahun ke 26! 🎉</p>
                            <p>Semoga hari-hari kamu penuh dengan kebahagiaan! 😊</p>
                        </div>
                        <div class="carousel-item">
                            <p>Doa terbaik untuk kamu 🥳</p>
                            <p>Apapun yang kamu inginkan dapat terlaksana</p>
                            <!-- <p>Bisa menjadi seorang istri, yang dicintai pasangan kamu</p> -->
                            <p>Semoga tahun ini menjadi tahun mu</p>
                        </div>
                        <div class="carousel-item">
                            <p>Kamu adalah sosok yang luar biasa. ❤️</p>
                        </div>
                        <div class="carousel-item">
                            <p>Nikmati hari istimewamu, dan semoga semua impianmu tercapai! 🌟</p>
                        </div>
                        <div class="carousel-item">
                            <p>Selamat ulang tahun sekali lagi! 🎂</p>
                            <div>
                                <p>Sebelum pergi dari sini, Isi Harapan atau Afirmasi ya :)</p>
                                <textarea class='form-control' name="" id="harapan"></textarea>
                                <span id="error-message" style="color: red;"></span>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="tutup" type="button">
                    Thanks, Close this Moment
                </button>
                <button class="btn btn-secondary me-2" type="button" id='prevBtn' data-bs-target="#birthdayCarousel"
                    data-bs-slide="prev">
                    Sebelumnya
                </button>
                <button class="btn btn-primary" type="button" data-bs-target="#birthdayCarousel" data-bs-slide="next">
                    Berikutnya
                </button>

            </div>

        </div>
    </div>
</div>
