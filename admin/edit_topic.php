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

// Récupérer les informations du sujet
if (isset($_GET['id'])) {
    $topic_id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM topics WHERE id='$topic_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $topic = $result->fetch_assoc();
    } else {
        echo "Sujet introuvable.";
        exit();
    }
}

// Mettre à jour le sujet
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $theme = $conn->real_escape_string($_POST['theme']);

    $update_sql = "UPDATE topics SET title='$title', description='$description', theme='$theme' WHERE id='$topic_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Sujet mis à jour avec succès.'); window.location.href='manage_topics.php';</script>";
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
    <title>Modifier Sujet</title>
    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 900px;
        }

        h1 {
            text-align: center;
            color: #d9524a;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label, input, textarea, select {
            margin: 10px 0;
            padding: 10px;
        }

        textarea {
            height: 200px;
            resize: vertical;
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
    <link rel="stylesheet" href="../forum/forum.css">
</head>
<body>
<div class="back-arrow" onclick="goBack()">←</div>
<div class="container">
    <h1>Modifier Sujet</h1>
    <form method="post">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($topic['title']); ?>" required>
        
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="5" required><?php echo htmlspecialchars($topic['description']); ?></textarea>
        
        <label for="theme">Thème</label>
        <select name="theme" id="theme">
            <option value="Général" <?php echo ($topic['theme'] == 'Général') ? 'selected' : ''; ?>>Général</option>
            <option value="Technologie" <?php echo ($topic['theme'] == 'Technologie') ? 'selected' : ''; ?>>Technologie</option>
            <option value="Études" <?php echo ($topic['theme'] == 'Études') ? 'selected' : ''; ?>>Études</option>
            <option value="Campus" <?php echo ($topic['theme'] == 'Campus') ? 'selected' : ''; ?>>Campus</option>
        </select>

        <button type="submit" class="btn">Mettre à jour</button>
    </form>
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
