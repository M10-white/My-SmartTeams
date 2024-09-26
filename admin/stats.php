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

// Statistiques
// Nombre total d'utilisateurs
$total_users_sql = "SELECT COUNT(*) AS total_users FROM utilisateurs";
$total_users_result = $conn->query($total_users_sql);
$total_users = ($total_users_result->num_rows > 0) ? $total_users_result->fetch_assoc()['total_users'] : 0;

// Répartition des utilisateurs (anciens / nouveaux étudiants)
$old_students_sql = "SELECT COUNT(*) AS old_students FROM utilisateurs WHERE status='ancien étudiant'";
$new_students_sql = "SELECT COUNT(*) AS new_students FROM utilisateurs WHERE status='nouvel étudiant'";
$old_students_result = $conn->query($old_students_sql);
$new_students_result = $conn->query($new_students_sql);
$old_students = ($old_students_result->num_rows > 0) ? $old_students_result->fetch_assoc()['old_students'] : 0;
$new_students = ($new_students_result->num_rows > 0) ? $new_students_result->fetch_assoc()['new_students'] : 0;

// Nombre total de sujets
$total_topics_sql = "SELECT COUNT(*) AS total_topics FROM topics";
$total_topics_result = $conn->query($total_topics_sql);
$total_topics = ($total_topics_result->num_rows > 0) ? $total_topics_result->fetch_assoc()['total_topics'] : 0;

// Nombre total de messages
$total_posts_sql = "SELECT COUNT(*) AS total_posts FROM posts";
$total_posts_result = $conn->query($total_posts_sql);
$total_posts = ($total_posts_result->num_rows > 0) ? $total_posts_result->fetch_assoc()['total_posts'] : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Administrateur</title>
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

        .stats {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }

        .stat {
            background-color: #d9524a;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin: 10px;
            flex: 1;
            text-align: center;
            min-width: 200px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .stat h2 {
            margin: 0 0 10px 0;
        }

        .stat p {
            font-size: 18px;
        }
    </style>
    <link rel="stylesheet" href="../forum/forum.css">
</head>
<body>
    <div class="back-arrow" onclick="goBack()">←</div>
    
    <div class="container">
        <h1>Statistiques Administrateur</h1>
        <div class="stats">
            <div class="stat">
                <h2>Utilisateurs</h2>
                <p>Total : <?php echo $total_users; ?></p>
                <p>Anciens : <?php echo $old_students; ?></p>
                <p>Nouveaux : <?php echo $new_students; ?></p>
            </div>
            <div class="stat">
                <h2>Sujets</h2>
                <p>Total : <?php echo $total_topics; ?></p>
            </div>
            <div class="stat">
                <h2>Reponse à un post</h2>
                <p>Total : <?php echo $total_posts; ?></p>
            </div>
        </div>
    </div>
</body>
<script>
    function goBack() {
        window.location.href = '../admin/admin_dashboard.php'; 
    }
</script>
</html>
