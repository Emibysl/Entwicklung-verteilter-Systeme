<?php
<<<<<<< HEAD
// Lokale XAMPP-Datenbankverbindung
$dbServername = "localhost";  // Nicht mehr IONOS-Servername!
$dbUsername = "root";         // XAMPP Standardbenutzer
$dbPassword = "";             // Kein Passwort standardmäßig
$dbName = "db_verteilteSysteme";      // Name der importierten DB
=======
$dbServername = "localhost";  
$dbUsername = "root";
$dbPassword = "";            
$dbName = "datenbank_verteiltesysteme";  
>>>>>>> 148b942a88c14aeadf3032463097e7e9c6bcbe28
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Überprüfen, ob Verbindung erfolgreich war
if ($conn->connect_error) {
    error_log("Verbindung fehlgeschlagen: " . $conn->connect_error); // Protokolliere den Fehler
    die("Verbindung zur Datenbank konnte nicht hergestellt werden."); // Allgemeine Fehlermeldung
}

// Setzen des Zeichensatzes auf utf8mb4
$conn->set_charset("utf8mb4");
