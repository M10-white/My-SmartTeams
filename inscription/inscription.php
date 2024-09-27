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
    $formation = $conn->real_escape_string($_POST['formation']);
    $ville = $conn->real_escape_string($_POST['ville']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Définition de l'expression régulière pour l'email
    $email_pattern = "/^[\w\.-]+@[a-zA-Z\d\.-]+\.[a-zA-Z]{2,}$/";

    // Vérification si l'adresse e-mail est valide
    if (!preg_match($email_pattern, $email)) {
        echo "<script>
            var popup = document.createElement('div');
            popup.className = 'popup error';
            popup.textContent = 'Adresse e-mail invalide. Veuillez entrer une adresse e-mail valide.';
            document.body.appendChild(popup);
            setTimeout(function() {
                popup.style.animation = 'popupHide 0.5s forwards'; 
                setTimeout(function() {
                    popup.remove(); 
                }, 500);
            }, 3000);
        </script>";
        exit();
    }

    // Vérification si les mots de passe correspondent
    if ($password != $confirm_password) {
        echo "<script>
        var popup = document.createElement('div');
        popup.className = 'popup error';
        popup.textContent = 'Les mots de passe ne correspondent pas.';
        document.body.appendChild(popup);
        setTimeout(function() {
            popup.style.animation = 'popupHide 0.5s forwards'; 
            setTimeout(function() {
                popup.remove(); 
            }, 500);
        }, 3000);
    </script>";
        exit();
    }

    // Vérification des critères du mot de passe
    $password_pattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{7,}$/";
    if (!preg_match($password_pattern, $password)) {
        echo "<script>
            var popup = document.createElement('div');
            popup.className = 'popup error';
            popup.textContent = 'Le mot de passe doit comporter au moins 7 caractères, incluant des lettres, des chiffres et un symbole.';
            document.body.appendChild(popup);
            setTimeout(function() {
                popup.style.animation = 'popupHide 0.5s forwards'; 
                setTimeout(function() {
                    popup.remove(); 
                }, 500);
            }, 3000);
        </script>";
        exit();
    }

    // Vérifier si l'adresse email existe déjà dans la base de données
    $email_check_query = "SELECT * FROM utilisateurs WHERE email='$email' LIMIT 1";
    $result = $conn->query($email_check_query);

    if ($result->num_rows > 0) {
        echo "<script>
            var popup = document.createElement('div');
            popup.className = 'popup error';
            popup.innerHTML = 'Cette adresse email est déjà inscrite. Voulez-vous vous <a href=\"../connexion/connexion.php\">connecter</a> ?';
            document.body.appendChild(popup);
            setTimeout(function() {
                popup.style.animation = 'popupHide 0.5s forwards'; 
                setTimeout(function() {
                    popup.remove(); 
                }, 500);
            }, 3000);
        </script>";
        exit();
    }

    // Hachage du mot de passe pour plus de sécurité
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Préparation de la requête SQL pour insérer les données
    $sql = "INSERT INTO utilisateurs (prenom, nom, email, status, formation, ville, mot_de_passe) VALUES ('$prenom', '$nom', '$email', '$status', '$formation', '$ville', '$hashed_password')";

    // Exécution de la requête
    if ($conn->query($sql) === TRUE) {
        // Affichage du pop-up et redirection après 3 secondes
        echo "<script>
            var popup = document.createElement('div');
            popup.className = 'popup success';
            popup.textContent = 'Inscription réussie !';
            document.body.appendChild(popup);
            setTimeout(function() {
                window.location.href = '../connexion/connexion.php';
            }, 1000); 
        </script>";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Fermeture de la connexion
$conn->close();
?>
