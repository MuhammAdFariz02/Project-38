<?php
include './koneksi.php';

if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	/* tahapan pembayaran SPP
		1. masukkan nis
		2. tampilkan histori pembayaran (jika ada) dan form pembayaran
		3. proses pembayaran, kembali ke nomor 2
	*/
	echo '<h2>Pembayaran SPP</h2><hr>';
	
	if(isset($_REQUEST['submit'])){
		//proses pembayaran secara bertahap
		$submit = $_REQUEST['submit'];
		$nis = $_REQUEST['nis'];
		
		//proses simpan pembayaran
		if($submit=='bayar'){
			$kls = $_REQUEST['kls'];
			$bln = $_REQUEST['bln'];
			$tgl = $_REQUEST['tgl'];
			$jml = $_REQUEST['jml'];
			
			$qbayar = mysqli_query($konek, "INSERT INTO pembayaran VALUES('$kls','$nis','$bln','$tgl','$jml')");
			
			if($qbayar > 0){
				header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				die();
			} else {
				echo 'ada ERROR dg query';
			}
		}
		
		//proses hapus pembayaran, hanya ADMIN
		if($submit=='hapus'){
			$kls = $_REQUEST['kls'];
			$bln = $_REQUEST['bln'];
			$tgl = $_REQUEST['tgl'];
			$jml = $_REQUEST['jml'];
			
			$qbayar = mysqli_query($konek, "DELETE FROM pembayaran WHERE kelas='$kls' AND nis='$nis' AND bulan='$bln'");
			
			if($qbayar > 0){
				header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				die();
			} else {
				echo 'ada ERROR dg query';
			}
		}
		
		//tampilkan data santri
		$qsantri = mysqli_query($konek, "SELECT * FROM santri WHERE nis='$nis'");
		list($nis,$nama,$idbalai) = mysqli_fetch_array($qsantri);
		
    echo '<div class="row">';
	echo '<div class="col-sm-9"><table class="table table-bordered">';
	echo '<tr><td colspan="2">Nomor Induk</td><td colspan="3">'.$nis.'</td>';
    echo '<td><a href="./cetak.php?nis='.$nis.'" target="_blank" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></span>cetak semua</a></td></tr>';
	echo '<tr><td colspan="2">Nama Santri</td><td colspan="4">'.$nama.'</td></tr>';
      
	echo '<tr><td colspan="2">Pembayaran</td><td colspan="4">';
?>
<form class="form-inline" role="form" method="post" action="./admin.php?hlm=bayar" autocomplete="off">
  <input type="hidden" name="nis" value="<?php echo $nis; ?>">
  <input type="hidden" name="tgl" value="<?php echo date("Y-m-d"); ?>">
  <div class="form-group">
      <label class="sr-only" for="kls">Kelas</label>
	  <select name="kls" class="form-control" id="kls">
	  <?php
		$qkelas = mysqli_query($konek, "SELECT kelas,th_pelajaran FROM kelas WHERE nis='$nis'");
		while(list($kelas,$thn)=mysqli_fetch_array($qkelas)){
			echo '<option value="'.$kelas.'">'.$kelas.' ('.$thn.')</option>';
		}
	  ?>
	  </select>
  </div>
  <div class="form-group">
    <label class="sr-only" for="bln">Bulan</label>
	<select name="bln" id="bln" class="form-control">
	<?php
		for($i=1;$i<=12;$i++){
			$b = date('F',mktime(0,0,0,$i,10));
			echo '<option value="'.$b.'">'.$b.'</option>';
		}
	?>
	</select>
  </div>
  <div class="form-group">
	<label class="sr-only" for="jml">Jumlah</label>
	<div class="input-group">
		<div class="input-group-addon">Rp.</div>
		<input type="text" class="form-control" id="jml" name="jml" placeholder="Jumlah">
	</div>
  </div>
  <button type="submit" class="btn btn-default" name="submit" value="bayar">Bayar</button>
</form>
<?php
		echo '</td></tr>';
		echo '<tr class="info"><th width="50">#</th><th width="100">Kelas</th><th>Bulan</th><th>Tanggal Bayar</th><th colspan=2>Jumlah</th>';
		// echo '<th>&nbsp;</th>';
		echo '</tr>';
		
		//tampilkan histori pembayaran, jika ada
		$qbayar = mysqli_query($konek, "SELECT kelas,bulan,tgl_bayar,jumlah FROM pembayaran WHERE nis='$nis' ORDER BY tgl_bayar DESC");
		if(mysqli_num_rows($qbayar) > 0){
			$no = 1;
			while(list($kelas,$bulan,$tgl,$jumlah) = mysqli_fetch_array($qbayar)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$kelas.'</td>';
				echo '<td>'.$bulan.'</td>';
				echo '<td>'.$tgl.'</td>';
				echo '<td colspan=2>'.$jumlah.'</td></tr>';
				
				$no++;
			}
		} else {
			echo '<tr><td colspan="6"><em>Belum ada data!</em></td></tr>';
		}
		echo '</table></div></div>';
		
	} else {
?>
<!-- form input nomor induk santri -->
<form class="form-horizontal" role="form" method="post" action="./admin.php?hlm=bayar" autocomplete="off">
  <div class="form-group">
    <label for="nis" class="col-sm-2 control-label">Nomor Induk Santri</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="nis" name="nis" placeholder="Nomor Induk Santri" required autofocus>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-3">
      <button type="submit" name="submit" class="btn btn-default">Lanjut</button>
    </div>
  </div>
</form>
<?php
	}
}
?>