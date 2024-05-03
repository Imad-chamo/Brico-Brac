<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/confirmation.css"> <!-- Assurez-vous d'ajouter le lien vers votre fichier CSS -->
    <title>Confirmation de Commande</title>
</head>

<body>
<?php include 'src/menu.php'; ?>
<div class="contenair">
    <div class="confirmation-container">
        <h2>Confirmation de Commande</h2>
        <p>Merci pour votre commande. Voici les détails :</p>

        <?php
        // Incluez votre fichier de configuration de la base de données
        include 'src/bdd.php';

        // Récupérez les données de la commande depuis la base de données
        $query = "SELECT * FROM commande ORDER BY ID DESC LIMIT 1";
        $result = $db->query($query);

        if ($result && $result->rowCount() > 0) {
            $row = $result->fetch();
            ?>

            <div class="order-details">
                <h3>Numéro de Commande : #<?php echo $row['ID']; ?></h3>
                <p>Date de Commande : <?php echo $row['Date_de_commande']; ?></p>
                <p>Montant Total : <?php echo $row['Montant_total']; ?> €</p>
            </div>

            <h3>Adresse de Livraison :</h3>
            <p><?php echo $row['adresse_livraison']; ?></p>
            <p>Code Postal : <?php echo $row['code_postal_livraison']; ?></p>
            <p>Ville : <?php echo $row['ville_livraison']; ?></p>
            <p>Numéro de Téléphone : <?php echo $row['telephone_livraison']; ?></p>
            <p>Commentaire de Livraison : <?php echo $row['commentaire_livraison']; ?></p>

            <h3>Informations de Paiement :</h3>
            <p>Numéro de Carte : **** **** **** <?php echo substr($row['numero_carte'], -4); ?></p>
            <p>Date d'Expiration : <?php echo $row['date_expiration']; ?></p>
        <?php
        } else {
            echo "<p>La commande n'existe pas.</p>";
        }
        ?>
        <p>Merci de votre commande !</p>
    </div>
    </div>
</body>
</html>
