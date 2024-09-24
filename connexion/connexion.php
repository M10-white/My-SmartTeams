<?php
include ("connexion.html");
session_start(); // Démarre la session

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_smartteams";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Requête pour vérifier les informations de l'utilisateur
    $query = "SELECT * FROM utilisateurs WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Vérifier si le mot de passe correspond
        if (password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie, enregistrer les informations dans la session
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['date_inscription'] = $user['date_inscription'];

            // Redirection vers la page de profil
            header("Location: ../compteProfil/compteProfil.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }
}

$conn->close();
?>
