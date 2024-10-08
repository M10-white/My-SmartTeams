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
    <title>Présentation - My SmartTeams</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="presentation.css">
    <link rel="stylesheet" href="../accueil/stylesIndex.css">
</head>
<body>

    <header class="header">
        <span class="menu-icon" onclick="toggleMenu()">☰</span>
    </header>
    <header class="presentation-header">
        <div class="logo-container">
            <img src="../images/favicon.png" alt="Logo de My SmartTeams" class="logo">
            <h1>My SmartTeams</h1>
        </div>
    </header>

    <div class="menu" id="menu">
        <a href="../accueil/index.php">Accueil</a>
        <a href="../presentation/presentation.php">Présentation</a>
        <a href="../forum/forum.php">Forum</a>
        <a href="<?php echo $profil_link; ?>">Mon Profil</a>
    </div>

    <div class="presentation-container">
        <h2>Présentation de la plateforme</h2>
        <p>
            Bienvenue sur My SmartTeams, la plateforme qui connecte les nouveaux étudiants avec les anciens du campus EPSI WIS. 
            Notre objectif est de faciliter l'intégration des nouveaux arrivants en leur offrant un espace où ils peuvent 
            poser des questions, partager des expériences et créer des liens. Grâce à notre interface conviviale, 
            les utilisateurs peuvent facilement naviguer entre les différentes fonctionnalités, y compris les forums de discussion, 
            les événements à venir, et bien plus encore. 
        </p>
        <p>
            Rejoignez-nous pour découvrir tout ce que notre plateforme a à offrir et faire partie d'une communauté dynamique 
            et accueillante !
        </p>
    </div>
    
    <script src="../js/scriptIndex.js"></script>
</body>
</html>
