<?php
session_start();
require_once 'inc/dbverb.php';
require_once 'inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* STADT BEARBEITEN */
    if (isset($_POST['update_staedte'])) {
        $stadt_id   = $_POST['stadt_id'];
        $stadt_name = $_POST['stadt_name'];
        $zone_id    = $_POST['zone_id'];

        if ($zone_id <= 0) {
            $_SESSION['success_message'] = "Die Zone ID muss eine positive Zahl sein.";
            $_SESSION['message_type'] = "error";
        } else {
            // Prüfen, ob zone_id existiert
            $zoneCheck = $conn->prepare("SELECT 1 FROM zonen WHERE zonen_id = ?");
            $zoneCheck->bind_param("i", $zone_id);
            $zoneCheck->execute();
            $zoneExists = $zoneCheck->fetch();
            $zoneCheck->close();

            if (!$zoneExists) {
                $_SESSION['success_message'] = "Die ausgewählte Zone ID existiert nicht.";
                $_SESSION['message_type'] = "error";
            } else {
                // Stadt aktualisieren
                $stmt = $conn->prepare("UPDATE staedte SET stadt_name = ?, zone_id = ? WHERE id = ?");
                $stmt->bind_param("sii", $stadt_name, $zone_id, $stadt_id);
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Die Stadt wurde erfolgreich aktualisiert.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['success_message'] = "Fehler beim Aktualisieren der Stadt: " . $stmt->error;
                    $_SESSION['message_type'] = "error";
                }
                $stmt->close();
            }
        }
        header("Location: ../deliveryZones.php");
        exit();
    }

    /* NEUE STADT HINZUFÜGEN */
    if (isset($_POST['add_stadt'])) {
        $stadt_name = $_POST['stadt_name'];
        $zone_id    = $_POST['zone_id'];

        if ($zone_id <= 0) {
            $_SESSION['success_message'] = "Die Zone ID muss eine positive Zahl sein.";
            $_SESSION['message_type'] = "error";
        } else {
            // Prüfen, ob die Zone existiert
            $zoneCheck = $conn->prepare("SELECT 1 FROM zonen WHERE zonen_id = ?");
            $zoneCheck->bind_param("i", $zone_id);
            $zoneCheck->execute();
            $zoneExists = $zoneCheck->fetch();
            $zoneCheck->close();

            if (!$zoneExists) {
                $_SESSION['success_message'] = "Die eingegebene Zone existiert nicht.";
                $_SESSION['message_type'] = "error";
            } else {
                // Neue Stadt einfügen
                $stmt = $conn->prepare("INSERT INTO staedte (stadt_name, zone_id) VALUES (?, ?)");
                $stmt->bind_param("si", $stadt_name, $zone_id);
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Neue Stadt wurde erfolgreich hinzugefügt.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['success_message'] = "Fehler beim Hinzufügen der Stadt: " . $stmt->error;
                    $_SESSION['message_type'] = "error";
                }
                $stmt->close();
            }
        }
        header("Location: ../deliveryZones.php");
        exit();
    }

    /* STADT LÖSCHEN */
    if (isset($_POST['delete_stadt'])) {
        $stadt_id = filter_var($_POST['delete_stadt'], FILTER_VALIDATE_INT);
        if ($stadt_id !== false) {
            $stmt = $conn->prepare("DELETE FROM staedte WHERE id = ?");
            $stmt->bind_param("i", $stadt_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Stadt wurde erfolgreich gelöscht.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['success_message'] = "Fehler beim Löschen der Stadt.";
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['success_message'] = "Ungültige Stadt-ID.";
            $_SESSION['message_type'] = "error";
        }
        header("Location: ../deliveryZones.php");
        exit();
    }

    /* ZONE BEARBEITEN */
    if (isset($_POST['update_zonen'])) {
        $zone_id            = $_POST['zone_id'];
        $mindestbestellwert = $_POST['mindestbestellwert'];
        $lieferkosten       = $_POST['lieferkosten'];

        if ($mindestbestellwert < 0 || $lieferkosten < 0) {
            $_SESSION['success_message'] = "Mindestbestellwert und Lieferkosten müssen positive Werte sein.";
            $_SESSION['message_type'] = "error";
        } else {
            $stmt = $conn->prepare("UPDATE zonen SET mindestbestellwert = ?, lieferkosten = ? WHERE zonen_id = ?");
            $stmt->bind_param("ddi", $mindestbestellwert, $lieferkosten, $zone_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Die Zone wurde erfolgreich aktualisiert.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['success_message'] = "Fehler beim Aktualisieren der Zone: " . $stmt->error;
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        }
        header("Location: ../deliveryZones.php");
        exit();
    }

    /* NEUE ZONE HINZUFÜGEN */
    if (isset($_POST['add_zone'])) {
        $mindestbestellwert = $_POST['mindestbestellwert'];
        $lieferkosten       = $_POST['lieferkosten'];

        if ($mindestbestellwert < 0 || $lieferkosten < 0) {
            $_SESSION['success_message'] = "Mindestbestellwert und Lieferkosten müssen positive Werte sein.";
            $_SESSION['message_type'] = "error";
        } else {
            // Ermitteln der neuen Zone-ID: höchster zonen_id + 1
            $sqlMaxId = "SELECT MAX(zonen_id) AS max_id FROM zonen";
            $resultMax = $conn->query($sqlMaxId);
            $rowMax = $resultMax->fetch_assoc();
            $newZoneId = ((int)$rowMax['max_id']) + 1;

            $stmt = $conn->prepare("INSERT INTO zonen (zonen_id, mindestbestellwert, lieferkosten) VALUES (?, ?, ?)");
            $stmt->bind_param("idd", $newZoneId, $mindestbestellwert, $lieferkosten);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Neue Zone wurde erfolgreich hinzugefügt.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['success_message'] = "Fehler beim Hinzufügen der Zone: " . $stmt->error;
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        }
        header("Location: ../deliveryZones.php");
        exit();
    }


    /* ZONE LÖSCHEN */
    if (isset($_POST['delete_zone'])) {
        $zone_id = filter_var($_POST['delete_zone'], FILTER_VALIDATE_INT);
        if ($zone_id !== false) {
            $stmt = $conn->prepare("DELETE FROM zonen WHERE zonen_id = ?");
            $stmt->bind_param("i", $zone_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Zone wurde erfolgreich gelöscht.";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['success_message'] = "Fehler beim Löschen der Zone.";
                $_SESSION['message_type'] = "error";
            }
            $stmt->close();
        } else {
            $_SESSION['success_message'] = "Ungültige Zone-ID.";
            $_SESSION['message_type'] = "error";
        }
        header("Location: ../deliveryZones.php");
        exit();
    }
}

$conn->close();
