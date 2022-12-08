<?php
include './koneksi.php';

if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['aksi'] )){
		$aksi = $_REQUEST['aksi'];
		switch($aksi){
			case 'baru':
				include 'santri_baru.php';
				break;
			case 'edit':
				include 'santri_edit.php';
				break;
			case 'hapus':
				include 'santri_hapus.php';
				break;
		}
	} else {
		$sql = mysqli_query($konek, "SELECT * FROM santri ORDER BY nis");
		echo '<h2>Daftar Santri</h2><hr>';
      echo '<div class="row">';
		echo '<div class="col-md-9"><table class="table table-bordered">';
		echo '<tr class="info"><th>#</th><th width="100">NIS</th><th>Nama Lengkap</th><th>Balai</th>';
		echo '<th width="200"><a href="./admin.php?hlm=master&sub=santri&aksi=baru" class="btn btn-default btn-xs">Tambah Data</a></th></tr>';
		
		if( mysqli_num_rows($sql) > 0 ){
			$no = 1;
			while(list($nis,$nama,$idbalai) = mysqli_fetch_array($sql)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$nis.'</td>';
				echo '<td>'.$nama.'</td>';
				$qbalai = mysqli_query($konek, "SELECT balai FROM balai WHERE idbalai='$idbalai'");
				list($balai) = mysqli_fetch_array($qbalai);
				echo '<td>'.$balai.'</td>';
				echo '<td><a href="./admin.php?hlm=master&sub=santri&aksi=edit&nis='.$nis.'" class="btn btn-success btn-xs">Edit</a> ';
				echo '<a href="./admin.php?hlm=master&sub=santri&aksi=hapus&nis='.$nis.'" class="btn btn-danger btn-xs">Hapus</a></td>';
				echo '</tr>';
				$no++;
			}
		} else {
			echo '<tr><td colspan="5"><em>Belum ada data</em></td></tr>';
		}
		
		echo '</table></div></div>';
	}
}
?>