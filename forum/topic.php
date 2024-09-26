<?php
session_start();
if (!isset($_SESSION['email'])) {
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

$topic_id = $_GET['id'];

// Récupérer les informations du sujet
$sql = "SELECT topics.*, utilisateurs.prenom FROM topics JOIN utilisateurs ON topics.user_id = utilisateurs.id WHERE topics.id = '$topic_id'";
$topic_result = $conn->query($sql);
$topic = $topic_result->fetch_assoc();

// Récupérer les réponses pour ce sujet
$sql = "SELECT posts.*, utilisateurs.prenom FROM posts JOIN utilisateurs ON posts.user_id = utilisateurs.id WHERE posts.topic_id = '$topic_id' ORDER BY posts.created_at ASC";
$posts_result = $conn->query($sql);

// Supprimer un post
if (isset($_GET['delete_post'])) {
    $post_id = $_GET['delete_post'];
    
    // Vérifier si l'utilisateur est propriétaire du post
    $user_id = $_SESSION['id'];
    $delete_sql = "DELETE FROM posts WHERE id = '$post_id' AND user_id = '$user_id'";
    
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: topic.php?id=$topic_id"); // Redirige après suppression pour rafraîchir la page
        exit();
    } else {
        echo "Erreur lors de la suppression : " . $conn->error;
    }
}

// Ajouter une nouvelle réponse
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    $content = $conn->real_escape_string($_POST['content']);
    $user_id = $_SESSION['id'];

    $sql = "INSERT INTO posts (topic_id, user_id, content) VALUES ('$topic_id', '$user_id', '$content')";
    if ($conn->query($sql) === TRUE) {
        header("Location: topic.php?id=$topic_id"); // Redirige après ajout de la réponse
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($topic['title']); ?> - Forum</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="forum.css">
</head>
<body>

    <div class="back-arrow" onclick="goBack()">←</div>

    <div class="forum-container">

    <div class="new-post">
        <h1><?php echo htmlspecialchars($topic['title']); ?></h1>
        <p>Créé par : <?php echo htmlspecialchars($topic['prenom']); ?> le <?php echo $topic['created_at']; ?></p>
        <p><?php echo htmlspecialchars($topic['description']); ?></p>
    </div>

    <!-- Afficher les réponses -->
    <div class="posts">
        <h2>Réponses</h2>
        <?php
        if ($posts_result->num_rows > 0) {
            while($post = $posts_result->fetch_assoc()) {
                echo "<div class='post'>
                        <p><strong>" . htmlspecialchars($post['prenom']) . " :</strong> " . htmlspecialchars($post['content']) . "</p>
                        <p>" . $post['created_at'] . "</p>";

                // Afficher le bouton de suppression si l'utilisateur est le propriétaire du post
                if ($_SESSION['id'] == $post['user_id']) {
                    echo "<a href='javascript:void(0);' class='delete-button' onclick='deletePost(" . $post['id'] . ");'>Supprimer</a>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>Aucune réponse pour ce sujet.</p>";
        }
        ?>
    </div>

    <!-- Formulaire pour ajouter une nouvelle réponse -->
    <div class="new-post">
        <h2 style="color: #333;">Ajouter une réponse</h2>
        <form action="topic.php?id=<?php echo $topic_id; ?>" method="POST">
            <textarea name="content" placeholder="Votre réponse..." required></textarea><br>
            <button type="submit">Poster la réponse</button>
        </form>
    </div>
    </div>

</body>
<script>
    function goBack() {
        window.location.href = '../forum/forum.php'; 
    }

    // Fonction JavaScript pour supprimer le post via un rafraîchissement direct
    function deletePost(postId) {
        if (confirm("Voulez-vous vraiment supprimer ce post ?")) {
            window.location.href = "topic.php?id=<?php echo $topic_id; ?>&delete_post=" + postId;
        }
    }
</script>
</html>
