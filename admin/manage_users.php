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

// Suppression de l'utilisateur
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_sql = "DELETE FROM utilisateurs WHERE id='$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Utilisateur supprimé avec succès.');</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression de l\'utilisateur.');</script>";
    }
}

// Récupération de tous les utilisateurs
$sql = "SELECT * FROM utilisateurs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <style>
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

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

    </style>
    <link rel="stylesheet" href="../forum/forum.css">
</head>
<body>
<div class="back-arrow" onclick="goBack()">←</div>

<div class="container">
    <h1>Gestion des Utilisateurs</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Statut</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nom']}</td>
                    <td>{$row['prenom']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['role']}</td>
                    <td>
                        <a href='edit_user.php?id={$row['id']}' class='btn'>Modifier</a>
                        <a href='?delete_id={$row['id']}' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet utilisateur ?\");' class='btn'>Supprimer</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Aucun utilisateur trouvé.</td></tr>";
        }
        ?>
    </table>

</div>
</body>
<script>
    function goBack() {
        window.location.href = '../admin/admin_dashboard.php'; 
    }
</script>
</html>

<?php
$conn->close();
?>
