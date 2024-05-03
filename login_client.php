<?php
include('src/bdd.php');

if (isset($_POST['login'])) {
    // Traitement de la soumission du formulaire de connexion
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Effectuer une requête SQL pour vérifier les informations de connexion
    $query = $db->prepare("SELECT * FROM clients WHERE email = ?");
    $query->execute([$email]);

    if ($query->rowCount() == 1) {
        $row = $query->fetch();
        if (password_verify($password, $row['password'])) {
            // L'utilisateur est authentifié
            session_start();
            $_SESSION['user_id'] = $row['id'];
            // Vous pouvez rediriger l'utilisateur vers une page de bienvenue
            header('Location: index.php');
        } else {
            // Mot de passe incorrect
            echo "Mot de passe incorrect.";
        }
    } else {
        // Utilisateur non trouvé
        echo "Utilisateur non trouvé.";
    }
} elseif (isset($_POST['register'])) {
    // Traitement de la soumission du formulaire d'inscription
    $newPassword = $_POST['newPassword'];
    $newFirstName = $_POST['newFirstName'];
    $newLastName = $_POST['newLastName'];
    $newEmail = $_POST['newEmail'];

    // Hacher le mot de passe
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Effectuer une requête SQL pour ajouter un nouvel utilisateur avec le mot de passe haché, nom et prénom
    $insertQuery = $db->prepare("INSERT INTO clients (password, first_name, last_name, email) VALUES (?, ?, ?, ?)");
    $insertQuery->execute([$hashedPassword, $newFirstName, $newLastName, $newEmail]);

    // Vous pouvez rediriger l'utilisateur vers une page de connexion après l'inscription
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion et Inscription</title>
    <link rel="stylesheet" type="text/css" href="src/css/login_client.css">
</head>
<body>
    <?php include 'src/menu.php'; ?>
    <div class="container">
        <div class="form-container">
            <h2>Connexion</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Adresse e-mail" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit" name="login">Se connecter</button>
            </form>
        </div>
        <div class="form-container">
            <h2>Inscription</h2>
            <form method="post">
                <input type="text" name="newFirstName" placeholder="Prénom" required>
                <input type="text" name "newLastName" placeholder="Nom" required>
                <input type="email" name="newEmail" placeholder="Adresse e-mail" required>
                <input type="password" name="newPassword" placeholder="Mot de passe" required>
                <button type="submit" name="register">S'inscrire</button>
            </form>
        </div>
    </div>
</body>
</html>

