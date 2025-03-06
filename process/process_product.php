<?php
// process_product.php
session_start();
require_once 'inc/dbverb.php';
require_once 'inc/functions.php';


/* PRODUKT LÖSCHEN */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
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
    header("Location: ../mainDashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Unterscheide, ob ein Produkt bearbeitet (Update) oder neu angelegt (Insert) wird
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // PRODUKT BEARBEITEN (Update)
        $id = $_POST['id'];
        $name = sanitizeInput($_POST['name']);
        $preis = floatval($_POST['preis']);
        $beschreibung = sanitizeInput($_POST['beschreibung']);
        $allergien = isset($_POST['allergien']) ? implode(',', $_POST['allergien']) : '';
        $kategorie = sanitizeInput($_POST['kategorie']);

        // Wenn ein neues Bild hochgeladen wurde, verarbeite es
        if (!empty($_FILES['bild']['name'])) {
            $targetDir = "../uploads/";
            $bild = basename($_FILES['bild']['name']);
            $targetFile = $targetDir . $bild;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (!in_array($fileType, ["jpg", "jpeg", "png"])) {
                $_SESSION['success_message'] = "Nur JPG, JPEG und PNG Dateien sind erlaubt.";
                header("Location: ../mainDashboard.php");
                exit();
            }
            if (!move_uploaded_file($_FILES["bild"]["tmp_name"], $targetFile)) {
                $_SESSION['success_message'] = "Fehler beim Hochladen des Bildes.";
                header("Location: ../mainDashboard.php");
                exit();
            }
            $stmt = $conn->prepare("UPDATE produkte SET name=?, preis=?, bild=?, kategorie=?, produktbeschreibung=?, allergien=? WHERE id=?");
            $stmt->bind_param("sdssssi", $name, $preis, $bild, $kategorie, $beschreibung, $allergien, $id);
        } else {
            // Kein neues Bild, daher ohne Bildupdate
            $stmt = $conn->prepare("UPDATE produkte SET name=?, preis=?, kategorie=?, produktbeschreibung=?, allergien=? WHERE id=?");
            $stmt->bind_param("sdsssi", $name, $preis, $kategorie, $beschreibung, $allergien, $id);
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
        $kategorie = sanitizeInput($_POST['kategorie']);
        $beschreibung = sanitizeInput($_POST['beschreibung']);
        $allergien = isset($_POST['allergien']) ? implode(',', $_POST['allergien']) : '';
        $preis = floatval($_POST['preis']);
        $bild = '';

        // Bildverarbeitung, falls ein Bild hochgeladen wurde
        if (isset($_FILES['bild']) && $_FILES['bild']['error'] === 0) {
            $targetDir = "uploads/";
            $bild = basename($_FILES['bild']['name']);
            $targetFile = $targetDir . $bild;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (!in_array($fileType, ["jpg", "jpeg", "png"])) {
                $_SESSION['success_message'] = "Nur JPG, JPEG und PNG Dateien sind erlaubt.";
                header("Location: ../products.php");
                exit();
            }
            if (!move_uploaded_file($_FILES["bild"]["tmp_name"], $targetFile)) {
                $_SESSION['success_message'] = "Fehler beim Hochladen des Bildes.";
                header("Location: ../products.php");
                exit();
            }
        }

        $sql = "INSERT INTO produkte (name, preis, bild, kategorie, produktbeschreibung, allergien) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sdssss", $name, $preis, $bild, $kategorie, $beschreibung, $allergien);
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
