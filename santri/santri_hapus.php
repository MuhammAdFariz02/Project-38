<?php
include './koneksi.php';

if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['submit'] )){
		$nis = $_REQUEST['nis'];
		$sql = mysqli_query($konek, "DELETE FROM santri WHERE nis='$nis'");
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=santri');
			die();
		} else {
			echo 'ada ERROR dengan query';
		}
	} else {
		$nis = $_REQUEST['nis'];
		$sql = mysqli_query($konek, "SELECT * FROM santri WHERE nis='$nis'");
		list($nis,$santri,$idbalai) = mysqli_fetch_array($sql);
		
		echo '<div class="alert alert-danger">Yakin akan menghapus santri:';
		echo '<br>Nama  : <strong>'.$santri.'</strong>';
		echo '<br>NIS   : '.$nis;
		
		$qbalai = mysqli_query($konek, "SELECT balai FROM balai WHERE idbalai='$idbalai'");
		list($balai) = mysqli_fetch_array($qbalai);
		
		echo '<br>balai : '.$balai.' ('.$idbalai.')<br><br>';
		echo '<a href="./admin.php?hlm=master&sub=santri&aksi=hapus&submit=ya&nis='.$nis.'" class="btn btn-sm btn-success">Ya, Hapus</a> ';
		echo '<a href="./admin.php?hlm=master&sub=santri" class="btn btn-sm btn-default">Tidak</a>';
		echo '</div>';
	}
}
?>