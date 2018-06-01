<!DOCTYPE html>
<html>

<?php

$punoImeKorisnika = $_POST['punoImeKorisnika'];
$imeKorisnika = $_POST['imeKorisnika'];
$lozinkaKorisnika = md5(htmlspecialchars($_POST['lozinkaKorisnika']));

// Registracija korisnika u bazi pazeći na SQL injection
$dbc = mysqli_connect('localhost', 'root', '', 'users') or die('Neuspješno spajanje na bazu.');
$sql = "INSERT INTO users (username, password, name)
		VALUES (?, ?, ?)";
$stmt = mysqli_stmt_init($dbc);
if (mysqli_stmt_prepare($stmt, $sql)) {
	mysqli_stmt_bind_param($stmt, 'sss', $imeKorisnika, $lozinkaKorisnika, $punoImeKorisnika);
    mysqli_stmt_execute($stmt);
	$registriranKorisnik = true;
}
mysqli_close($dbc);
?>

<head>
	<title>Registracija korisnika</title>
	<meta charset="UTF-8">
	<meta name="description" content="Registracija korisnika.">
	<meta name="author" content="Anamaria Lešnjak">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

	<div class="okvir">

		<header>
			<a href="index.html">
				<div class="logo">
					<h1>Lopta</h1>
				</div>
			</a>
			<nav>
				<a href="registracija.html">Registracija</a>
				<a href="login.html">Prijava</a>
				<a href="administrator.php">Administrator</a>
				<a href="proizvodi.php">Proizvodi</a>
				<a href="unos.html">Unos</a>
				<a href="http://www.tvz.hr">TVZ</a>
				<a href="onama.html">O nama</a>
				<a href="index.html">Naslovnica</a>
			</nav>
		</header>

		<main>
			<?php
				if($registriranKorisnik == true) {
					echo '<p>Korisnik je uspješno registriran!</p>';
				} else {
					echo '<p>Korisnik nije uspješno registriran!</p>';
				}
			?>
		</main>

		<footer>
			<p><a href="mailto:alesnjak@tvz.hr">Anamaria Lešnjak</a> Ⓒ 2018.</p>
		</footer>

	</div>

</body>

</html>
