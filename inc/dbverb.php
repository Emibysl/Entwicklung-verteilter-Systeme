<?php
$dbServername = "localhost";  
$dbUsername = "root";
$dbPassword = "";            
$dbName = "datenbank_verteiltesysteme";  
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Überprüfen, ob Verbindung erfolgreich war
if ($conn->connect_error) {
    error_log("Verbindung fehlgeschlagen: " . $conn->connect_error); // Protokolliere den Fehler
    die("Verbindung zur Datenbank konnte nicht hergestellt werden."); // Allgemeine Fehlermeldung
}

// Setzen des Zeichensatzes auf utf8mb4
$conn->set_charset("utf8mb4");
