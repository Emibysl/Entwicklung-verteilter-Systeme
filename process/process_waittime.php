<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wartezeit_num'], $_POST['wartezeit_einheit'])) {
    // Eingaben validieren und bereinigen
    $wartezeitNum = (int) $_POST['wartezeit_num'];
    $wartezeitEinheit = htmlspecialchars($_POST['wartezeit_einheit'], ENT_QUOTES, 'UTF-8');
    
    // Wartezeit zusammenbauen (z.B. "30 Minuten")
    $wartezeit = $wartezeitNum . ' ' . $wartezeitEinheit;
    
    // In eine Textdatei speichern
    file_put_contents('wartezeit.txt', $wartezeit);
    
    $_SESSION['success_message'] = "Die Wartezeit wurde erfolgreich aktualisiert!";
}


// Weiterleitung zurück zum Dashboard
header("Location: wartezeit_dashboard.php");
$conn->close();
exit();
?>
