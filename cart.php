<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/cart.css">
    <title>Mon Panier</title>
</head>
<body>
<?php include 'src/menu.php'; ?>
    <main>
        <div class="titre"><H2>Votre panier</H2></div>
        <div class="cart-content">
            <?php
            session_start();
            if (empty($_SESSION['user_id'])) {
                // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
                header("Location: login_client.php");
                exit(); // Assurez-vous de quitter le script après la redirection
            }
            include 'src/bdd.php';

            echo '<form method="post" action="cart.php">';
            echo '<table class="cart-table">';
            echo '<tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Prix total</th><th>Supprimer article</th></tr>';

            if (empty($_SESSION['user_id'])) {
                echo '<tr><td colspan="4">Votre panier est vide.</td></tr>';
            } else {
                $client_id = $_SESSION['user_id'];

                $query = "SELECT article.ID, article.Nom, panier.quantity, article.Prix_HT, article.TVA, article.Pourcentage
                FROM panier
                INNER JOIN article ON panier.product_id = article.ID
                WHERE panier.client_id = $client_id";

                $result = $db->query($query);
                
                $total_ttc = 0;
                
                if ($result && $result->rowCount() > 0) {
                    while ($row = $result->fetch()) {
                        $prix_unitaire = calculateTTC($row['Prix_HT'] * (1 - ($row['Pourcentage'] / 100)), $row['TVA']);
                        $prix_total = $prix_unitaire * $row['quantity'];
                        $total_ttc += $prix_total; // Ajoute au Total TTC

                        echo '<tr>';
                        echo '<td>' . $row['Nom'] . '</td>';
                        echo '<td>'. $row['quantity'] . '</td>';
                        echo '<td>' . $prix_unitaire . ' €</td>';
                        echo '<td>' . $prix_total . ' €</td>';
                        echo '<td><form method="post" action="cart.php"><input type="hidden" name="remove_item" value="' . $row['ID'] . '"><input type="submit" value="Supprimer"></form></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">Votre panier est vide.</td></tr>';
                }
            }

            echo '</table>';
            echo '</form>';

            //suppresion d'un article 
            if (isset($_POST['remove_item'])) {
                $product_id_to_remove = $_POST['remove_item'];
                // Supprimez l'article du panier en fonction de son ID
                $query = "DELETE FROM panier WHERE client_id = $client_id AND product_id = $product_id_to_remove";
                $db->query($query);
                // Redirigez l'utilisateur vers la page du panier après la suppression
                header("Location: cart.php");
                exit();
            }

            

            if (!empty($_SESSION['user_id'])) {
                echo '<form method="post" action="order.php">';
                echo '<input type="submit" name="commander" value="Passer la commande" class="btn">';
                echo '</form>';
            }
            // Fonction de calcul du prix TTC (comme dans votre code précédent)
            function calculateTTC($ht, $tva) {
                $ttc = $ht * (1 + ($tva / 100));
                return number_format($ttc, 2, '.', '');
            }

            ?>
        </div>
    </main>
</body>
</html>
