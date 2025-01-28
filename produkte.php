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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produktverwaltung</title>
    <link rel="stylesheet" href="./assets/css/produkte.css">
</head>
<body>
	<br>
	<!-- Back Button -->
	<a href="dashboardneu.php" class="back-button">Zurück zum Dashboard</a>
    <div class="container">
        <h1>Produktverwaltung</h1>
        <form action="produkte.php" method="POST" enctype="multipart/form-data">
            <label for="name">Produktname:</label><br>
            <input type="text" id="name" name="name" required><br>
			<label for="kategorie">Kategorie auswählen:</label><br>
			<select id="kategorieDropdown" onchange="setKategorie()" required>
				<option value="">Bitte wählen</option> <!-- Standardoption für die Auswahl -->
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

			</select><br><br>
			<label for="kategorieFeld">Ausgewählte Kategorie:</label><br>
			<input type="text" id="kategorieFeld" name="kategorie" readonly required><br><br>
			
			<label for="beschreibung">Produktbeschreibung:</label><br>
			<textarea id="beschreibung" name="beschreibung"></textarea><br>
			
			<label>Allergien (Mehrfachauswahl möglich):</label><br>
			<input type="checkbox" name="allergien[]" value="a"> A<br>
			<input type="checkbox" name="allergien[]" value="b"> B<br>
			<input type="checkbox" name="allergien[]" value="c"> C<br>
			<input type="checkbox" name="allergien[]" value="d"> D<br>
			<input type="checkbox" name="allergien[]" value="e"> E<br>
			<input type="checkbox" name="allergien[]" value="f"> F<br>
			<input type="checkbox" name="allergien[]" value="g"> G<br>
			<input type="checkbox" name="allergien[]" value="h"> H<br>
			<input type="checkbox" name="allergien[]" value="i"> I<br>
			<input type="checkbox" name="allergien[]" value="j"> J<br>
			<input type="checkbox" name="allergien[]" value="1"> 1<br>
			<input type="checkbox" name="allergien[]" value="2"> 2<br>
			<input type="checkbox" name="allergien[]" value="3"> 3<br>
			<input type="checkbox" name="allergien[]" value="4"> 4<br>
			<input type="checkbox" name="allergien[]" value="5"> 5<br>
			<input type="checkbox" name="allergien[]" value="6"> 6<br>
			<input type="checkbox" name="allergien[]" value="7"> 7<br>
			<input type="checkbox" name="allergien[]" value="8"> 8<br>
			<input type="checkbox" name="allergien[]" value="9"> 9<br>
			<input type="checkbox" name="allergien[]" value="10"> 10<br>
			<input type="checkbox" name="allergien[]" value="12"> 12<br>
			<input type="checkbox" name="allergien[]" value="14"> 14<br>
			<!-- Füge weitere Checkboxen für alle Enum-Werte hinzu -->

			
            <label for="preis">Preis (in Euro):</label><br>
            <input type="number" id="preis" name="preis" step="0.01" required><br>
			
            <label for="bild">Produktbild:</label><br>
            <input type="file" id="bild" name="bild" accept="image/*"><br><br>
			
            <input type="hidden" name="id" id="product_id">
            <input type="submit" value="Produkt hinzufügen">
        </form>

        <div class="products">
            <h2>Bisherige Produkte</h2>
            <?php		
            // Datenbankverbindung herstellen
			include_once '../inc/dbverb.php';



            // Produkt löschen
			if (isset($_POST['delete_id'])) {
				$delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
				if ($delete_id !== false) {
					// Zuerst die Einträge in der Tabelle produkt_zutaten löschen
					$delete_zutaten_stmt = $conn->prepare("DELETE FROM produkt_zutaten WHERE produkt_id = ?");
					if ($delete_zutaten_stmt) {
						$delete_zutaten_stmt->bind_param("i", $delete_id);
						$delete_zutaten_stmt->execute();
						$delete_zutaten_stmt->close();
					}

					// Dann das Produkt aus der Tabelle produkte löschen
					$stmt = $conn->prepare("DELETE FROM produkte WHERE id = ?");
					if ($stmt) {
						$stmt->bind_param("i", $delete_id);
						if ($stmt->execute()) {
							echo "<script>alert('Produkt und zugehörige Zutaten erfolgreich gelöscht!'); window.location.href='produkte.php';</script>";
						} else {
							echo "<script>alert('Fehler beim Löschen des Produkts'); window.location.href='produkte.php';</script>";
						}
						$stmt->close();
					} else {
						echo "<script>alert('Fehler bei der Vorbereitung der Anweisung: " . $conn->error . "'); window.location.href='produkte.php';</script>";
					}
				} else {
					echo "<script>alert('Ungültige Produkt-ID.'); window.location.href='produkte.php';</script>";
				}
			}





            // Produkt bearbeiten
			if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
				$id = $_POST['id'];
				$name = $_POST['name'];
				$preis = $_POST['preis'];
				$beschreibung = $_POST['beschreibung'];
				$allergien = isset($_POST['allergien']) ? implode(',', $_POST['allergien']) : ''; 
				$bild = $_FILES['bild']['name'];
				$kategorie = $_POST['kategorie'];

				$target_dir = "uploads/";
				$target_file = $target_dir . basename($bild);

				// Dateitypüberprüfung
				$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
				if (!empty($bild) && $fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
					echo "Nur JPG, JPEG und PNG Dateien sind erlaubt.";
					exit;
				}

				if (!empty($bild)) {
					if (move_uploaded_file($_FILES["bild"]["tmp_name"], $target_file)) {
						$stmt = $conn->prepare("UPDATE produkte SET name=?, preis=?, bild=?, kategorie=?, produktbeschreibung=?, allergien=? WHERE id=?");
						$stmt->bind_param("sdssssi", $name, $preis, $bild, $kategorie, $beschreibung, $allergien, $id);
					} else {
						echo "Fehler beim Hochladen des Bildes.";
						exit;
					}
				} else {
					$stmt = $conn->prepare("UPDATE produkte SET name=?, preis=?, kategorie=?, produktbeschreibung=?, allergien=? WHERE id=?");
					$stmt->bind_param("sdsssi", $name, $preis, $kategorie, $beschreibung, $allergien, $id);
				}

				if ($stmt->execute()) {
					echo "Produkt erfolgreich aktualisiert!";
				} else {
					echo "Fehler beim Aktualisieren: " . $stmt->error;
				}
				$stmt->close();
			}




            // Neues Produkt hinzufügen
			if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST['id'])) {
				$name = $_POST['name'];
				$preis = $_POST['preis'];
				$beschreibung = $_POST['beschreibung'];
				$allergien = isset($_POST['allergien']) ? implode(',', $_POST['allergien']) : '';
				$bild = $_FILES['bild']['name'];
				$kategorie = $_POST['kategorie'];

				$target_dir = "uploads/";
				$target_file = $target_dir . basename($bild);

				// Dateitypüberprüfung
				$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
				if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
					echo "Nur JPG, JPEG und PNG Dateien sind erlaubt.";
					exit;
				}

				if (move_uploaded_file($_FILES["bild"]["tmp_name"], $target_file)) {
					$stmt = $conn->prepare("INSERT INTO produkte (name, preis, bild, kategorie, produktbeschreibung, allergien) VALUES (?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("sdssss", $name, $preis, $bild, $kategorie, $beschreibung, $allergien);

					if ($stmt->execute()) {
						echo "Neues Produkt erfolgreich hinzugefügt!";
					} else {
						echo "Fehler: " . $stmt->error;
					}
					$stmt->close();
				} else {
					echo "Fehler beim Hochladen des Bildes.";
				}
			}



            // Produkte aus der Datenbank abrufen
            $sql = "SELECT * FROM produkte";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
					echo "<h3>" . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "</h3>";
					echo "<p>Preis: " . htmlspecialchars($row["preis"], ENT_QUOTES, 'UTF-8') . "€</p>";
					echo "<p>Beschreibung: " . htmlspecialchars($row["produktbeschreibung"], ENT_QUOTES, 'UTF-8') . "</p>";
					echo "<p>Allergien: " . htmlspecialchars($row["allergien"], ENT_QUOTES, 'UTF-8') . "</p>";
					echo "<img src='uploads/" . htmlspecialchars($row["bild"], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "'>";
					echo "<div class='product-buttons'>";
					echo "<form action='produkte.php' method='POST' onsubmit='return confirmDelete();'>";
					echo "<input type='hidden' name='delete_id' value='" . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . "'>";
					echo "<button type='submit'>Löschen</button>";
					echo "</form>";
					echo "<form action='produkte.php' method='POST'>";
					echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . "'>";
					echo "<button type='button' class='edit' onclick='editProduct(" . json_encode($row) . ")'>Bearbeiten</button>";
					echo "</form>";
					echo "</div>";
					echo "</div>";
                }
            } else {
                echo "Keine Produkte vorhanden.";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function editProduct(product) {
			 // Scrollt zum Formular mit der ID 'name' (oder wähle die Formular-ID)
			document.getElementById('name').scrollIntoView({ behavior: 'smooth' });
			
			document.getElementById('product_id').value = product.id;
			document.getElementById('name').value = product.name;
			document.getElementById('preis').value = product.preis;
			document.getElementById('beschreibung').value = product.produktbeschreibung;
			
			// Allergien setzen (die gespeicherten Allergien splitten und Checkboxen aktivieren)
			var allergien = product.allergien ? product.allergien.split(',') : [];

			var checkboxes = document.querySelectorAll('input[name="allergien[]"]');
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = allergien.includes(checkbox.value);
			});

			// Setze die ausgewählte Kategorie im Dropdown-Menü und Textfeld
			document.getElementById('kategorieDropdown').value = product.kategorie;
			document.getElementById('kategorieFeld').value = product.kategorie;
		}


		
		function setKategorie() {
			// Hole den ausgewählten Wert aus dem Dropdown-Menü
			var selectedKategorie = document.getElementById('kategorieDropdown').value;
			
			// Setze den Wert des Textfeldes auf die ausgewählte Kategorie
			document.getElementById('kategorieFeld').value = selectedKategorie;
		}
		
		function confirmDelete() {
            return confirm("Bist du sicher, dass du dieses Produkt löschen möchtest?");
        }
    </script>
</body>
</html>
