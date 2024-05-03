<?php
include 'src/bdd.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['loggedin'])) {
    if ($_SESSION['user_role'] == 3) {

    } else {
        // Rediriger l'utilisateur ou afficher un message d'erreur, car l'utilisateur n'a pas les droits nécessaires.
        echo "acces refusé";
    }
} else {
    // Rediriger l'utilisateur vers la page de connexion, car il n'est pas connecté.
    header('Location: login_user.php'); // Redirigez vers la page de connexion
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un Utilisateur</title>
</head>
<body>
    <h1>Ajouter un Utilisateur</h1>
    <form method="post" action="src/process_add_users.php">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
        
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required>
        
        <label for="email">Email :</label>
        <input type="text" name="email" id="email" required>
        
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="text" name="mot_de_passe" id="mot_de_passe" required>
        
        <label for="id_role">Role :</label>
        <select name="id_role" id="id_role">
            <option value="1">Client</option>
            <option value="2">Gestionnaire de commande</option>
            <option value="3">Administrateur</option>
        </select>
                
        <input type="submit" name="submit" value="Ajouter l'Utilisateur">
    </form>
</body>
</html>