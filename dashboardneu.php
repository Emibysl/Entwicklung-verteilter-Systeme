//WIRD NICHT GEBRAUCHT


<?php

session_start();
// Überprüfen, ob der Benutzername in der Session gesetzt ist
if (!isset($_SESSION['benutzername'])) {
    //echo "Benutzername nicht gesetzt, Weiterleitung zur Login-Seite...";
    header("Location: ../login.php");
    exit();
} else {
    echo "Benutzername gesetzt: " . $_SESSION['benutzername'];
}

// CSRF Token generieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// CSRF Token validieren bei POST-Anfragen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed.');
    }
}

// Sanitize user input function
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<!-- kunde.php -->
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kassensystem</title>
	<!--=============== FAVICON ===============-->
   <link rel="shortcut icon" href="../assets/img/favicon.jpeg" type="image/x-icon">

   <!--=============== REMIXICONS ===============-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
   
	<!--=============== CSS ===============-->
	<link rel="stylesheet" href="assets/css/dashboardneu.css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
		
	<!--=============== GOOGLE API =============== -->
	<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0Rvkv56YKn94nwRu0todxIJH7-fIHp0U&libraries=places&callback=initAutocomplete">
	</script>
	
	<!-- DAS IST NEUER CODE -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	
	<style>
		.confirmation-message {
			position: fixed;
			top: 10%;
			left: 50%;
			transform: translate(-50%, -50%);
			padding: 10px 20px;
			background-color: #4CAF50;
			color: white;
			border-radius: 5px;
			font-size: 14px;
			box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
			z-index: 1000;
		}
		.confirmation-message .close-btn {
			margin-left: 10px;
			cursor: pointer;
			font-weight: bold;
		}

		
		 /* Stil für das Bestätigungsmodal */
		#confirmationModal.confirmation-modal {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.5);
			justify-content: center;
			align-items: center;
			z-index: 1000;
		}

		#confirmationModal .confirmation-modal-content {
			background-color: #fff;
			padding: 20px;
			border-radius: 8px;
			text-align: center;
			width: 300px;
			box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
		}

		#confirmationModal .confirmation-modal-header {
			font-size: 18px;
			margin-bottom: 15px;
			font-weight: bold;
		}

		#confirmationModal .confirmation-modal-buttons {
			display: flex;
			justify-content: space-around;
			margin-top: 20px;
		}

		#confirmationModal .confirmation-modal-button {
			padding: 8px 15px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			font-size: 14px;
			transition: background-color 0.3s;
		}

		#confirmationModal .confirmation-modal-button.confirm {
			background-color: #4CAF50;
			color: white;
		}

		#confirmationModal .confirmation-modal-button.confirm:hover {
			background-color: #45a049;
		}

		#confirmationModal .confirmation-modal-button.cancel {
			background-color: #f44336;
			color: white;
		}

		#confirmationModal .confirmation-modal-button.cancel:hover {
			background-color: #e53935;
		}
		
		/*Dropdown*/
		.product-dropdown {
			bottom: 0;
			width: 100%;
		}

		.product-dropdown label {
			display: block;
			font-weight: bold;
			margin-bottom: 5px;
		}

		.product-dropdown select {
			width: 100%;
			padding: 10px 20px;
			background-color: #FF9800; /* Gleiche Hintergrundfarbe wie Button im geschlossenen Zustand */
			color: #fff;
			border: none;
			border-radius: 5px;
			font-size: 16px;
			cursor: pointer;
			transition: background-color 0.3s ease; /* Sanfter Übergang */
		}

		/* Hintergrundfarbe ändern, wenn das Dropdown geöffnet ist */
		.product-dropdown select:focus {
			background-color: #fff;
			color: #000; /* Textfarbe ändern, wenn geöffnet */
			border: 1px solid #FF9800; /* Optional: Rand hinzufügen beim Öffnen */
		}
		
		/* Overlay Hintergrund */
		/* Für geschlossen Meldung */
		.popup-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.5);
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 999;
		}

		/* Popup-Inhalt */
		.popup-content {
			background-color: #fff;
			padding: 20px;
			width: 300px;
			text-align: center;
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}

		.popup-content h3 {
			margin: 0 0 10px;
			font-size: 1.5em;
			color: #333;
		}

		.popup-content p {
			font-size: 1em;
			color: #555;
			margin-bottom: 20px;
		}

		.popup-content button {
			padding: 10px 20px;
			background-color: #007BFF;
			color: #fff;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 1em;
		}

		.popup-content button:hover {
			background-color: #0056b3;
		}
		
	</style>


