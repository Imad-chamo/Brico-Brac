<?php
include 'src/bdd.php';

session_start();

// Vérifier si l'utilisateur est connecté
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

if (isset($_POST['delete_article'])) {
    $article_id = $_POST['article_id'];
    $delete_query = "DELETE FROM article WHERE id = :id";
    $stmt = $db->prepare($delete_query);
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->execute(); // Exécutez la requête préparée
    // Ajoutez un message de réussite ou redirigez vers une page différente après la suppression
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <link rel="stylesheet" href="src/css/product_managment.css">
</head>
<body>
<?php include 'src/menu_admin.php'; ?>
    <h1>Liste des Produits</h1>
    <a href="add_article.php" class="button">Ajouter un nouvel article</a>

    <table>
        <thead>
            <tr>
                <th>Nom de l'article</th>
                <th>Référence</th>
                <th>Prix HT</th>
                <th>TVA</th>
                <th>Prix TTC</th>
                <th>Promotion</th>
                <th>Prix Réduit</th>
                <th>Nouveauté</th>
                <th>Actions</th>
            </tr>

        <?php
        $query = "SELECT * FROM article";
        $result = $db->query($query);

        foreach ($result as $row) {
            $ttc = calculateTTC($row['Prix_HT'], $row['TVA']);

            echo '<tr>'; 
            echo '<td>' . $row['Nom'] . '</td>';
            echo '<td>' . $row['Référence'] . '</td>';
            echo '<td>' . $row['Prix_HT'] . ' €</td>';
            echo '<td>' . $row['TVA'] . '%</td>';
            echo '<td>' . $ttc . ' €</td>';

            if (is_numeric($row['Pourcentage']) && $row['Pourcentage'] > 0) {
                $discountPercentage = floatval($row['Pourcentage']);
                $discountedPrice = $ttc * (1 - ($discountPercentage / 100));
                echo '<td>En Promotion</td>';
                echo '<td>' . $discountedPrice . ' €</td>';
            } else {
                echo '<td></td>';
                echo '<td></td>';
            }
    

            if ($row['Nouveauté'] == 1) {
                echo '<td>Nouveauté</td>';
            } else {
                echo '<td></td>';
            }

            // Afficher les boutons pour supprimer et modifier
            echo '<td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="article_id" value="' . $row['id'] . '">';
            echo '<button type="submit" name="delete_article">Supprimer</button>';
            echo '</form>';
            // Bouton de modification
            echo '<form method="get" action="modify_article.php">';
            echo '<input type="hidden" name="article_id" value="' . $row['id'] . '">';
            echo '<button type="submit" name="modify_article">Modifier</button>';
            echo '</form>';
            echo '</td>';
        }

        function calculateTTC($ht, $tva)
        {
            // Calculate the TTC price
            $ttc = $ht * (1 + ($tva / 100));
            return number_format($ttc, 2, '.', ''); // Format to 2 decimal places
        }
        ?>

    </table>

</body>

</html>