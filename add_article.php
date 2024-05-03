<?php
session_start();

if (isset($_SESSION['loggedin'])) {
    if ($_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3) {

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
    <title>Ajouter un Article</title>
    <link rel="stylesheet" href="src/css/add_article.css">
</head>
<body>
    <form method="post" action="src/process_add_article.php">
        <h1>Ajouter un Article</h1>
        <label for="nom">Nom de l'article:</label>
        <input type="text" name="nom" id="nom" required>
        
        <label for="reference">Référence:</label>
        <input type="text" name="reference" id="reference" required>
        
        <label for="prix_ht">Prix HT:</label>
        <input type="text" name="prix_ht" id="prix_ht" required>
        
        <label for="tva">TVA (%):</label>
        <input type="text" name="tva" id="tva" required>
        
        <label for="pourcentage">Pourcentage de Réduction (si applicable):</label>
        <input type="text" name="pourcentage" id="pourcentage">
        
        <label for="nouveaute">Nouveauté:</label>
        <input type="checkbox" name="nouveaute" id="nouveaute" value="1">
        
        <input type="submit" name="submit" value="Ajouter l'article">
    </form>
</body>
</html>
