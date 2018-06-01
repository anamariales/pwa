<?php

// Pokreni session spremanje kako bi mogli privremeno spremiti uspješnu prijavu korisnika
session_start();

?>

<!DOCTYPE html>
<html>

<?php

// Putanja do direktorija sa slikama
define('UPLPATH', 'slike/');

// Provjera da li se radi o POST requestu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Provjera da li je korisnik došao s login.html
	if (isset($_POST['prijava'])) {

		// Ukoliko postoji, uništi trenutačni session prethodno prijavljenog korisnika
		if (isset($_SESSION['$imePrijavljenogKorisnika'])) {
			unset($_SESSION['$imePrijavljenogKorisnika']);
			unset($_SESSION['$levelPrijavljenogKorisnika']);
		}

		// Provjera da li korisnik postoji u bazi uz zaštitu od SQL injectiona
		$prijavaImeKorisnika = $_POST['imeKorisnika'];
		$prijavaLozinkaKorisnika = md5(htmlspecialchars($_POST['lozinkaKorisnika']));

		$dbc = mysqli_connect('localhost', 'root', '', 'users') or die('Neuspješno spajanje na bazu.');
		$sql = "SELECT username, password, level FROM users
				WHERE username = ? AND password = ?";
		$stmt = mysqli_stmt_init($dbc);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_bind_param($stmt, 'ss', $prijavaImeKorisnika, $prijavaLozinkaKorisnika);
        	mysqli_stmt_execute($stmt);
        	mysqli_stmt_store_result($stmt);
     	}
		mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
		mysqli_stmt_fetch($stmt);

		if (mysqli_stmt_num_rows($stmt) > 0) {
			$uspjesnaPrijava = true;

			if($levelKorisnika == 2) {
				$korisnikJeAdministrator = true;
			}
			else {
				$korisnikJeAdministrator = false;
			}

			$_SESSION['$imePrijavljenogKorisnika'] = $imeKorisnika;
			$_SESSION['$levelPrijavljenogKorisnika'] = $levelKorisnika;
		} else {
			$uspjesnaPrijava = false;
		}
		mysqli_close($dbc);

	}

	// Brisanje i promijena arhiviranosti
	else if (isset($_POST['administracija'])) {

		$arhivirajProizvod = $_POST['arhivirajProizvod'];
		$sifraProizvoda = $_POST['sifraProizvoda'];
		$obrisiProizvod = $_POST['obrisiProizvod'];
		if ($obrisiProizvod != null) {
			$dbc = mysqli_connect('localhost', 'root', '', 'users')
				   or die('Neuspješno spajanje na bazu.');
			$query = "DELETE FROM proizvodi
					  WHERE sifraProizvoda = '" . $sifraProizvoda . "'";
			$result = mysqli_query($dbc, $query)
					  or die('Neuspješno brisanje.');
			mysqli_close($dbc);
		} else {
			$dbc = mysqli_connect('localhost', 'root', '', 'users')
				   or die('Neuspješno spajanje na bazu.');
			$query = "UPDATE proizvodi
					  SET arhivirajProizvod = '" . $arhivirajProizvod . "'
					  WHERE sifraProizvoda = '" . $sifraProizvoda . "'";
			$result = mysqli_query($dbc, $query)
					  or die('Neuspješna promjena podataka.');
			mysqli_close($dbc);
		}

	}

}

?>

<head>
	<title>Administracija</title>
	<meta charset="UTF-8">
	<meta name="description" content="Administracija.">
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

			// Pokaži stranicu ukoliko je korisnik uspješno prijavljen i administrator je
			if (($uspjesnaPrijava == true && $korisnikJeAdministrator == true)
			   || (isset($_SESSION['$imePrijavljenogKorisnika'])) && $_SESSION['$levelPrijavljenogKorisnika'] == 2) {

				$dbc = mysqli_connect('localhost', 'root', '', 'users')
					   or die('Neuspješno spajanje na bazu.');
				$query = "SELECT * FROM proizvodi";
				$result = mysqli_query($dbc, $query)
						  or die('Neuspješno hvatanje proizvoda.');;
				while($row = mysqli_fetch_array($result)) {
					echo '<article id="proizvod">';
						echo '<a href="#">';
							echo '<div class="slika-proizvoda">';
								echo '<img class="slika" src="' . UPLPATH . $row['slikaProizvoda'] . '" />';
							echo '</div>';
							echo '<div class="opis-proizvoda">';
								echo '<h2 class="naziv">' . $row['nazivProizvoda'] . '</h2>';
								echo '<h3 class="cijena">' . $row['cijenaProizvoda'] . ' kn</h3>';
								echo '<p class="opis">' . $row['opisProizvoda'] . '</p>';
								echo '<p class="sifra">#' . $row['sifraProizvoda'] . '</p>';
								echo '<p class="kategorija">@' . $row['kategorijaProizvoda'] . '</p>';
								echo '<form name="promijenaProizvoda" action="administrator.php" method="POST">';
									echo '<input type="hidden"
												 name="sifraProizvoda"
												 id="sifraProizvoda"
												 value="' . $row['sifraProizvoda'] . '"/>';
									if($row['arhivirajProizvod'] == null) {
										echo '<input type="checkbox"
													 name="arhivirajProizvod"
													 id="arhivirajProizvod"/> Arhiviraj?';
									} else {
										echo '<input type="checkbox"
													 name="arhivirajProizvod"
													 id="arhivirajProizvod" checked/> Arhiviraj?';
									}
									echo '<br>';
									echo '<input type="checkbox"
												 name="obrisiProizvod"
												 id="obrisiProizvod"/> Obriši?';
									echo '<input type="submit" name="administracija" value="Promijeni">';
								echo '</form>';
							echo '</div>';
						echo '</a>';
					echo '</article>';
				}
				mysqli_close($dbc);

			// Pokaži poruku da je korisnik uspješno prijavljen, ali nije administrator
			} else if ($uspjesnaPrijava == true && $korisnikJeAdministrator == false) {
				echo '<p>Bok ' . $imeKorisnika . '! Uspješno ste prijavljeni, ali niste administrator lopte.</p>';
			} else if (isset($_SESSION['$imePrijavljenogKorisnika']) && $_SESSION['$levelPrijavljenogKorisnika'] == 1) {
				echo '<p>Bok ' . $_SESSION['$imePrijavljenogKorisnika'] .
					 '! Uspješno ste prijavljeni, ali niste administrator lopte.</p>';
			} else if ($uspjesnaPrijava == false) {
				echo '<p>Niste uspješno prijavljeni,
					  molimo vas da se <a href="login.html">prijavite</a>
					  ili <a href="registracija.html">registrirate</a>.</p>';
			}

			?>
		</main>

		<footer>
			<p><a href="mailto:alesnjak@tvz.hr">Anamaria Lešnjak</a> Ⓒ 2018.</p>
		</footer>

	</div>

</body>

</html>
