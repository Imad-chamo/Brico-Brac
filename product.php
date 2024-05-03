<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
include 'src/bdd.php';

if (isset($_GET['reference'])) {
    $reference = $_GET['reference'];

    // Execute an SQL query to retrieve product details based on the reference.
    $query = "SELECT * FROM article WHERE Référence = :reference";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':reference', $reference);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $ttc = calculateTTC($row['Prix_HT'], $row['TVA']);
        include 'src/menu.php';

        if ($row['Pourcentage'] > 0) {
            $discountedPriceHt = calculateDiscountedPriceHt($row['Prix_HT'], $row['Pourcentage']);
            $discountedPrice = calculateTTC($discountedPriceHt, $row['TVA']);
        }
?>
        <div class="container mt-4">
            <div class="product">
                <?php if ($row['Nouveauté'] == 1) : ?>
                    <span class="badge badge-success">Nouveauté</span><br>
                <?php endif; ?>
                <?php if ($row['Pourcentage'] > 0) : ?>
                    <span class="badge badge-danger">En Promotion</span> (-<?php echo $row['Pourcentage']; ?>%)<br>
                <?php endif; ?>
                <h3><?php echo $row['Nom']; ?></h3>
                <p>Référence: <?php echo $row['Référence']; ?></p>
                <?php if ($row['Pourcentage'] > 0) : ?>
                    <p>Prix HT: <?php echo $discountedPriceHt; ?> €</p>
                    <p>TVA: <?php echo $row['TVA']; ?> %</p>
                    <p>Prix Réduit TTC: <?php echo $discountedPrice; ?> € au lieu de <?php echo $ttc; ?> €</p>
                <?php else : ?>
                    <p>Prix HT: <?php echo $row['Prix_HT']; ?> €</p>
                    <p>TVA: <?php echo $row['TVA']; ?> %</p>
                    <p>Prix TTC: <?php echo $ttc; ?> €</p>
                <?php endif; ?>
            </div>

            <!-- Add a form for selecting quantity and adding to the cart -->
            <form method="post" action="src/ajouter_panier.php">
                <input type="hidden" name="reference" value="<?php echo $row['Référence']; ?>">
                <div class="form-group">
                    <label for "quantite">Quantité :</label>
                    <input type="number" class="form-control" name="quantite" min="1">
                </div>
                <button type="submit" class="btn btn-primary">Ajouter au panier</button>
            </form>
        </div>
    <?php } else {
        echo 'Produit non trouvé.';
    }
}

function calculateTTC($ht, $tva) {
    $ttc = $ht * (1 + ($tva / 100));
    return number_format($ttc, 2, '.', '');
}

function calculateDiscountedPriceHt($originalPriceHt, $discountPercentage) {
    // Calcule le prix HT réduit en fonction du pourcentage de réduction
    $discountedPriceHt = $originalPriceHt - ($originalPriceHt * $discountPercentage / 100);
    return number_format($discountedPriceHt, 2, '.', '');
}
?>
</body>
</html>
