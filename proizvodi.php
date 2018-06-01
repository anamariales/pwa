<!DOCTYPE html>
<html>
	
<?php

// Putanja do direktorija sa slikama
define('UPLPATH', 'slike/');
	
?>

<head>
	<title>Popis proizvoda</title>
	<meta charset="UTF-8">
	<meta name="description" content="Popis proizvoda.">
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
				$dbc = mysqli_connect('localhost', 'root', '', 'users')
					   or die('Neuspješno spajanje na bazu.');
				$query = "SELECT * FROM proizvodi";
				$result = mysqli_query($dbc, $query);
				while($row = mysqli_fetch_array($result)) {
					if($row['arhivirajProizvod'] == null ) {
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
							echo '</div>';
						echo '</a>';
					echo '</article>';
					}
				}
				mysqli_close($dbc);
			?>
		</main>

		<footer>
			<p><a href="mailto:alesnjak@tvz.hr">Anamaria Lešnjak</a> Ⓒ 2018.</p>
		</footer>

	</div>

</body>

</html>