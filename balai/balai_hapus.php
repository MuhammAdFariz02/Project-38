<?php
include './koneksi.php';
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['submit'] )){
		$idbalai = $_REQUEST['idbalai'];
		$sql = mysqli_query($konek, "DELETE FROM balai WHERE idbalai='$idbalai'");
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=jurusan');
			die();
		} else {
			echo 'ada ERROR dengan query';
		}
	} else {
		$idbalai = $_REQUEST['idbalai'];
		$sql = mysqli_query($konek, "SELECT * FROM balai WHERE idbalai='$idbalai'");
		list($idbalai,$balai) = mysqli_fetch_array($sql);
		
		echo '<div class="alert alert-danger">Yakin akan menghapus Program Studi: <strong>'.$balai.' ('.$idbalai.')</strong><br><br>';
		echo '<a href="./admin.php?hlm=master&sub=jurusan&aksi=hapus&submit=ya&idbalai='.$idbalai.'" class="btn btn-sm btn-success">Ya, Hapus</a> ';
		echo '<a href="./admin.php?hlm=master&sub=jurusan" class="btn btn-sm btn-default">Tidak</a>';
		echo '</div>';
	}
}
?>