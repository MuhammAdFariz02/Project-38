<?php
include './koneksi.php';

if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['submit'] )){
		//variabel session ditransfer ke variabel lokal yg lebih mudah diingat penamaannya
		$submit = $_REQUEST['submit'];
		$kelas = $_REQUEST['kelas'];
		$tapel = $_REQUEST['tapel'];
		$idbalai = $_REQUEST['idbalai'];
		
		//proses simpan santri ke dalam kelas
		if(($submit=='simpan') AND isset($_REQUEST['nis'])){
			$nis = $_REQUEST['nis'];
			$sql = mysqli_query($konek, "INSERT INTO kelas VALUES('$kelas','$tapel','$nis')");
		}
		
		//proses hapus santri dari kelas
		if(($submit=='hapus') AND isset($_REQUEST['nis'])){
			$nis = $_REQUEST['nis'];
			$qsantri = mysqli_query($konek, "DELETE FROM kelas WHERE kelas='$kelas' AND th_pelajaran='$tapel' AND nis='$nis'");
		}
		
		//form untuk menambahkan santri ke dalam kelas
		echo '<div class="row">';
		echo '<div class="col-md-12">';
		echo '<h2>Daftar santri</h2><hr>';
		echo '<form method="post" action="admin.php?hlm=master&sub=kelas&aksi=view" class="form-inline" role="form">';
		echo '<input type="hidden" name="kelas" value="'.$kelas.'">';
		echo '<input type="hidden" name="tapel" value="'.$tapel.'">';
		echo '<input type="hidden" name="idbalai" value="'.$idbalai.'">';
		echo '<div class="form-group"><select name="nis" class="form-control">';
		//query untuk menampilkan nama2 santri pada balai terkait yang belum mendapatkan/masuk kelas
		$qsantri = mysqli_query($konek, "SELECT nis,nama FROM santri WHERE idbalai='$idbalai' AND nis NOT IN (SELECT nis FROM kelas ) ORDER BY nis");
		while(list($nis,$nama)=mysqli_fetch_array($qsantri)){
			echo '<option value="'.$nis.'">'.$nis.' '.$nama.'</option>';
		}
		echo '</select></div>';
		echo ' <button type="submit" name="submit" value="simpan" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Tambahkan</button>';
		echo ' <a href="admin.php?hlm=master&sub=kelas" class="btn btn-link">Daftar Kelas</a>';
		echo '</form>';
		echo '</div></div><br>';
			
		//tabel daftar santri
		echo '<div class="row">';
		echo '<div class="col-md-9">';
		echo '<table class="table table-bordered">';
		echo '<tr><td colspan="2">Kelas</td><td colspan="2">'.$kelas.'</td></tr>';
		echo '<tr><td colspan="2">Tahun Pelajaran</td><td colspan="2">'.$tapel.'</td></tr>';
		echo '<tr class="info"><th width="20">No.</th><th width="150">NIS</th><th>Nama santri</th><th>&nbsp;</th></tr>';
		
		$qkelas = mysqli_query($konek, "SELECT nis FROM kelas WHERE kelas='$kelas' AND th_pelajaran='$tapel'");
		if(mysqli_num_rows($qkelas) > 0){
			$no=1;
			while(list($nis)=mysqli_fetch_array($qkelas)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$nis.'</td>';
				$qsantri = mysqli_query($konek, "SELECT nama FROM santri WHERE nis='$nis'");
				list($santri) = mysqli_fetch_array($qsantri);
				echo '<td>'.$santri.'</td>';
				//button hapus santri
				echo '<td><a href="admin.php?hlm=master&sub=kelas&aksi=view&nis='.$nis.'&kelas='.$kelas.'&tapel='.$tapel.'&idbalai='.$idbalai.'&submit=hapus" class="btn btn-danger btn-xs">Hapus</a></td></tr>';
				$no++;
			}
		} else {
			echo '<tr><td colspan="4"><em>Belum ada data.</em></td></tr>';
		}
		echo '</table></div></div>';
	} else {
?>
<!--
form pertama untuk tahapan menambahkan kelas baru, yaitu:
1. ketikkan nama kelas
2. ketikkan tahun pelajaran, misalnya: 2014/2015 atau 2014-2015
3. pilih balai yg bersangkutan dg kelas
4. klik tombol [LANJUT]
//-->
<h2>Tambah Kelas</h2><hr>
<form method="post" action="admin.php?hlm=master&sub=kelas&aksi=view" class="form-horizontal" role="form" autocomplete="off">
	<div class="form-group">
		<label for="kelas" class="col-sm-2 control-label">Kelas</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="kelas" name="kelas" placeholder="Kelas" required autofocus>
		</div>
	</div>
	<div class="form-group">
		<label for="tapel" class="col-sm-2 control-label">Tahun Pelajaran</label>
		<div class="col-sm-2">
			<!-- <input type="text" class="form-control" id="tapel" name="tapel" placeholder="mmmm/nnnn" required> //-->
			<select name="tapel" class="form-control">
			<?php
				$qtapel = mysqli_query($konek, "SELECT tapel FROM tapel ORDER BY tapel DESC");
				while(list($tapel)=mysqli_fetch_array($qtapel)){
					echo '<option value="'.$tapel.'">'.$tapel.'</option>';
				}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="balai" class="col-sm-2 control-label">Balai</label>
		<div class="col-sm-3">
			<select class="form-control" id="balai" name="idbalai">
			<?php
			//menampilkan daftar balai ke dalam combo-box atau pulldown
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
			<button type="submit" name="submit" value="baru" class="btn btn-default">Lanjut</button>
			<a href="./admin.php?hlm=master&sub=kelas" class="btn btn-link">Batal</a>
		</div>
	</div>
</form>
<?php
	}
}
?>