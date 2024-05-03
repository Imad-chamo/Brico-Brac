<?php
include 'src/bdd.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Liste des Produits</title>
    <link rel="stylesheet" href="src/css/index.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php include 'src/menu.php'; ?>

<div class="jumbotron text-center bg-dark text-white">
    <h1 class="display-4">Brico-brac</h1>
    <p class="lead">Bienvenue sur Brico'brac ! La référence du magasin de bricolage près de chez vous !</p>
</div>

<?php
$query = "SELECT * FROM article";
$result = $db->query($query);

echo '<div class="product-container">'; // Start a container for your products

foreach ($result as $row) {
    $ttc = calculateTTC($row['Prix_HT'], $row['TVA']);
    
    // Set a fixed height for the product container
    echo '<div class="product" style="height: 320px;width: 360px;">'; // Adjust the height as needed

    if ($row['Nouveauté'] == 1) {
        echo '<span class="new">Nouveauté</span><br>';
    }

    echo '<h2>' . $row['Nom'] . '</h2>';

    echo '<p class="price">';
    if ($row['Pourcentage'] > 0) {
        $discountPercentage = $row['Pourcentage'];
        $discountedPrice = $ttc * (1 - ($discountPercentage / 100));
        echo '<span class="original-price">Prix TTC: ' . $ttc . ' €</span><br>';
        echo '<span class="discounted-price">Prix Réduit TTC: ' . $discountedPrice . ' €</span>';
    } else {
        echo '<span class="price-ttc">Prix TTC: ' . $ttc . ' €</span>';
    }
    echo '</p>';

    echo '<div class="card-footer">';
    echo '<a href="product.php?reference=' . $row['Référence'] . '" class="btn btn-dark btn-sm bg-dark text-white mb-2 mr-2">Voir les détails</a>';

    // Add the form for selecting quantity and adding to the cart
    echo '<form method="post" action="src/ajouter_panier.php" class="d-inline">';
    echo '<input type="hidden" name="reference" value="' . $row['Référence'] . '">';
    echo 'Quantité: <input type="number" name="quantite" min="1" class="form-control mb-2">';
    echo '<input type="submit" value="Ajouter au panier" class="btn btn-dark btn-sm bg-dark text-white mb-2">';
    echo '</form>';
    echo '</div>';
    echo '</div>'; // Close the product container
}

echo '</div>'; // Close the container

function calculateTTC($ht, $tva) {
    $ttc = $ht * (1 + ($tva / 100));
    return number_format($ttc, 2, '.', '');
}
?>


</body>
</html>