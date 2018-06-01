<!DOCTYPE html>
<html>

<?php

// Spremanje podataka poslanih putem forme u varijable
$nazivProizvoda = $_POST['nazivProizvoda'];
$opisProizvoda = $_POST['opisProizvoda'];
$sifraProizvoda = $_POST['sifraProizvoda'];
$cijenaProizvoda = $_POST['cijenaProizvoda'];
$kategorijaProizvoda = $_POST['kategorijaProizvoda'];
$slikaProizvoda = $_FILES['slikaProizvoda']['name'];
$arhivirajProizvod = $_POST['arhivirajProizvod'];

// Promjena direktorija u kojem se sprema slika prilikom uploada
$target = 'slike/' . $slikaProizvoda;
move_uploaded_file($_FILES['slikaProizvoda']['tmp_name'], '$target');

// Putanja do direktorija sa slikama
define('UPLPATH', 'slike/');

// Spajanje i spremanje u bazu unosa
$dbc = mysqli_connect('localhost', 'root', '', 'users')
	   or die('Neuspješno spajanje na bazu.');
$query = "INSERT INTO proizvodi (nazivProizvoda,
								 opisProizvoda,
								 sifraProizvoda,
								 cijenaProizvoda,
								 kategorijaProizvoda,
								 slikaProizvoda,
								 arhivirajProizvod)
          VALUES ('$nazivProizvoda',
			  	  '$opisProizvoda',
				  '$sifraProizvoda',
				  '$cijenaProizvoda',
				  '$kategorijaProizvoda',
				  '$slikaProizvoda',
				  '$arhivirajProizvod')";
$result = mysqli_query($dbc, $query)
		  or die('Nije uspjelo spremanje u bazu.');
mysqli_close($dbc);

?>

<head>
	<title>Spramnje</title>
	<meta charset="UTF-8">
	<meta name="description" content="Spremanje .">
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
			<article id="proizvod">
				<a href="#">
					<div class="slika-proizvoda">
						<?php echo '<img class="slika" src="' . UPLPATH . $slikaProizvoda . '" />'; ?>
					</div>
					<div class="opis-proizvoda">
						<h2 class="naziv"><?php echo $nazivProizvoda ?></h2>
						<h3 class="cijena"><?php echo $cijenaProizvoda ?> kn</h3>
						<p class="opis"><?php echo $opisProizvoda ?></p>
					</div>
				</a>
			</article>
		</main>

		<footer>
			<p><a href="mailto:alesnjak@tvz.hr">Anamaria Lešnjak</a> Ⓒ 2018.</p>
		</footer>

	</div>

</body>

</html>