<?php
$baca = file_get_contents('buka.txt');
if ($baca == 'belum') {
    file_put_contents("buka.txt", "sudah");
}