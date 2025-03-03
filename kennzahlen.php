<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kennzahlen</title>

	<link rel="stylesheet" href="./assets/css/kennzahlen.css">
	<link rel="stylesheet" href="./assets/css/mainDashboard.css">
	<link rel="stylesheet" href="./assets/css/produkte.css">

</head>

<body>
	<div class="header">
		<div class="header-content">
			<h1>Kennzahlenbereich</h1>
			<div class="menu-icon" onclick="toggleMenu()">☰</div>
		</div>
	</div>

	<main class="main">
		<div class="container" style="margin-top: 30px;">
			<section>
				<h2>Tagesabschluss</h2>
				<form method="GET" action="TagesabschlussErsteller.php">
					<label for="abschlussDatePicker">Wählen Sie das Datum für den Tagesabschluss:</label>
					<input type="date" id="abschlussDatePicker" name="selected_date" required>
					<button type="submit">Tagesabschluss drucken</button>
				</form>
			</section>

			<section>
				<h2>Monatsabschluss</h2>
				<form method="GET" action="MonatsabschlussErsteller.php">
					<label for="monthPicker">Wählen Sie einen Monat:</label>
					<input type="text" id="monthPicker" name="selected_month" required placeholder="YYYY-MM">
					<button type="submit">Monatsabschluss drucken</button>
				</form>
			</section>
		</div>
	</main>
	<div id="menu-popup" class="menu-popup">
		<div class="menu-popup-content">
			<span class="close" onclick="toggleMenu()">&times;</span>
			<h2>Navigation</h2>
			<ul>
				<li><a href="mainDashboard.php">Speisekartenverwaltung</a></li>
				<li><a href="orders.php">Offene Bestellungen</a></li>
				<li><a href="products.php">Neues Produkt anlegen</a></li>
				<li><a href="kennzahlen.php" style="font-weight: bold;">Kennzahlenbereich✅</a></li>
				<li><a href="wartezeit_dashboard.php">Wartezeit anpassen</a></li>
				<li><a href="deliveryZones.php">Lieferbereiche anpassen</a></li>
			</ul>
		</div>
	</div>
	<script src="assets/js/script.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>