<?php
session_start(); // Démarre la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['email'])) {
    // Vérifier le rôle de l'utilisateur
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        // Si l'utilisateur est administrateur, diriger vers le tableau de bord admin
        $profil_link = "../admin/admin_dashboard.php"; 
    } else {
        // Si l'utilisateur est normal, diriger vers compteProfil.php
        $profil_link = "../compteProfil/compteProfil.php";
    }
} else {
    // L'utilisateur n'est pas connecté, le lien doit diriger vers la page de connexion/inscription
    $profil_link = "../profil/profil.php";
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
        <a href="../forum/forum.php"><button class="start-button">Commencer l'aventure</button></a>
    </div>

    <div class="menu" id="menu">
        <a href="index.php">Accueil</a>
        <a href="../presentation/presentation.php">Présentation</a>
        <a href="../forum/forum.php">Forum</a>
        <a href="<?php echo $profil_link; ?>">Mon Profil</a>
    </div>

</body>
<script src="../js/scriptIndex.js"></script>
</html>
