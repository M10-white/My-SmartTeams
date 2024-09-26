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

// Récupérer les informations de l'utilisateur à partir de l'ID
if (isset($_GET['id'])) {
    $user_id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM utilisateurs WHERE id='$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Utilisateur introuvable.";
        exit();
    }
}

// Mettre à jour l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $email = $conn->real_escape_string($_POST['email']);
    $status = $conn->real_escape_string($_POST['status']);
    $role = $conn->real_escape_string($_POST['role']);

    $update_sql = "UPDATE utilisateurs SET nom='$nom', prenom='$prenom', email='$email', status='$status', role='$role' WHERE id='$user_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Utilisateur mis à jour avec succès.'); window.location.href='manage_users.php';</script>";
    } else {
        echo "Erreur lors de la mise à jour : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <style>
        .container {
            max-width: 600px;
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

        form {
            display: flex;
            flex-direction: column;
        }

        label, input, select {
            margin: 10px 0;
            padding: 10px;
        }

        .btn {
            padding: 10px;
            background-color: #d9524a;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #b8453c;
        }
    </style>
    <link rel="stylesheet" href="../accueil/stylesIndex.css">
</head>
<body>
<div class="container">
    <h1>Modifier Utilisateur</h1>
    <form method="post">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?php echo $user['nom']; ?>" required>
        
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?php echo $user['prenom']; ?>" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
        
        <label for="status">Statut</label>
        <select name="status" id="status">
            <option value="ancien etudiant" <?php echo ($user['status'] == 'ancien etudiant') ? 'selected' : ''; ?>>Ancien</option>
            <option value="nouvel etudiant" <?php echo ($user['status'] == 'nouvel etudiant') ? 'selected' : ''; ?>>Nouveau</option>
        </select>

        <label for="role">Rôle</label>
        <select name="role" id="role">
            <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>Utilisateur</option>
            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Administrateur</option>
        </select>

        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div>
</body>
</html>

<?php
$conn->close();
?>
