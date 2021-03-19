<?php
function pesan($pesan,$type='success'){
	echo "<div class='alert alert-$type' role='$type'>";
     echo  $pesan ;	
    echo  "</div>";

}

function cek_login(){
}