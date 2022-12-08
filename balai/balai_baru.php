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
		$balai = $_REQUEST['balai'];
		
		$sql = mysqli_query($konek, "INSERT INTO balai VALUES('$idbalai','$balai')");
		
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=balai');
			die();
		} else {
			echo 'ada ERROR dg query';
		}
	} else {
?>
<h2>Tambah Program Studi</h2>
<hr>
<form method="post" action="admin.php?hlm=master&sub=balai&aksi=baru" class="form-horizontal" role="form" autocomplete="off">
	<div class="form-group">
		<label for="idbalai" class="col-sm-2 control-label">Kode balai</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="idbalai" name="idbalai" placeholder="Kode balai" required autofocus>
		</div>
	</div>
	<div class="form-group">
		<label for="balai" class="col-sm-2 control-label">Nama balai</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="balai" name="balai" placeholder="Nama balai" required>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="submit" class="btn btn-default">Simpan</button>
			<a href="./admin.php?hlm=master&sub=balai" class="btn btn-link">Batal</a>
		</div>
	</div>
</form>
<?php
	}
}
?>