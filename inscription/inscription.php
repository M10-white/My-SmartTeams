<?php include('inscription.html'); ?>
<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = ""; // Remplace par ton mot de passe MySQL si nécessaire
$dbname = "my_smartteams";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);
    $status = $conn->real_escape_string($_POST['status']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Vérification si les mots de passe correspondent
    if ($password != $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Hachage du mot de passe pour plus de sécurité
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Préparation de la requête SQL pour insérer les données
    $sql = "INSERT INTO utilisateurs (prenom, nom, email, status, mot_de_passe)
            VALUES ('$prenom', '$nom', '$email', '$status', '$hashed_password')";

    // Exécution de la requête
    if ($conn->query($sql) === TRUE) {
        // Affichage du pop-up et redirection après 3 secondes
        echo "<script>
            document.body.innerHTML += '<div class=\"popup success\">Inscription réussie !</div>';
            setTimeout(function() {
                window.location.href = 'connexion.php';
            }, 3000); // Redirection après 3 secondes
        </script>";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Fermeture de la connexion
$conn->close();
?>
