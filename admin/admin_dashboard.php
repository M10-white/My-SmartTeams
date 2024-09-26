<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion/connexion.php");
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

// Récupérer le nombre d'utilisateurs
$user_count_query = "SELECT COUNT(*) AS total_users FROM utilisateurs";
$user_count_result = $conn->query($user_count_query);
$user_count = $user_count_result->fetch_assoc()['total_users'];

// Récupérer le nombre de sujets créés
$topics_count_query = "SELECT COUNT(*) AS total_topics FROM topics";
$topics_count_result = $conn->query($topics_count_query);
$topics_count = $topics_count_result->fetch_assoc()['total_topics'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <style>

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #d9524a;
        }

        .card {
            margin: 20px 0;
            padding: 15px;
            background: #e3e9e8;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .card h2 {
            margin: 0 0 10px 0;
        }

        .card p {
            margin: 5px 0;
        }

        .options {
            text-align: center;
            margin-top: 20px;
        }

        .options a {
            margin: 10px;
            padding: 10px 20px;
            background-color: #d9524a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .options a:hover {
            background-color: #b8453c;
        }
    </style>
    <link rel="stylesheet" href="../accueil/stylesIndex.css">
</head>
<body>
<div class="container">
    <h1>Tableau de Bord Administrateur</h1>
    
    <div class="card">
        <h2>Gestion des utilisateurs</h2>
        <p>Total des utilisateurs inscrits : <?php echo $user_count; ?></p>
        <div class="options">
            <a href="manage_users.php">Voir les utilisateurs</a>
        </div>
    </div>

    <div class="card">
        <h2>Modération du Forum</h2>
        <p>Total des sujets créés : <?php echo $topics_count; ?></p>
        <div class="options">
            <a href="manage_topics.php">Modérer les sujets</a>
        </div>
    </div>

    <div class="card">
        <h2>Statistiques Générales</h2>
        <p>Nombre total de connexions, sujets créés, etc.</p>
        <div class="options">
            <a href="view_stats.php">Voir les statistiques</a>
        </div>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
