<?php
include 'src/bdd.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <title>page d'accueil</title>
    <link rel="stylesheet" href="src/css/index.css">
</head>
<body>
<?php include 'src/menu.php'; ?>

<div class="jumbotron text-center bg-dark text-white">
    <h1 class="display-4">Brico-brac</h1>
    <p class="lead">Bienvenue sur Brico'brac ! La référence du magasin de bricolage près de chez vous !</p>
</div>

<div class="articles">
<?php
$query = "SELECT * FROM article";
$result = $db->query($query);

echo '<div class="article-container">'; // Start a container for your articles

foreach ($result as $row) {
    if ($row['Pourcentage'] > 0 || $row['Nouveauté'] == 1) {
        $ttc = calculateTTC($row['Prix_HT'], $row['TVA']);
        $discountedPrice = $ttc; // Initialize with regular price

        if ($row['Pourcentage'] > 0) {
            $discountPercentage = $row['Pourcentage'];
            $discountedPrice = $ttc * (1 - ($discountPercentage / 100));
        }

        // Set a fixed height for the card body and control the width with CSS
        echo '<div class="card" style="height: 300px; width: 360px;">'; // Adjust the height and width as needed

        if ($row['Nouveauté'] == 1) {
            echo '<div class="card-header bg-success text-white">Nouveauté</div>';
        } elseif ($row['Pourcentage'] > 0) {
            echo '<div class="card-header bg-danger text-white">En Promotion (-' . $row['Pourcentage'] . '%)</div>';
        }

        // Add the card body
        echo '<div class="card-body">';
        echo "<h5 class='card-title'>" . $row['Nom'] . "</h5>";
        echo "<p class='card-text'>Prix TTC: " . $discountedPrice . " €</p>";
        echo '</div>';

        // Add details and add to cart buttons
        echo '<div class="card-footer">';
        echo '<a href="product.php?reference=' . $row['Référence'] . '" class="btn btn-dark btn-sm bg-dark text-white mb-2 mr-2">Voir les détails</a>';

        // Add the form for selecting quantity and adding to the cart
        echo '<form method="post" action="src/ajouter_panier.php" class="d-inline">';
        echo '<input type="hidden" name="reference" value="' . $row['Référence'] . '">';
        echo 'Quantité: <input type="number" name="quantite" min="1" class="form-control mb-2">';
        echo '<input type="submit" value="Ajouter au panier" class="btn btn-dark btn-sm bg-dark text-white mb-2">';
        echo '</form>';
        echo '</div>';

        echo '</div>'; // Close the card
    }
}

echo '</div>'; // Close the container

function calculateTTC($ht, $tva) {
    $ttc = $ht * (1 + ($tva / 100));
    return number_format($ttc, 2, '.', '');
}
?>


</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>