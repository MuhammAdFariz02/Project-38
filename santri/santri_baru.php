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
		$nama = $_REQUEST['nama'];
		$idbalai = $_REQUEST['idbalai'];
		
		$sql = mysqli_query($konek, "INSERT INTO santri VALUES('$nis','$nama','$idbalai')");
		
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=santri');
			die();
		} else {
			echo 'ERROR! Periksa penulisan querynya.';
		}
	} else {
?>
<h2>Tambah santri</h2>
<hr>
<form method="post" action="admin.php?hlm=master&sub=santri&aksi=baru" class="form-horizontal" role="form" autocomplete="off">
	<div class="form-group">
		<label for="nis" class="col-sm-2 control-label">NIS</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="nis" name="nis" placeholder="Nomor Induk santri" required autofocus>
		</div>
	</div>
	<div class="form-group">
		<label for="nama" class="col-sm-2 control-label">Nama santri</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
		</div>
	</div>
	<div class="form-group">
		<label for="balai" class="col-sm-2 control-label">Program Studi</label>
		<div class="col-sm-4">
			<select name="idbalai" class="form-control">
			<?php
			$qbalai = mysqli_query($konek, "SELECT * FROM balai ORDER BY idbalai");
			while(list($idbalai,$balai)=mysqli_fetch_array($qbalai)){
				echo '<option value="'.$idbalai.'">'.$balai.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="submit" class="btn btn-default">Simpan</button>
			<a href="./admin.php?hlm=master&sub=santri" class="btn btn-link">Batal</a>
		</div>
	</div>
</form>
<?php
	}
}
?>