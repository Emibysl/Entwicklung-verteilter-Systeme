<?php
// Lokale XAMPP-Datenbankverbindung
$dbServername = "localhost";  // Nicht mehr IONOS-Servername!
$dbUsername = "root";         // XAMPP Standardbenutzer
$dbPassword = "";             // Kein Passwort standardmäßig
$dbName = "db_verteilteSysteme";      // Name der importierten DB
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Überprüfen, ob Verbindung erfolgreich war
if ($conn->connect_error) {
    error_log("Verbindung fehlgeschlagen: " . $conn->connect_error); // Protokolliere den Fehler
    die("Verbindung zur Datenbank konnte nicht hergestellt werden."); // Allgemeine Fehlermeldung
}

// Setzen des Zeichensatzes auf utf8mb4
$conn->set_charset("utf8mb4");
