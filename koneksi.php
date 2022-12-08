<?php
$host	= $_SERVER['SERVER_NAME'];
$user	= "root";
$pass	= "";
$db		= "spp_bayar";	

$konek  = mysqli_connect($host, $user, $pass,$db);
if (!$konek) {
    die('Gagal terhubung ke Databse! '.mysqli_connect_error());
}
?>