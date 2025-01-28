<?php

session_start();
// Überprüfen, ob der Benutzername in der Session gesetzt ist
if (!isset($_SESSION['benutzername'])) {
    //echo "Benutzername nicht gesetzt, Weiterleitung zur Login-Seite...";
    header("Location: ../login.php");
    exit();
} else {
    //echo "Benutzername gesetzt: " . $_SESSION['benutzername'];
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datenbankverbindung herstellen
include_once '../inc/dbverb.php';
if ($conn->connect_error) {
    die("Datenbankverbindung fehlgeschlagen: " . $conn->connect_error);
}

// Erfolgs-/Fehler-Flag für die Meldung
$message = "";
$message_type = "";

// Aktualisierung der Daten beim Speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Staedte Tabelle aktualisieren
    if (isset($_POST['update_staedt'])) {
        $stadt_id = $_POST['stadt_id'];
        $stadt_name = $_POST['stadt_name'];
        $zone_id = $_POST['zone_id'];

        // Validierung: Positive Zone ID prüfen und Existenz in der zonen-Tabelle sicherstellen
        if ($zone_id <= 0) {
            $message = "Die Zone ID muss eine positive Zahl sein.";
            $message_type = "error";
        } else {
            // Existenz der Zone ID prüfen
            $zoneCheck = $conn->prepare("SELECT 1 FROM zonen WHERE zonen_id = ?");
            $zoneCheck->bind_param("i", $zone_id);
            $zoneCheck->execute();
            $zoneExists = $zoneCheck->fetch();
            $zoneCheck->close();

            if (!$zoneExists) {
                $message = "Die ausgewählte Zone ID existiert nicht.";
                $message_type = "error";
            } else {
                // Stadt aktualisieren, wenn Validierungen bestanden wurden
                $stmt = $conn->prepare("UPDATE staedte SET stadt_name = ?, zone_id = ? WHERE id = ?");
                $stmt->bind_param("sii", $stadt_name, $zone_id, $stadt_id);

                if ($stmt->execute()) {
                    $message = "Die Stadt wurde erfolgreich aktualisiert.";
                    $message_type = "success";
                } else {
                    $message = "Fehler beim Aktualisieren der Stadt.";
                    $message_type = "error";
                }
                $stmt->close();
            }
        }
    }

    // Zonen Tabelle aktualisieren
    if (isset($_POST['update_zonen'])) {
        $zone_id = $_POST['zone_id'];
        $mindestbestellwert = $_POST['mindestbestellwert'];
        $lieferkosten = $_POST['lieferkosten'];

        // Validierung: Positive Werte für Mindestbestellwert und Lieferkosten
        if ($mindestbestellwert < 0 || $lieferkosten < 0) {
            $message = "Mindestbestellwert und Lieferkosten müssen positive Werte sein.";
            $message_type = "error";
        } else {
            $stmt = $conn->prepare("UPDATE zonen SET mindestbestellwert = ?, lieferkosten = ? WHERE zonen_id = ?");
            $stmt->bind_param("ddi", $mindestbestellwert, $lieferkosten, $zone_id);

            if ($stmt->execute()) {
                $message = "Die Zone wurde erfolgreich aktualisiert.";
                $message_type = "success";
            } else {
                $message = "Fehler beim Aktualisieren der Zone.";
                $message_type = "error";
            }
            $stmt->close();
        }
    }
}

// Daten aus den Tabellen abrufen
$staedteResult = $conn->query("SELECT id, stadt_name, zone_id FROM staedte WHERE zone_id BETWEEN 1 AND 3");
$zonenResult = $conn->query("SELECT * FROM zonen WHERE zonen_id BETWEEN 1 AND 3");

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Städte und Zonen bearbeiten</title>
	<link rel="stylesheet" href="./assets/css/lieferbereich.css">
</head>
<body>

<?php if ($message): ?>
    <div class="popup <?= $message_type ?>" id="popup"><?= $message ?></div>
    <script>
        document.getElementById("popup").style.display = "block";
        setTimeout(function() {
            document.getElementById("popup").style.display = "none";
        }, 3000); // Popup nach 3 Sekunden ausblenden
    </script>
<?php endif; ?>
<br>
<!-- Button zum Mitarbeiter-Dashboard -->
<a href="dashboardneu.php" class="back-button">Zurück zum Mitarbeiter-Dashboard</a>
<h2>Städte bearbeiten</h2>
<table border="1">
    <tr>
        <!--<th>ID</th>-->
        <th>Stadtname</th>
        <th>Zone ID</th>
        <th>Aktion</th>
    </tr>
    <?php while ($row = $staedteResult->fetch_assoc()): ?>
        <form method="POST">
            <tr>
                <!--<td><?//= $row['id'] ?></td>-->
                <td><input type="text" name="stadt_name" value="<?= $row['stadt_name'] ?>"></td>
                <td><input type="number" name="zone_id" value="<?= $row['zone_id'] ?>" min="1"></td>
                <td>
                    <input type="hidden" name="stadt_id" value="<?= $row['id'] ?>">
                    <button type="submit" name="update_staedt">Speichern</button>
                </td>
            </tr>
        </form>
    <?php endwhile; ?>
</table>

<h2>Zonen bearbeiten</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Mindestbestellwert</th>
        <th>Lieferkosten</th>
        <th>Aktion</th>
    </tr>
    <?php while ($row = $zonenResult->fetch_assoc()): ?>
        <form method="POST">
            <tr>
                <td><?= $row['zonen_id'] ?></td>
                <td><input type="number" step="0.01" name="mindestbestellwert" value="<?= $row['mindestbestellwert'] ?>" min="0"></td>
                <td><input type="number" step="0.01" name="lieferkosten" value="<?= $row['lieferkosten'] ?>" min="0"></td>
                <td>
                    <input type="hidden" name="zone_id" value="<?= $row['zonen_id'] ?>">
                    <button type="submit" name="update_zonen">Speichern</button>
                </td>
            </tr>
        </form>
    <?php endwhile; ?>
</table>
</body>
</html>
