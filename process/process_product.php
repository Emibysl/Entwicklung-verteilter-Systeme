<?php
session_start();
require_once 'inc/dbverb.php'; // DB Verbindung herstellen
require_once 'inc/functions.php'; // Funktionen einbinden


//Produkt löschen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    //Validierung der ID
    $delete_id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
    if ($delete_id !== false) {
        // Lösche das Produkt
        $stmt = $conn->prepare("DELETE FROM produkte WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $delete_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Produkt erfolgreich gelöscht!";
            } else {
                $_SESSION['success_message'] = "Fehler beim Löschen des Produkts.";
            }
            $stmt->close();
        } else {
            $_SESSION['success_message'] = "Fehler bei der Vorbereitung der Anweisung: " . $conn->error;
        }
    } else {
        $_SESSION['success_message'] = "Ungültige Produkt-ID.";
    }
    // Umleitung auf deliveryZones.php
    header("Location: ../mainDashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Unterscheiden, ob Produkt bearbeitet oder neu angelegt wird (ID auf Existenz prüfn)
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Produkt bearbeiten
        $id = $_POST['id'];
        $name = sanitizeInput($_POST['name']);
        $price = floatval($_POST['preis']);
        $description = sanitizeInput($_POST['beschreibung']);
        $allergies = isset($_POST['allergien']) ? implode(',', $_POST['allergien']) : '';
        $category = sanitizeInput($_POST['kategorie']);

        // Wenn neues Bild hochgeladen wurde, verarbeiten
        if (!empty($_FILES['bild']['name'])) {
            $targetDir = "../uploads";
            $picture = basename($_FILES['bild']['name']);
            $targetFile = $targetDir . $picture;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (!in_array($fileType, ["jpg", "jpeg", "png"])) {
                $_SESSION['success_message'] = "Nur JPG, JPEG und PNG Dateien sind erlaubt.";
                $_SESSION['message_type'] = "error";
                header("Location: ../mainDashboard.php");
                exit();
            }
            if (!move_uploaded_file($_FILES["bild"]["tmp_name"], $targetFile)) {
                $_SESSION['success_message'] = "Fehler beim Hochladen des Bildes.";
                $_SESSION['message_type'] = "error";
                header("Location: ../mainDashboard.php");
                exit();
            }
            $stmt = $conn->prepare("UPDATE produkte SET name=?, preis=?, bild=?, kategorie=?, produktbeschreibung=?, allergien=? WHERE id=?");
            $stmt->bind_param("sdssssi", $name, $price, $picture, $category, $description, $allergies, $id);
        } else {
            // Kein neues Bild, daher ohne Bildupdate
            $stmt = $conn->prepare("UPDATE produkte SET name=?, preis=?, kategorie=?, produktbeschreibung=?, allergien=? WHERE id=?");
            $stmt->bind_param("sdsssi", $name, $price, $category, $description, $allergies, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Produkt wurde erfolgreich aktualisiert!";
        } else {
            $_SESSION['success_message'] = "Fehler beim Aktualisieren: " . $stmt->error;
        }
        $stmt->close();
        header("Location: ../mainDashboard.php");
        exit();
    } else {
        // PRODUKT HINZUFÜGEN (Insert)
        $name = sanitizeInput($_POST['name']);
        $category = sanitizeInput($_POST['kategorie']);
        $description = sanitizeInput($_POST['beschreibung']);
        $allergies = isset($_POST['allergien']) ? implode(',', $_POST['allergien']) : '';
        $price = floatval($_POST['preis']);
        $picture = '';
        // Bildverarbeitung, falls ein Bild hochgeladen wurde
        if (isset($_FILES['bild']) && $_FILES['bild']['error'] === 0) {
            $targetDir = "../uploads";
            $picture = basename($_FILES['bild']['name']);
            $targetFile = $targetDir . $picture;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (!in_array($fileType, ["jpg", "jpeg", "png"])) {
                $_SESSION['success_message'] = "Nur JPG, JPEG und PNG Dateien sind erlaubt.";
                $_SESSION['message_type'] = "error";
                header("Location: ../products.php");
                exit();
            }
            if (!move_uploaded_file($_FILES["bild"]["tmp_name"], $targetFile)) {
                $_SESSION['success_message'] = "Fehler beim Hochladen des Bildes.";
                $_SESSION['message_type'] = "error";
                header("Location: ../products.php");
                exit();
            }
        }

        $sql = "INSERT INTO produkte (name, preis, bild, kategorie, produktbeschreibung, allergien) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sdssss", $name, $price, $picture, $category, $description, $allergies);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Produkt wurde erfolgreich hinzugefügt!";
            } else {
                $_SESSION['success_message'] = "Fehler beim Hinzufügen des Produkts: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['success_message'] = "Fehler bei der Vorbereitung der Anweisung: " . $conn->error;
        }
        header("Location: ../products.php");
        exit();
    }
}
$conn->close();
