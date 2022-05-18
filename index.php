<?php
session_start();
if (isset($_GET['sesi'])) {
	$nik = $_POST['nik'];
	$nama = $_POST['nama'];
	$fn = 'konfig/config.txt';
	$fo = fopen($fn, 'a+');
	if (isset($_POST['daftar'])) {
		$txt = implode('|', array(
			$nik, $nama
		));
		$txt .= "\n";
		fwrite($fo, $txt);
		fclose($fo);
		header('location: index.php');
	} else if(isset($_POST['masuk'])) {
		$fr = fread($fo, filesize($fn)-1);
		fclose($fo);
		$users = explode("\n", $fr);
		foreach($users as $usr) {
			$usr = explode('|', $usr);
			if ($nik == $usr[0] && $nama == $usr[1]) {
				$_SESSION['nik'] = $nik;
				$_SESSION['nama'] = $nama;
				header('location: index.php');
				break;
			}
		}
	}
} else if (isset($_GET['isi']) && isset($_SESSION['nik'])) {
	$tgl = $_POST['tgl'];
	$jam = $_POST['jam'];
	$lokasi = $_POST['lokasi'];
	$suhu = $_POST['suhu'];
	$txt = implode('|', array(
		$tgl, $jam, $lokasi, $suhu
	));
	$txt .= "\n";
	$fn = "konfig/user/{$_SESSION['nik']}.txt";
	$fo = fopen($fn, 'a');
	fwrite($fo, $txt);
	fclose($fo);
	header('location: index.php');
} else if (isset($_GET['logout'])) {
	session_unset();
	session_destroy();
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Peduli Diri</title>
	<link rel="stylesheet" href="aset/css/global.css">
</head>
<body>
	<div class="login">
		<?php if (!isset($_SESSION['nik'])) { ?>
			<div class="login-form">
				<h2>Masuk atau Daftar</h2>
				<form action="?sesi" method="post">
					<input type="text" name="nik" placeholder="NIK">
					<input type="text" name="nama" placeholder="Nama Lengkap">
					<div class="btn">
						<button name="daftar" type="submit">Saya Pengguna Baru</button>
						<button name="masuk" type="submit">Masuk</button>
					</div>
				</form>
			</div>
		<?php
		} else {
			$fn = "konfig/user/{$_SESSION['nik']}.txt";
		?>
	</div>
	<div class="badan">
		<div class="profile">
			<div class="kepala">
				<div class="logo">
					<img src="aset/gambar/ikon.png" alt="ikon" width="200">
				</div>
				<div class="menu">
					<h1>Peduli Diri</h1>
					<p>Catatan Perjalanan</p>
					<button onclick="home()">Home</button>
					<button onclick="catatan()">Catatan Perjalanan</button>
					<button onclick="ngisi()">Isi Data</button>
					<a href="?logout">Keluar</a>
				</div>
			</div>	
			<div id="home" class="content">
				<div class="box box2">
					Selamat Datang <?=$_SESSION['nama']?> di aplikasi yang sangat mempedulikan Anda
				</div>
				<div class="caper">
					<button onclick="catatan()">Isi Catatan Perjalanan</button>
				</div>
			</div>
			<div id="catatan" class="content">
				<div class="box sortt">
					<label for="sort-as">Urutkan Berdasarkan</label>
					<select id="sort-as">
						<option></option>
						<option value="Tanggal">Tanggal</option>
						<option value="Waktu">Waktu</option>
						<option value="Lokasi">Lokasi</option>
						<option value="Suhu Tubuh">Suhu Tubuh</option>
					</select>
					<button>Urutkan</button>
				</div>
				<div class="box box2">
					<?php
						if (file_exists($fn)) {
							$fo = fopen($fn, 'r');
							$fr = fread($fo, filesize($fn)-1);
							fclose($fo);
							$data = explode("\n", $fr);
					?>
					<table>
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Waktu</th>
								<th>Lokasi</th>
								<th>Suhu Tubuh</th>
							</tr>
						</thead>
						<tbody>
							<?php
									foreach($data as $row) {
										$row = explode('|', $row);
							?>
							<tr>
								<td data-date="<?=$row[0]?>"><?=date_format(date_create($row[0]), 'd-m-Y')?></td>
								<td><?=$row[1]?></td>
								<td><?=$row[2]?></td>
								<td><?=$row[3]?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } else { ?>
					<p>Data Perjalanan Masih Kosong</p>
					<?php } ?>
					<button onclick="catatan()" class="caper_in">Isi Catatan Perjalanan</button>
				</div>
			</div>
			<div id="ngisi" class="content">
				<div class="box form-page">
					<form action="?isi" method="POST">
						<table class="form-data">
							<tr>
								<td>Tanggal</td>
								<td><input type="date" name="tgl" id="tgl"></td>
							</tr>
							<tr>
								<td>Jam berkujung</td>
								<td><input type="time" name="jam" id="jam"></td>
							</tr>
							<tr>
								<td>Lokasi yang di kunjungi</td>
								<td><input type="text" name="lokasi" id="lokasi"></td>
							</tr>
							<tr>
								<td>Suhu tubuh</td>
								<td><input type="number" name="suhu" id="suhu"></td>
							</tr>
							<tr>
								<td><br></td>
								<td><br></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" name="simpan" value="Simpan"></td>
						</table>
					</form>
				</div>
			</div>
		</div>
		<script src="aset/js/sortable.js"></script>
		<?php } ?>
	</div>
</body>
</html>