</head>
<body>
    <div class="header">
		<div class="header-content">
			<div class="opening-hours">
				<i class="fas fa-clock"></i> Öffnungszeiten: 
					<span>Mo-So:</span> 11:00 - 22:00 Uhr
			</div>
			<h1>Willkommen im Kassensystem</h1>
			<div class="menu-icon" onclick="toggleMenu()">☰</div>
			<span class="cart-count" id="cart-count">0</span>
			<div class="cart-icon" onclick="toggleCart()">🛒</div>			
		</div>
	</div>

	<!-- Modales Fenster für Allergieinformationen -->
	<div id="allergy-modal" class="modal">
		<div class="modal-content">
			<span class="close" onclick="closeModal()">&times;</span>
			<h2>Allergie Informationen</h2>
			<div id="allergy-info"></div>
		</div>
	</div>
	
	<!-- Bestätigungsmodal -->
	<div id="confirmationModal" class="confirmation-modal">
		<div class="confirmation-modal-content">
			<div class="confirmation-modal-header">Diesen Softdrink hinzufügen?</div>
			<div class="confirmation-modal-buttons">
				<button id="confirmBtn" class="confirmation-modal-button confirm">Ja</button>
				<button id="cancelBtn" class="confirmation-modal-button cancel">Nein</button>
			</div>
		</div>
	</div>

	


	
	<!--==================== MAIN ====================-->
   <main class="main">
    
    <div class="container">
		<br>
		<br>
		<br>
		<br>
		<br>
		<h1 class="section__title">Speisekarte</h1>
		
		<!-- Suchfeld -->
		<div class="search-container">
			<form class="search-form">
				<input type="text" id="search-input" name="search" placeholder="Produkt suchen..." autocomplete="off">
				<!-- Der Suchen-Button und Zurücksetzen-Button werden nicht mehr benötigt -->
			</form>
		</div>


        <div class="products">
            <?php
				include_once '../inc/dbverb.php';

				

				// Überprüfen, ob eine Suchanfrage gesendet wurde
				$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

				if ($searchTerm != '') {
					// SQL-Abfrage mit LIKE-Klausel für die Suche
					$sql = "SELECT * FROM produkte WHERE name LIKE ? ORDER BY kategorie";
					$stmt = $conn->prepare($sql);
					$searchTerm = "%" . $searchTerm . "%"; // Wildcards hinzufügen
					$stmt->bind_param('s', $searchTerm);
					$stmt->execute();
					$result = $stmt->get_result();
				} else {
					// Standard SQL-Abfrage, wenn keine Suche durchgeführt wird
					$sql = "SELECT * FROM produkte ORDER BY kategorie";
					$result = $conn->query($sql);
				}
				$productsByCategory = [];

				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						$category = $row['kategorie'];
						if (!isset($productsByCategory[$category])) {
							$productsByCategory[$category] = [];
						}
						$productsByCategory[$category][] = $row;
					}
				} else {
					echo "Keine Produkte vorhanden.";
				}

				// Softdrink-Varianten basierend auf Material abrufen
				$softdrinkVariants = [
					'Dose' => [],
					'Glas' => []
				];
				$softdrinkQuery = "SELECT * FROM softdrinks";
				$softdrinkResult = $conn->query($softdrinkQuery);

				if ($softdrinkResult->num_rows > 0) {
					while ($row = $softdrinkResult->fetch_assoc()) {
						$material = $row['material'];
						if (isset($softdrinkVariants[$material])) {
							$softdrinkVariants[$material][] = $row;
						}
					}
				}

				$conn->close();

				$allergy_map = [
					// Allergiemap
				];

				// Accordion für jede Kategorie erstellen
				foreach ($productsByCategory as $category => $products) {
					echo "<div class='accordion'>";
					echo "<div class='accordion-header' onclick='toggleAccordion(this)'>$category <span>+</span></div>";
					echo "<div class='accordion-content'>";
					echo "<div class='products'>";

					foreach ($products as $product) {
						// Prüfen, ob das Produkt "Softdrinks Glasflasche 0,33l" oder "Softdrinks Dose 0,33l" ist
						if ($product["name"] === "Softdrinks Glasflasche 0,33l" || $product["name"] === "Softdrinks Dose0,33l") {
							$materialType = strpos($product["name"], "Glasflasche") !== false ? "Glas" : "Dose";
							
							echo "<div class='product'>";
							echo "<img src='uploads/" . sanitizeInput($product["bild"]) . "' alt='" . sanitizeInput($product["name"]) . "'>";
							echo "<div class='product-info'>";
							echo "<h3>" . sanitizeInput($product["name"]) . "</h3>";
							echo "</div>"; // .product-info neu hinzugefügt wichtig für Anordnung
										
							echo "<p class='price'>Preis: " . sanitizeInput($product["preis"]) . "€</p>";
							
							// Dropdown-Menü für Softdrink-Varianten basierend auf Materialtyp
							echo "<div class='product-dropdown'>";
							//echo "<label for='softdrink_$materialType'>Softdrink wählen:</label>";
							echo "<select id='softdrink_$materialType' onchange='selectSoftdrinkVariant(this)'>";
							echo "<option value=''>Softdrink wählen...</option>";

							// Varianten für die Dropdown-Liste hinzufügen
							foreach ($softdrinkVariants[$materialType] as $variant) {
								$variantData = json_encode($variant);
								echo "<option value='$variantData'>" . htmlspecialchars($variant["name"]). "</option>";
							}

							echo "</select>";
							echo "</div>"; // .product-dropdown
							//echo "</div>"; // .product-info hat den Button verschoben
							echo "</div>"; // .product
						} else {
							// Standard-Produktkachel für andere Getränke
							echo "<div class='product' id='product_" . $product['id'] . "'>";
							echo "<img src='uploads/" . sanitizeInput($product["bild"]) . "' alt='" . sanitizeInput($product["name"]) . "'>";
							echo "<div class='product-info'>";
							echo "<h3>" . sanitizeInput($product["name"]) . "</h3>";

							if (!empty($product["produktbeschreibung"])) {
								echo "<p class='product-description'>" . sanitizeInput($product["produktbeschreibung"]) . "</p>";
							}

							if (!empty($product["allergien"])) {
								echo "<p class='product-allergies'>Allergien: " . sanitizeInput($product["allergien"]) . "</p>";
							}

							echo "</div>"; // .product-info

							echo "<div class='price-add'>";
							echo "<p class='price'>Preis: " . sanitizeInput($product["preis"]) . "€</p>";
							// Button mit Pop-up für Softdrinks Glasflasche oder Dose, ansonsten Standard-Warenkorb-Button
							if ($product["name"] === "Softdrinks Glasflasche 0,33l" || $product["name"] === "Softdrinks Dose0,33l") {
								$materialType = strpos($product["name"], "Glasflasche") !== false ? "Glas" : "Dose";
								echo "<button onclick=\"showSoftdrinkSelectionPopup({ id: " . $product['id'] . ", name: '" . $product['name'] . "' }, '$materialType')\">+</button>";
							} else {
								// Standard-Warenkorb-Button für andere Produkte
								echo "<button onclick='addToCart(" . json_encode($product) . ")'>+</button>";
							}
										echo "</div>"; // .price-add
										echo "</div>"; // .product
									}
								}

					echo "</div>"; // .products
					echo "</div>"; // .accordion-content
					echo "</div>"; // .accordion
				}
			?>


    </div>
        </div>
    </div>
	
	<!-- DAS IST NEUER CODE: Hintergrund-Overlay für das Popup -->

	<div id="popup-overlay"></div>

	<!-- Popup-Modal für Zutaten -->
	<div id="zutaten-popup">
		<h2>Wähle deine Zutaten</h2>
		<div id="zutaten-list"></div> <!-- Dynamisch eingefügte Zutaten-Checkboxen -->
		
		<h3>Soßenauswahl<br><h4>(1 Soße gratis / je weitere Soße 0,50 €)</h4></h3>
		<div id="sossen-list"></div> <!-- Dynamisch eingefügte Soßen-Checkboxen -->
		
		<button id="confirm-zutaten">Bestätigen</button>
		<button id="close-popup">Schließen</button>
	</div>
	
	<!-- Menü-Popup -->
	<div id="menu-popup" class="menu-popup">
		<div class="menu-popup-content">
			<span class="close" onclick="toggleMenu()">&times;</span>
			<h2>Navigation</h2>
			<ul>
				<li><a href="mitarbeiter.php">Offene Bestellungen</a></li>
				<li><a href="produkte.php">Unsere Produkte</a></li>
				<li><a href="bestelluebersicht.php">Genehmigte Bestellungen</a></li>
				<li><a href="kennzahlen.php">Tagesinfo</a></li>
				<li><a href="wartezeit_dashboard.php">Aktualisierung der Wartezeit</a></li>
				<li><a href="lieferbereich.php">Anpassung der Lieferbereiche (Kosten)</a></li>

			</ul>
			<a class="logout" href="logout.php">Ausloggen</a>
			<button id="allergy-info-btn" onclick="showAllergyInfo()">Allergien</button>
		</div>
	</div>
	
	<!-- Overlay für Popups -->
	<div id="popup-overlay"></div>

	<!-- Popup-Modal für die Pizza-Größenauswahl -->
	<div id="size-selection-popup" style="display: none;">
		<div id="size-list"></div> <!-- Hier kommen die Radio-Buttons für die Größenoptionen -->
		<button id="confirm-size">Bestätigen</button>
		<button id="close-size-popup">Schließen</button>
	</div>




   <div class="cart-overlay" id="cart">
    <div class="cart-header">
        Einkaufswagen
        <span class="close-cart" onclick="toggleCart()">×</span>
    </div>
    
    <!-- Delivery information -->
    <form method="POST" action="../order/process_internorder.php">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="orderItems" id="orderItems">

        <!-- Fixed payment method section at the top -->
        <div class="payment-method" style="position: sticky; top: 0; background-color: white; z-index: 1; padding: 10px;">
            <h3>Wählen Sie Ihre Zahlungsmethode</h3>
            <label class="radio-container">
                <input type="radio" name="payment_method" value="Bar" required>
                Bar
                <span class="checkmark"></span>
            </label>
            <label class="radio-container">
                <input type="radio" name="payment_method" value="Karte" required>
                Karte
                <span class="checkmark"></span>
            </label>
            <!-- Fehlermeldung für Zahlungsmethode -->
            <p class="error-message" style="display:none;color:red;">Bitte wählen Sie eine Zahlungsmethode aus.</p>
        </div>
        
        <!-- Scrollable cart items section -->
        <div class="cart-items" id="cart-items" style="max-height: 300px; overflow-y: auto; padding: 10px;">
            <!-- Cart items will be dynamically added here -->
        </div>

        <!-- Fixed footer section at the bottom -->
        <div class="cart-footer" style="position: sticky; bottom: 0; background-color: white; padding: 10px;">
            <p class="total-price">Summe der Produkte: <span id="total-price">0,00 €</span></p>
            <div id="error-message" style="color: red; margin-bottom: 15px;"></div>
            <button id="buy-now" name="place_internorder" class="checkout-btn" type="submit" disabled>
				Bestellen
			</button>
        </div>
    </form>
</div>

<?php 
unset($_SESSION['errors']); 
unset($_SESSION['message']);
?>




   <!--==================== FOOTER ====================-->
   <footer class="footer">
      <div class="footer__container container grid">
         <a href="#" class="footer__logo">BRO´S GEMÜSE KEBAB</a>

         <div class="footer__content grid">
            <a href="impressum.html" class="footer__link">Impressum</a>
            <a href="datenschutz.html" class="footer__link">Datenschutz</a>

            <span class="footer__copy">
               &#169; All Rights Reserved
            </span>
         </div>
      </div>
   </footer>
   
  

	<script>
	/*DAS IST NEUER CODE */
		$(document).ready(function() {
			// Popup schließen
			$('#close-popup').click(function() {
				$('#zutaten-popup').hide();
			});
		});
		
		function toggleMenu() {
			var menuPopup = document.getElementById('menu-popup');
			if (menuPopup.style.display === 'block') {
				menuPopup.style.display = 'none';
			} else {
				menuPopup.style.display = 'block';
			}
		}

	</script>
	
    <!-- Binde die externe JavaScript-Datei ein -->
    <script src="assets/js/script.js" ></script>
	
	

	
</body>

</html>
