<?php
$_SESSION['informasi'] = 0;
session_unset();
session_destroy();

pindah("$url");
?>