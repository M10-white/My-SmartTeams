<?php
session_start(); // Démarre la session

// Détruire toutes les sessions
session_unset(); // Libère toutes les variables de session
session_destroy(); // Détruit la session

header("Location: ../connexion/connexion.php"); // Redirection vers la page de connexion
exit();
?>
