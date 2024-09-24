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

// Si l'utilisateur est connecté, affiche un message de bienvenue ou le profil
if (isset($_SESSION['email'])) {
    // Vous pouvez inclure des options spécifiques pour les utilisateurs connectés
    $prenom = $_SESSION['prenom'];
    echo "Bienvenue, $prenom";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My SmartTeams</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="stylesIndex.css">
</head>
<body>

    <header class="header">
        <span class="menu-icon" onclick="toggleMenu()">☰</span>
    </header>

    <div class="content">
        <h1 target="_blank">My SmartTeams</h1>        
        <p>La plateforme qui connecte les nouveaux et anciens étudiants du campus EPSI WIS.</p>
        <button class="start-button">Commencer l'aventure</button>
    </div>

    <div class="menu" id="menu">
        <a href="index.php">Accueil</a>
        <a href="../presentation/presentation.php">Présentation</a>
        <a href="../forum/forum.php">Forum</a>
        <a href="<?php echo $profil_link; ?>">Mon Profil</a>
    </div>

    <script src="../js/scriptIndex.js"></script>
</body>
</html>
