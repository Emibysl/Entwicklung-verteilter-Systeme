<?php
//products.php
session_start();
require_once 'inc/functions.php';

//Hinzufügen von Produkten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'process/process_product.php';
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="UTF-8">
	<title>Neues Produkt anlegen</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="assets/css/mainDashboard.css">
	<link rel="stylesheet" href="assets/css/products.css">
</head>

<body>

	<!-- Header-->
	<div class="header">
		<div class="header-content">
			<h1>Neues Produkt anlegen</h1>
			<div class="menu-icon" onclick="toggleMenu()">☰</div>
		</div>
	</div>

	<?php if (isset($_SESSION['success_message'])): ?>
		<div class="success-message">
			<?= escape($_SESSION['success_message']) ?>
			<?php unset($_SESSION['success_message']); ?>
		</div>
	<?php endif; ?>

	<main class="main">
		<div class="container">
			<div class="edit-modal">
				<div class="edit-modal-header">
					<h2>Neues Produkt anlegen</h2>
				</div>
				<div class="edit-modal-body">
					<form action="products.php" method="POST" enctype="multipart/form-data">
						<!-- Produktname -->
						<label for="name">Produktname:</label>
						<input type="text" id="name" name="name" required>

						<!-- Kategorie -->
						<label for="kategorieDropdown">Kategorie auswählen:</label>
						<select id="kategorieDropdown" onchange="setKategorie()" required>
							<option value="">Bitte wählen</option>
							<option value="Sandwiches">Sandwiches</option>
							<option value="Wraps">Wraps</option>
							<option value="Brosbox">Brosbox</option>
							<option value="Tellerportionen">Tellerportionen</option>
							<option value="Pizza">Pizza</option>
							<option value="Pide">Pide</option>
							<option value="Beilagen">Beilagen</option>
							<option value="Grillspezialitaeten">Grillspezialitäten</option>
							<option value="Drinks">Drinks</option>
							<option value="Menu">Menü</option>
							<option value="Nachspeise">Nachspeise</option>
						</select>
						<label for="kategorieFeld">Ausgewählte Kategorie:</label>
						<input type="text" id="kategorieFeld" name="kategorie" readonly required>

						<!-- Beschreibung -->
						<label for="beschreibung">Produktbeschreibung:</label>
						<textarea id="beschreibung" name="beschreibung"></textarea>

						<!-- Allergien -->
						<label>Allergien (Mehrfachauswahl möglich):</label>
						<div class="allergy-options">
							<label><input type="checkbox" name="allergien[]" value="a"> A</label>
							<label><input type="checkbox" name="allergien[]" value="b"> B</label>
							<label><input type="checkbox" name="allergien[]" value="c"> C</label>
							<label><input type="checkbox" name="allergien[]" value="d"> D</label>
							<label><input type="checkbox" name="allergien[]" value="e"> E</label>
							<label><input type="checkbox" name="allergien[]" value="f"> F</label>
							<label><input type="checkbox" name="allergien[]" value="g"> G</label>
							<label><input type="checkbox" name="allergien[]" value="h"> H</label>
							<label><input type="checkbox" name="allergien[]" value="i"> I</label>
							<label><input type="checkbox" name="allergien[]" value="j"> J</label>
							<label><input type="checkbox" name="allergien[]" value="1"> 1</label>
							<label><input type="checkbox" name="allergien[]" value="2"> 2</label>
							<label><input type="checkbox" name="allergien[]" value="3"> 3</label>
							<label><input type="checkbox" name="allergien[]" value="4"> 4</label>
							<label><input type="checkbox" name="allergien[]" value="5"> 5</label>
							<label><input type="checkbox" name="allergien[]" value="6"> 6</label>
							<label><input type="checkbox" name="allergien[]" value="7"> 7</label>
							<label><input type="checkbox" name="allergien[]" value="8"> 8</label>
							<label><input type="checkbox" name="allergien[]" value="9"> 9</label>
							<label><input type="checkbox" name="allergien[]" value="10"> 10</label>
							<label><input type="checkbox" name="allergien[]" value="12"> 12</label>
							<label><input type="checkbox" name="allergien[]" value="14"> 14</label>
						</div>

						<!-- Preis -->
						<label for="preis">Preis (in Euro):</label>
						<input type="number" id="preis" name="preis" step="0.01" required>

						<!-- Bild -->
						<label for="bild">Produktbild:</label>
						<input type="file" id="bild" name="bild" accept="image/*">
						<input type="hidden" name="id" id="product_id">

						<!-- Submit -->
						<button type="submit">Produkt hinzufügen</button>
					</form>
				</div>
			</div>
		</div>
	</main>
	<!-- Menü-Popup -->
	<div id="menu-popup" class="menu-popup">
		<div class="menu-popup-content">
			<span class="close" onclick="toggleMenu()">&times;</span>
			<h2>Navigation</h2>
			<ul>
				<li><a href="mainDashboard.php">Speisekartenverwaltung</a></li>
				<li><a href="orders.php">Offene Bestellungen</a></li>
				<li><a href="products.php" style="font-weight: bold;">Neues Produkt anlegen✅</a></li>
				<!--<li><a href="kennzahlen.php">Kennzahlenbereich</a></li>-->
				<li><a href="wartezeit_dashboard.php">Aktualisierung der Wartezeit</a></li>
				<li><a href="deliveryZones.php">Anpassung der Lieferbereiche (Kosten)</a></li>
			</ul>
		</div>
	</div>
	<script src="assets/js/script.js"></script>
</body>

</html>