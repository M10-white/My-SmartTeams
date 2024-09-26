<?php
session_start();
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

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['email'])) {
    // L'utilisateur est connecté, le lien doit diriger vers compteProfil.php
    $profil_link = "../compteProfil/compteProfil.php";
} else {
    // L'utilisateur n'est pas connecté, le lien doit diriger vers la page de connexion/inscription
    $profil_link = "../profil/profil.php";
}

// Vérifier si un nouveau sujet est créé
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $theme = $conn->real_escape_string($_POST['theme']); 
    
    $user_id = $_SESSION['id'];

    $sql = "INSERT INTO topics (user_id, title, description, theme) VALUES ('$user_id', '$title', '$description', '$theme')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
        var popup = document.createElement('div');
        popup.className = 'popup success';
        popup.textContent = 'Sujet créé avec succès!';
        document.body.appendChild(popup);
        setTimeout(function() {
            popup.style.animation = 'popupHide 0.5s forwards'; 
            setTimeout(function() {
                popup.remove(); 
            }, 500);
        }, 2000);
    </script>";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Gestion de la recherche
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$theme_filter = isset($_GET['theme_filter']) ? $conn->real_escape_string($_GET['theme_filter']) : '';

$sql = "SELECT topics.*, utilisateurs.prenom, utilisateurs.ville, utilisateurs.status 
        FROM topics 
        JOIN utilisateurs ON topics.user_id = utilisateurs.id 
        WHERE (topics.title LIKE '%$search_query%' OR topics.description LIKE '%$search_query%')";

// Ajouter un filtre par thème si un thème a été sélectionné
if (!empty($theme_filter)) {
    $sql .= " AND topics.theme = '$theme_filter'";
}

$sql .= " ORDER BY topics.created_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - My SmartTeams</title>
    <link rel="stylesheet" href="forum.css">
</head>
<body>

    <header class="header">
        <span class="menu-icon" onclick="toggleMenu()">☰</span>
    </header>  
    <div class="content">
        <h1 style="margin-top: 2%;">Forum de discussion</h1>      
    </div>

    <div class="forum-container">
    <!-- Formulaire pour créer un nouveau sujet -->
    <div class="new-topic" style="margin: 60% auto 20px auto;">
        <h2 style="color: #333;">Créer un nouveau sujet</h2>
        <form action="forum.php" method="POST">
            <input type="text" name="title" placeholder="Titre du sujet" required><br>
            <textarea name="description" placeholder="Description du sujet" required></textarea><br>
            <h2 style="color: #333;" for="theme">Choisissez un thème :</h2>
            <select name="theme" id="theme" required>
            <option value="Général">Général</option>
            <option value="Technologie">Technologie</option>
            <option value="Études">Études</option>
            <option value="Campus">Campus</option>
            </select>
            <button type="submit">Publier</button>
        </form>
    </div>

    <div class="search-container">
    <form action="#topics" method="GET">
        <input type="text" name="search" placeholder="Rechercher des sujets ou des réponses..." required>
        <select name="theme_filter">
            <option value="">Tous les thèmes</option>
            <option value="Général">Général</option>
            <option value="Technologie">Technologie</option>
            <option value="Études">Études</option>
            <option value="Campus">Campus</option>
            <option value="Alternance">Alternance</option>
            <option value="Evenement">Evenement</option>
            <option value="Nourriture">Nourriture</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>
    </div>

    <!-- Liste des sujets -->
    <a name="topics"></a>
    <div class="topics-list">
        <h2>Liste des sujets</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='topic'>";
                echo "<h3><a href='topic.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></h3>";
                echo "<p>Thème : " . htmlspecialchars($row['theme']) . "</p>";
                echo "<p>Campus de " . htmlspecialchars($row['ville']) . "</p>";
                echo "<p>Posté par : " . htmlspecialchars($row['prenom']) . " (". htmlspecialchars($row['status']) .")</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Aucun sujet trouvé.</p>";
        }
        ?>
    </div>
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