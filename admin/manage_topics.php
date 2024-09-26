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

// Suppression du sujet
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM topics WHERE id='$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Sujet supprimé avec succès.');</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression du sujet.');</script>";
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
    <title>Gestion des Sujets</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #d9524a;
            color: #ffffff;
        }

        .btn {
            padding: 8px 12px;
            background-color: #d9524a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
        }

        .btn:hover {
            background-color: #b8453c;
        }
    </style>
    <link rel="stylesheet" href="../forum/forum.css">
</head>
<body>
<div class="container">
    <h1>Gestion des Sujets</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Auteur</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['prenom']}</td>
                    <td>
                        <a href='edit_topic.php?id={$row['id']}' class='btn'>Modifier</a>
                        <a href='?delete_id={$row['id']}' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce sujet ?\");' class='btn'>Supprimer</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Aucun sujet trouvé.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>
