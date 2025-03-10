<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['waittime_num'], $_POST['waittime_einheit'])) {
    // Eingaben validieren und bereinigen
    $waittimeNum = (int) $_POST['waittime_num'];
    $waittimeEinheit = htmlspecialchars($_POST['waittime_einheit'], ENT_QUOTES, 'UTF-8');

    // Wartezeit zusammenbauen (z.B. "30 Minuten")
    $waittime = $waittimeNum . ' ' . $waittimeEinheit;

    // In eine Textdatei speichern
    file_put_contents('wartezeit.txt', $waittime);

    $_SESSION['success_message'] = "Die Wartezeit wurde erfolgreich aktualisiert!";
}
header("Location: ../waittime.php");
$conn->close();
exit();
