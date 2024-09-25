<?php 
session_start(); // Démarre la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['email'])) {
    // L'utilisateur est connecté, le lien doit diriger vers compteProfil.php
    $profil_link = "../compteProfil/compteProfil.php";
} else {
    // L'utilisateur n'est pas connecté, le lien doit diriger vers la page de connexion/inscription
    $profil_link = "../profil/profil.php";
}

// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "my_smartteams";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - My SmartTeams</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="profil.css">
    <link rel="stylesheet" href="../accueil/stylesIndex.css">
</head>
<body>

    <div class="back-arrow" onclick="goBack()">←</div>

    <div class="profile-container">
        <h1>Déjà de la <a style="color: #d9524a;">Teams</a> ?</h1>
        <p>Si non inscrivez-vous !</p>
        <a href="../inscription/inscription.php"><button class="signup-button">Inscription</button></a>
        <a href="../connexion/connexion.php"><button class="login-button">Connexion</button></a>
    </div>

</body>
<script>
    function goBack() {
        window.location.href = '../accueil/index.php'; 
    }
</script>
</html>
