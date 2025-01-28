<?php
	session_start();
	if (!isset($_SESSION['benutzername'])) {
		//echo "Benutzername nicht gesetzt, Weiterleitung zur Login-Seite...";
		header("Location: ../login.php");
		exit();
	} else {
		//echo "Benutzername gesetzt: " . $_SESSION['benutzername'];
	}
	// Fehlerberichterstattung aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<!-- mitarbeiter.php -->
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<br>
    <title>Bestellübersicht</title>
	<a href="dashboardneu.php" class="back-button">Zurück zum Dashboard</a>
    <!-- Die Seite alle 10 Sekunden neu laden -->
    <meta http-equiv="refresh" content="10">
    <link rel="stylesheet" href="./assets/css/mitarbeiter.css">
	
	<!-- TON ABSPIELEN -->
	<audio id="new-order-sound" src="assets/sounds/cash-register-kaching-sound-effect-125042.mp3" preload="auto"></audio>

</head>
<body>
    <div class="container">
        <h1>Bestellübersicht für heute</h1>
        
        <?php
		include_once '../inc/dbverb.php';
		
		// Neueste Bestellung abfragen
		$sql = "SELECT BestellungID FROM Bestellungentest ORDER BY BestellungID DESC LIMIT 1";
		$result = $conn->query($sql);
		$playSound = false;

		if ($result && $row = $result->fetch_assoc()) {
			$latestOrderId = $row['BestellungID'];

			// Prüfen, ob die neueste Bestellung eine neue Bestellung ist
			if (!isset($_SESSION['lastOrderId']) || $_SESSION['lastOrderId'] < $latestOrderId) {
				$_SESSION['lastOrderId'] = $latestOrderId;
				$playSound = true; // Markiere, dass ein Ton abgespielt werden soll
			}
		}

