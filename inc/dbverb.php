<?php
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "db_verteilteSysteme";
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Überprüfen, ob Verbindung erfolgreich
if ($conn->connect_error) {
    error_log("Verbindung fehlgeschlagen: " . $conn->connect_error);//Fehler protokollieren
    die("Verbindung zur Datenbank konnte nicht hergestellt werden.");//Fehlermeldung
}

// Setzen des Zeichensatzes auf utf8mb4
$conn->set_charset("utf8mb4");
