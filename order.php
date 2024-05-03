<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/order.css"> <!-- Assurez-vous d'ajouter le lien vers votre fichier CSS -->
    <title>Récapitulatif de la Commande</title>
</head>
<body>
    <?php include 'src/menu.php'; ?>
    <main>
        <h2>Récapitulatif de la Commande</h2>

        <?php
        session_start(); // Démarrez la session

        // Incluez votre fichier de configuration de la base de données
        include 'src/bdd.php';

        // Vous pouvez ajouter des éléments de mise en page ici (HTML, en-tête, etc.)

        // Assurez-vous que l'utilisateur est connecté
        if (empty($_SESSION['user_id'])) {
            header('Location: login.php'); // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté.
            exit;
        }

        // Calcul des totaux
        $total_ttc = 0;
        $tva = 0;
        $total_ht = 0;

        // Affichez le contenu du panier
        echo '<div class="table-container">';
        echo '<table>';
        echo '<tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Prix total</th></tr>';

        $client_id = $_SESSION['user_id'];

        $query = "SELECT article.ID, article.Nom, panier.quantity, article.Prix_HT, article.TVA, article.Pourcentage
                FROM panier
                INNER JOIN article ON panier.product_id = article.ID
                WHERE panier.client_id = $client_id";

        $result = $db->query($query);

        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch()) {
                $prix_unitaire = calculateTTC($row['Prix_HT'] * (1 - ($row['Pourcentage'] / 100)), $row['TVA']);
                $prix_total = $prix_unitaire * $row['quantity'];
                $total_ttc += $prix_total; // Ajoute au Total TTC

                echo '<tr>';
                echo '<td>' . $row['Nom'] . '</td>';
                echo '<td>' . $row['quantity'] . '</td>';
                echo '<td>' . $prix_unitaire . ' €</td>';
                echo '<td>' . $prix_total . ' €</td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</div>';

            // Calcul du montant de la TVA (en fonction du Total TTC)
            $tva = $total_ttc * (20 / 100); // Supposons que la TVA est de 20%

            // Calcul du Total HT (en soustrayant la TVA du Total TTC)
            $total_ht = $total_ttc - $tva;

            // Affichage des totaux
            echo '<div class="centered-elements">';
            echo '<p class="totals">Total HT : ' . number_format($total_ht, 2, '.', '') . ' €</p>';
            echo '<p class="totals">TVA : ' . number_format($tva, 2, '.', '') . ' €</p>';
            echo '<p class="totals">Total TTC : ' . number_format($total_ttc, 2, '.', '') . ' €</p>';
            echo '</div>';

            // Formulaire pour les informations de livraison
            echo '<h2>Informations de Livraison</h2>';
            echo '<form method="post" action="src/process_order.php">'; // Remplacez "process_order.php" par le script de traitement de la commande
            echo '<label for="adresse">Adresse de Livraison :</label>';
            echo '<textarea name="adresse" id="adresse" rows="4" cols="50" required></textarea><br>';

            echo '<label for="code_postal">Code Postal :</label>';
            echo '<input type="text" name="code_postal" id="code_postal" required><br>';

            echo '<label for="ville">Ville :</label>';
            echo '<input type="text" name="ville" id="ville" required><br>';

            echo '<label for="telephone">Numéro de Téléphone :</label>';
            echo '<input type="text" name="telephone" id="telephone" required><br>';

            echo '<label for="commentaire">Commentaire de Livraison :</label>';
            echo '<textarea name="commentaire" id="commentaire" rows="4" cols="50"></textarea><br>';

            // Champs pour les informations de paiement
            echo '<h2>Informations de Paiement</h2>';
            echo '<label for="numero_carte">Numéro de Carte :</label>';
            echo '<input type="text" name="numero_carte" id="numero_carte" required><br>';

            echo '<label for="date_expiration">Date d\'Expiration (MM/AAAA) :</label>';
            echo '<input type="text" name="date_expiration" id="date_expiration" required><br>';

            echo '<label for="cryptogramme">Cryptogramme (CVV) :</label>';
            echo '<input type="text" name="cryptogramme" id="cryptogramme" required><br>';

            echo '<input type="submit" name="valider_commande" value="Valider la Commande">';
            echo '</form>';
        } else {
            echo '<p>Votre panier est vide.</p>';
        }

        function calculateTTC($ht, $tva) {
            $ttc = $ht * (1 + ($tva / 100));
            return number_format($ttc, 2, '.', '');
        }
        ?>
    </main>
</body>
</html>
