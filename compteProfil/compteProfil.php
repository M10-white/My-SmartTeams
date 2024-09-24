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

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: ../connexion/connexion.php"); // Redirection si non connecté
    exit();
}

// Récupération des informations de l'utilisateur depuis la session
$email = $_SESSION['email'];
$prenom = $_SESSION['prenom']; // Assurez-vous que 'prenom' est défini lors de la connexion
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - My SmartTeams</title>
    <link rel="stylesheet" href="compteProfil.css">
    <link rel="stylesheet" href="../accueil/stylesIndex.css">
</head>
<body>

    <header class="header">
        <span class="menu-icon" onclick="toggleMenu()">☰</span>
    </header>

    <div class="profile-container">
        <h1>Bienvenue, <?php echo htmlspecialchars($prenom); ?> !</h1>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <!-- Afficher d'autres informations de l'utilisateur ici si nécessaire -->

        <form action="deconnexion.php" method="post">
            <button type="submit" class="logout-button">Déconnexion</button>
        </form>
    </div>

    <div class="menu" id="menu">
        <a href="../accueil/index.php">Accueil</a>
        <a href="../presentation/presentation.php">Présentation</a>
        <a href="../forum/forum.php">Forum</a>
        <a href="<?php echo $profil_link; ?>">Mon Profil</a>
    </div>

</body>
<script src="../js/scriptIndex.js"></script>
</html>
