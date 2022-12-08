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
		
		$sql = mysqli_query($konek, "UPDATE santri SET nama='$nama', idbalai='$idbalai' WHERE nis='$nis';");
		
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=santri');
			die();
		} else {
			echo 'ERROR! Periksa penulisan querynya.';
		}
	} else {
		$nis = $_REQUEST['nis'];
		$sql = mysqli_query($konek, "SELECT * FROM santri WHERE nis='$nis'");
		list($nis,$nama,$idbalai) = mysqli_fetch_array($sql);
?>
<h2>Edit Data santri</h2>
<hr>
<form method="post" action="admin.php?hlm=master&sub=santri&aksi=edit" class="form-horizontal" role="form" autocomplete="off">
	<div class="form-group">
		<label for="nis" class="col-sm-2 control-label">NIS</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis; ?>"readonly>
		</div>
	</div>
	<div class="form-group">
		<label for="nama" class="col-sm-2 control-label">Nama santri</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="balai" class="col-sm-2 control-label">Program Studi</label>
		<div class="col-sm-4">
			<select name="idbalai" class="form-control">
			<?php
			$qbalai = mysqli_query($konek, "SELECT * FROM balai ORDER BY idbalai");
			while(list($id,$balai)=mysqli_fetch_array($qbalai)){
				echo '<option value="'.$id.'"';
				echo ($id==$idbalai) ? 'selected' : '';
				echo '>'.$balai.'</option>';
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