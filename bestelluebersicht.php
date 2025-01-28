<?php
	session_start();
	if (!isset($_SESSION['benutzername'])) {
		//echo "Benutzername nicht gesetzt, Weiterleitung zur Login-Seite...";
		header("Location: ../login.php");
		exit();
	} else {
		//echo "Benutzername gesetzt: " . $_SESSION['benutzername'];
	}
?>
<!DOCTYPE html>
<!-- bestelluebersicht.php -->
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lieferübersicht</title>
    <!-- Die Seite alle 60 Sekunden neu laden -->
    <meta http-equiv="refresh" content="60">
    <link rel="stylesheet" href="./assets/css/bestelluebersicht.css">
</head>
<body>
	<a href="dashboardneu.php" class="back-button">Zurück zum Dashboard</a>
    <div class="container">
        <h1>Lieferübersicht für heute</h1>
        
        <?php
        include_once '../inc/dbverb.php';
		
        // Bestellungen des aktuellen Tages abfragen, die genehmigt wurden
        $sql = "SELECT * FROM Bestellungentest WHERE DATE(Bestelldatum) = CURDATE() AND Freigegeben = 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>
				<th>Bestell-ID</th>
				<th>Kundenname</th>
				<th>Adresse</th>
				<th>Telefonnummer</th>
				<th>Gesamtbetrag (inklusive Lieferkosten)</th>
				<th>Anmerkungen</th>
				<th>Lieferinhalt</th>
				<th>Voraussichtliche Lieferzeit</th>
				<th>Aktion</th>
			</tr>";
            
            while($row = $result->fetch_assoc()) {				
				// Für jede Bestellung den Lieferinhalt abfragen
				$bestellungID = $row["BestellungID"]; // Rohwert aus der Datenbank
				$sql_items = "SELECT ProduktName, Menge FROM Bestellpositionen WHERE BestellungID = ?";
				$stmt_items = $conn->prepare($sql_items);
				
				// Überprüfen, ob das Statement erfolgreich vorbereitet wurde
				if ($stmt_items) {
					// Den Parameter binden und die Abfrage ausführen
					$stmt_items->bind_param("i", $bestellungID);
					$stmt_items->execute();
					$result_items = $stmt_items->get_result();

					// Lieferinhalt erstellen
					$lieferinhalt = "";
					if ($result_items->num_rows > 0) {
						while ($item = $result_items->fetch_assoc()) {
							$lieferinhalt .= htmlspecialchars($item['ProduktName']) . " (x" . htmlspecialchars($item['Menge']) . "), ";
						}
						$lieferinhalt = rtrim($lieferinhalt, ", "); // Entfernt das letzte Komma
					} else {
						$lieferinhalt = "Keine Artikel vorhanden.";
					}

					echo "<tr>";
					echo "<td>" . htmlspecialchars($row["BestellungID"]) . "</td>";
					echo "<td>" . htmlspecialchars($row["KundeName"]) . "</td>";
					echo "<td>" . htmlspecialchars($row["KundeAdresse"]) . "</td>";
					echo "<td>" . htmlspecialchars($row["KundeTelefonnummer"], ENT_QUOTES, 'UTF-8') . "</td>";
					echo "<td>" 
						. htmlspecialchars(number_format($row["Gesamtbetrag"], 2), ENT_QUOTES, 'UTF-8') 
						. "€<br>" . "🚗: " 
						. htmlspecialchars(number_format($row["Lieferkosten"], 2), ENT_QUOTES, 'UTF-8') 
						. "€</td>";
					echo "<td>" . htmlspecialchars($row["Anmerkungen"], ENT_QUOTES, 'UTF-8') . "</td>";
					echo "<td>" . $lieferinhalt . "</td>";  // Lieferinhalt anzeigen
					echo "<td>" . htmlspecialchars($row["Lieferzeit"]) . " Minuten</td>";
					echo "<td><button class='complete-btn' onclick='completeOrder(" . htmlspecialchars($row["BestellungID"]) . ")'>Als erledigt markieren</button></td>";
					echo "</tr>";
				}
				
				echo "</table>";
			}
		}else {
			echo "Keine genehmigten Bestellungen für heute.";
		}
		$conn->close();
        ?>
    </div>

    <script>
        function completeOrder(orderId) {
            if (confirm("Möchten Sie diese Bestellung als erledigt markieren?")) {
                fetch('../order/complete_order.php', {
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
</body>
</html>
