<?php
include 'src/bdd.php';

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


if (isset($_GET['article_id'])) {
    $article_id = $_GET['article_id'];

    if (isset($_POST['modify_article'])) {
        $new_name = $_POST['new_name'];
        $new_reference = $_POST['new_reference'];
        $new_price_ht = $_POST['new_price_ht'];
        $new_tva = $_POST['new_tva'];
        $new_percentage = $_POST['new_percentage'];
        $new_nouveaute = isset($_POST['new_nouveaute']) ? 1 : 0; // Vérifie si la case "Nouveauté" est cochée

        // Effectuez la mise à jour de l'article dans la base de données
        $update_query = "UPDATE article SET 
            Nom = :name, 
            Référence = :reference, 
            Prix_HT = :price_ht, 
            TVA = :tva, 
            Pourcentage = :percentage, 
            Nouveauté = :nouveaute 
            WHERE id = :id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':name', $new_name, PDO::PARAM_STR);
        $update_stmt->bindParam(':reference', $new_reference, PDO::PARAM_STR);
        $update_stmt->bindParam(':price_ht', $new_price_ht, PDO::PARAM_STR);
        $update_stmt->bindParam(':tva', $new_tva, PDO::PARAM_STR);
        $update_stmt->bindParam(':percentage', $new_percentage, PDO::PARAM_STR);
        $update_stmt->bindParam(':nouveaute', $new_nouveaute, PDO::PARAM_INT);
        $update_stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
        $update_stmt->execute();

        // Redirigez l'utilisateur vers la page de liste des produits après la mise à jour
        header('Location: product_managment.php');
        exit();
    }

    $query = "SELECT * FROM article WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->execute();
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Article</title>
    <link rel="stylesheet" href="src/css/modify_article.css">
</head>
<body>
    <div class="container">
    <h1>Modifier un Article</h1>

    <form method="post">
        <label for="new_name">Nom de l'article:</label>
        <input type="text" name="new_name" id="new_name" value="<?php echo $article['Nom']; ?>"><br>

        <label for="new_reference">Référence:</label>
        <input type="text" name="new_reference" id="new_reference" value="<?php echo $article['Référence']; ?>"><br>

        <label for="new_price_ht">Prix HT:</label>
        <input type="text" name="new_price_ht" id="new_price_ht" value="<?php echo $article['Prix_HT']; ?>"><br>

        <label for="new_tva">TVA (%):</label>
        <input type="text" name="new_tva" id="new_tva" value="<?php echo $article['TVA']; ?>"><br>

        <label for="new_percentage">Pourcentage de Promotion:</label>
        <input type="text" name="new_percentage" id="new_percentage" value="<?php echo $article['Pourcentage']; ?>"><br>

        <label for="new_nouveaute">Nouveauté:</label>
        <input type="checkbox" name="new_nouveaute" id="new_nouveaute" <?php echo $article['Nouveauté'] ? 'checked' : ''; ?>><br>

        <button type="submit" name="modify_article">Modifier l'Article</button>
    </form>
    </div>
</body>
</html>