$conn->query("SET SESSION group_concat_max_len = 10000");
		// Bestellungen des aktuellen Tages abfragen
		$sql = "SELECT b.*, 
                   GROUP_CONCAT(CONCAT('<br>- ', bp.Menge, 'x ', bp.ProduktName, 
                               IF(bp.ProduktName LIKE '%Pizza%', 
                                  CONCAT(' (Größe: ', 
                                      CASE bp.Groesse
                                          WHEN 1 THEN '28cm'
                                          WHEN 2 THEN '32cm'
                                          WHEN 3 THEN 'Familiengröße'
                                          ELSE 'Standardgröße'
                                      END, ')'), ''),
                               IF(bp.GetraenkeName IS NOT NULL, 
                                  CONCAT(' (Free Drink: ', bp.GetraenkeName, ')'), ''), 
                               IF(bp.Zutaten IS NOT NULL, 
                                  CONCAT(' (Zutaten: ', bp.Zutaten, ')'), ''),
                               IF(bp.Sossen IS NOT NULL, 
                                  CONCAT(' (Soßen: ', bp.Sossen, ')'), ''))
                               SEPARATOR '') AS Lieferinhalt  
					FROM Bestellungentest b 
					LEFT JOIN Bestellpositionen bp ON b.BestellungID = bp.BestellungID 
					WHERE DATE(b.Bestelldatum) = CURDATE() AND b.Freigegeben = 0 
					GROUP BY b.BestellungID";
		
		// Neue Abfrage für die zweite Tabelle
		$sql_all_orders = "SELECT b.*, 
							   GROUP_CONCAT(CONCAT('<br>- ', bp.Menge, 'x ', bp.ProduktName, 
                               IF(bp.ProduktName LIKE '%Pizza%', 
                                  CONCAT(' (Größe: ', 
                                      CASE bp.Groesse
                                          WHEN 1 THEN '28cm'
                                          WHEN 2 THEN '32cm'
                                          WHEN 3 THEN 'Familiengröße'
                                          ELSE 'Standardgröße'
                                      END, ')'), ''),
                               IF(bp.GetraenkeName IS NOT NULL, 
                                  CONCAT(' (Free Drink: ', bp.GetraenkeName, ')'), ''), 
                               IF(bp.Zutaten IS NOT NULL, 
                                  CONCAT(' (Zutaten: ', bp.Zutaten, ')'), ''),
                               IF(bp.Sossen IS NOT NULL, 
                                  CONCAT(' (Soßen: ', bp.Sossen, ')'), ''))
                               SEPARATOR '') AS Lieferinhalt  
					  FROM Bestellungentest b
					  LEFT JOIN Bestellpositionen bp ON b.BestellungID = bp.BestellungID
					  WHERE DATE(b.Bestelldatum) = CURDATE()
					  GROUP BY b.BestellungID";


		
		$result = $conn->query($sql);
		$result_all_orders = $conn->query($sql_all_orders);


		if ($result_all_orders->num_rows > 0) {
			echo "<table>";
			echo "<tr>
					<th>Bestell-ID</th>
					<th>Kundenname</th>
					<th>Adresse</th>
					<th>Telefonnummer</th>
					<th>Gesamtbetrag (inklusive Lieferkosten)</th>
					<th>Zahlungsmethode</th>
					<th>Anmerkungen</th>
					<th>Lieferinhalt</th>
					<th>Aktion</th>
					<th>Bestell-Typ</th>
					<th>Zeiten</th>
				  </tr>";

			while($row = $result->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . htmlspecialchars($row["BestellungID"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["KundeName"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["KundeAdresse"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["KundeTelefonnummer"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" 
					. htmlspecialchars(number_format($row["Gesamtbetrag"], 2), ENT_QUOTES, 'UTF-8') 
					. "€<br>" . "[🚗: " 
					. htmlspecialchars(number_format($row["Lieferkosten"], 2), ENT_QUOTES, 'UTF-8') 
					. "€]</td>";
				echo "<td>" . htmlspecialchars($row["Zahlungsmethode"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["Anmerkungen"], ENT_QUOTES, 'UTF-8') . "</td>";
				//echo "<td>" . htmlspecialchars(number_format($row["Lieferkosten"], 2) . "€", ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td class='order-content'>" . $row["Lieferinhalt"]. "</td>";
				echo "<td><button onclick='showForm(" . intval($row["BestellungID"]) . ")'>Freigeben</button>";
				echo "<br><br><button class='delete-btn' onclick='deleteOrder(" . intval($row["BestellungID"]) . ")'>Löschen</button></td>";
				echo "<td>" . htmlspecialchars($row["bestell_typ"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>";
				echo "Bestellzeit: " . htmlspecialchars($row["Bestelldatum"], ENT_QUOTES, 'UTF-8');
				if (!empty($row["Liefertermin"])) {
					echo "<br><br>Lieferzeit: " . htmlspecialchars($row["Liefertermin"], ENT_QUOTES, 'UTF-8');
				} elseif (!empty($row["Abholtermin"])) {
					echo "<br><br>Abholzeit: " . htmlspecialchars($row["Abholtermin"], ENT_QUOTES, 'UTF-8');
				} else {
					echo "Keine Liefer- oder Abholzeit angegeben";
				}
				echo "</td>";
				echo "Bestelldatum: " . htmlspecialchars($row["Bestelldatum"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "</tr>";
			}

			echo "</table>";
			echo "<br>";
			echo "<h1>Alle heutige Bestellungen</h1>";
			echo "<table>";
			echo "<tr>
					<th>Bestell-ID</th>
					<th>Kundenname</th>
					<th>Adresse</th>
					<th>Telefonnummer</th>
					<th>Gesamtbetrag (inklusive Lieferkosten)</th>
					<th>Zahlungsmethode</th>
					<th>Anmerkungen</th>
					<th>Lieferinhalt</th>
					<th>Stornieren</th>
					<th>Bestell-Typ</th>
					<th>Uhrzeit</th>
				  </tr>";

			while($row = $result_all_orders->fetch_assoc()) {
				// Überprüfen, ob die Bestellung storniert ist
				$isCancelled = $row["Freigegeben"] == 4;
				
				// Zeile entsprechend markieren
				$rowClass = $isCancelled ? 'cancelled' : '';

				echo "<tr class='$rowClass'>";
				echo "<td>" . htmlspecialchars($row["BestellungID"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["KundeName"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["KundeAdresse"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["KundeTelefonnummer"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" 
					. htmlspecialchars(number_format($row["Gesamtbetrag"], 2), ENT_QUOTES, 'UTF-8') 
					. "€<br>" . "[🚗: " 
					. htmlspecialchars(number_format($row["Lieferkosten"], 2), ENT_QUOTES, 'UTF-8') 
					. "€]</td>";
				echo "<td>" . htmlspecialchars($row["Zahlungsmethode"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>" . htmlspecialchars($row["Anmerkungen"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td class='order-content'>" . $row["Lieferinhalt"]. "</td>";
				
				// Stornieren-Button
				$buttonDisabled = $isCancelled ? 'disabled' : '';
				echo "<td><button class='delete-btn' onclick='cancelOrder(" . intval($row["BestellungID"]) . ")' $buttonDisabled>Stornieren</button></td>";

				echo "<td>" . htmlspecialchars($row["bestell_typ"], ENT_QUOTES, 'UTF-8') . "</td>";
				echo "<td>";
				echo "Bestellzeit: " . htmlspecialchars($row["Bestelldatum"], ENT_QUOTES, 'UTF-8');
				if (!empty($row["Liefertermin"])) {
					echo "<br><br>Lieferzeit: " . htmlspecialchars($row["Liefertermin"], ENT_QUOTES, 'UTF-8');
				} elseif (!empty($row["Abholtermin"])) {
					echo "<br><br>Abholzeit: " . htmlspecialchars($row["Abholtermin"], ENT_QUOTES, 'UTF-8');
				} else {
					echo "Keine Liefer- oder Abholzeit angegeben";
				}
				echo "</td>";
				echo "</tr>";
			}

			echo "</table>";

		} else {
			echo "Keine Bestellungen für heute.";
		}

		$conn->close();
		?>

        
        <!-- Formular zur Freigabe der Bestellung 
        <div id="form-container" style="display: none;">
            <h2>Bestellung freigeben</h2>
            <form id="release-form">
                <input type="hidden" id="order-id" name="order-id">
                <label for="delivery-time">Voraussichtliche Lieferzeit (in Minuten)</label>
                <input type="number" id="delivery-time" name="delivery-time" required>
                <button type="submit">Freigeben</button>
            </form>
        </div>-->
		<!-- Formular zur Freigabe der Bestellung -->
		<div id="form-container" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
			<h2>Bestellung freigeben</h2>
			<form id="release-form">
				<input type="hidden" id="order-id" name="order-id">
				<label for="delivery-time">Voraussichtliche Lieferzeit (in Minuten)</label>
				<input type="number" id="delivery-time" name="delivery-time" required>
				<div style="margin-top: 15px;">
					<button type="submit">Freigeben</button>
					<button type="button" onclick="closePopup()" style="background-color: #dc3545; color: white; border: none; padding: 8px 16px; cursor: pointer;">Abbrechen</button>
				</div>
			</form>
		</div>

		<script>
			// Funktion zum Anzeigen des Pop-ups
			function showPopup(orderId) {
				document.getElementById("order-id").value = orderId;  // Setzt die Bestell-ID
				document.getElementById("form-container").style.display = "block";  // Zeigt das Formular an
			}

			// Funktion zum Schließen des Pop-ups
			function closePopup() {
				document.getElementById("form-container").style.display = "none";
			}

			// Event-Listener für das Formular
			document.getElementById("release-form").addEventListener("submit", function(event) {
				event.preventDefault(); // Verhindert das Standard-Formular-Absenden

				// Hier könnte der Code zum Senden der Daten an den Server stehen, z.B. via AJAX

				closePopup(); // Schließt das Pop-up nach dem Freigeben
			});
		</script>

    </div>

    <script>
        function showForm(orderId) {
            document.getElementById('form-container').style.display = 'block';
            document.getElementById('order-id').value = orderId;
        }

        document.getElementById('release-form').addEventListener('submit', function(event) {
			event.preventDefault();
			const orderId = document.getElementById('order-id').value;
			const deliveryTime = document.getElementById('delivery-time').value;

			// Weiterleitung zu BelegeNormalErsteller.php mit den URL-Parametern
			window.location.href = `https://bros-bestellshop.de/order/Belegerstellung/BelegeNormalErsteller.php?order-id=${orderId}&delivery-time=${deliveryTime}`;
		});

        function deleteOrder(orderId) {
            if (confirm("Möchten Sie diese Bestellung wirklich löschen?")) {
                fetch('../order/delete_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'order-id': orderId
                    })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Seite neu laden, um die aktualisierte Liste anzuzeigen
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
		
		function cancelOrder(orderId) {
			if (confirm("Möchten Sie diese Bestellung wirklich stornieren?")) {
				fetch('../order/cancel_order.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: new URLSearchParams({
						'order-id': orderId
					})
				})
				.then(response => response.text())
				.then(data => {
					alert(data);
					location.reload(); // Seite neu laden, um die aktualisierte Liste anzuzeigen
				})
				.catch(error => {
					console.error('Error:', error);
				});
			}
		}
    </script>
	
	<script>
    // Ton abspielen, falls $playSound auf true gesetzt ist
    <?php if ($playSound): ?>
        document.getElementById('new-order-sound').play().catch(error => {
            console.log("Ton konnte nicht automatisch abgespielt werden:", error);
        });
    <?php endif; ?>
</script>
</body>
</html>
