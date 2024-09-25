<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../connexion/connexion.php"); // Redirection si non connecté
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = ""; // Ton mot de passe MySQL
$dbname = "my_smartteams";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

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

// Vérifier si un nouveau sujet est créé
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Récupérer l'ID de l'utilisateur depuis la session
    $user_id = $_SESSION['id']; // Assurez-vous que l'ID de l'utilisateur est stocké dans la session

    // Insertion du nouveau sujet dans la table topics
    $sql = "INSERT INTO topics (user_id, title, description) VALUES ('$user_id', '$title', '$description')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Sujet créé avec succès!');</script>";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Récupérer tous les sujets
$sql = "SELECT topics.*, utilisateurs.prenom FROM topics JOIN utilisateurs ON topics.user_id = utilisateurs.id ORDER BY topics.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - My SmartTeams</title>
    <link rel="stylesheet" href="forum.css">
    <link rel="stylesheet" href="../accueil/stylesIndex.css">
</head>
<body>

    <header class="header">
        <span class="menu-icon" onclick="toggleMenu()">☰</span>
    </header>  
    <div class="content">
        <h1 style="margin-top: 2%;">Forum de discussion</h1>      
    </div>

    <!-- Formulaire pour créer un nouveau sujet -->
    <div class="new-topic" style="margin: 60% auto 20px auto;">
        <h2 style="color: #333;">Créer un nouveau sujet</h2>
        <form action="forum.php" method="POST">
            <input type="text" name="title" placeholder="Titre du sujet" required><br>
            <textarea name="description" placeholder="Description du sujet" required></textarea><br>
            <button type="submit">Créer le sujet</button>
        </form>
    </div>

    <!-- Liste des sujets -->
    <div class="topics-list">
        <h2>Liste des sujets</h2>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='topic'>
                        <h3><a href='topic.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></h3>
                        <p>Créé par : " . htmlspecialchars($row['prenom']) . " le " . $row['created_at'] . "</p>
                        <p>" . htmlspecialchars($row['description']) . "</p>
                      </div>";
            }
        } else {
            echo "<p>Aucun sujet trouvé.</p>";
        }
        ?>
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