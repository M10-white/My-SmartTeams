<?php
session_start(); // Démarre la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['email'])) {
    // Vérifier le rôle de l'utilisateur
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        // Si l'utilisateur est administrateur, diriger vers le tableau de bord admin
        $profil_link = "../admin/dashboard.php"; 
    } else {
        // Si l'utilisateur est normal, diriger vers compteProfil.php
        $profil_link = "../compteProfil/compteProfil.php";
    }
} else {
    // L'utilisateur n'est pas connecté, le lien doit diriger vers la page de connexion/inscription
    $profil_link = "../profil/profil.php";
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header("Location: ../connexion/connexion.php"); // Redirection si non connecté
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_smartteams";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupération des informations de l'utilisateur depuis la session
$email = $_SESSION['email'];
$prenom = $_SESSION['prenom'];
$nom = $_SESSION['nom'];
$ville = $_SESSION['ville'];
$formation = $_SESSION['formation'];
$id = $_SESSION['id'];
$status = $_SESSION['status'];
$date_inscription = $_SESSION['date_inscription'];

// Mise à jour des informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $new_prenom = $conn->real_escape_string($_POST['prenom']);
    $new_nom = $conn->real_escape_string($_POST['nom']);
    $new_email = $conn->real_escape_string($_POST['email']);
    $new_ville = $conn->real_escape_string($_POST['ville']);
    $new_formation = $conn->real_escape_string($_POST['formation']);

    $update_query = "UPDATE utilisateurs SET prenom='$new_prenom', nom='$new_nom', email='$new_email', ville='$new_ville', formation='$new_formation' WHERE id='$id'";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Informations mises à jour avec succès !');</script>";
        $_SESSION['prenom'] = $new_prenom;
        $_SESSION['nom'] = $new_nom;
        $_SESSION['email'] = $new_email;
        $_SESSION['ville'] = $new_ville;
        $_SESSION['formation'] = $new_formation;
    } else {
        echo "<script>alert('Erreur lors de la mise à jour des informations.');</script>";
    }
}

// Suppression du compte utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $delete_query = "DELETE FROM utilisateurs WHERE id='$id'";
    if ($conn->query($delete_query) === TRUE) {
        session_destroy(); // Détruire la session
        header("Location: ../accueil/index.php");
        exit();
    } else {
        echo "<script>alert('Erreur lors de la suppression du compte.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - My SmartTeams</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="compteProfil.css">
    <link rel="stylesheet" href="../forum/forum.css">
</head>
<body>

    <header class="header">
        <span class="menu-icon" onclick="toggleMenu()">☰</span>
    </header>

    <div class="profile-containere">
        <h1>Bienvenue, <?php echo htmlspecialchars($prenom); ?> !</h1>
        <p>Tu es un <?php echo htmlspecialchars($status); ?> !</p>
        <p>Tu es en <?php echo htmlspecialchars($formation); ?>.</p>
        <p>Dans le campus de <?php echo htmlspecialchars($ville); ?>.</p>
        <p>Tu nous as rejoint le <?php echo htmlspecialchars($date_inscription); ?></p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>

        <form action="deconnexion.php" method="post">
            <button type="submit" class="logout-button">Déconnexion</button>
        </form>
    </div>

    <div class="profile-containere">
    <form method="POST">
            <h2>Modifier vos informations</h2>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
            
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
            
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="ville">Ville :</label>
            <input type="text" name="ville" id="ville" value="<?php echo htmlspecialchars($ville); ?>" required>
            
            <label for="formation">Formation :</label>
            <input type="text" name="formation" id="formation" value="<?php echo htmlspecialchars($formation); ?>" required>
            
            <button type="submit" name="update" class="update-button">Mettre à jour</button>
        </form>

        <form method="POST">
            <h2>Supprimer votre compte</h2>
            <button type="submit" name="delete" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">Supprimer mon compte</button>
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
