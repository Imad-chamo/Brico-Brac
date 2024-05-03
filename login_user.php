<!DOCTYPE html>
<html>
<head>
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="src/css/login_user.css">
</head>
<body>

<h2>Connexion Utilisateur</h2>

<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include('src/bdd.php');

    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Requête SQL pour vérifier les informations d'identification de l'utilisateur en utilisant PDO
    $query = "SELECT ID, idRole FROM utilisateur WHERE Email = :email AND Mot_de_passe = :mot_de_passe";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe);
    $stmt->execute();

    if ($stmt) {
        if ($stmt->rowCount() == 1) {
            // L'utilisateur est connecté avec succès
            session_start();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['ID']; // Stocker l'ID de l'utilisateur dans la session
            $_SESSION['user_role'] = $user['idRole']; // Stocker l'ID du rôle de l'utilisateur dans la session
            header("Location: product_managment.php"); // Rediriger vers la page de tableau de bord
        } else {
            echo "Identifiants invalides. Veuillez réessayer.";
        }
    } else {
        echo "Une erreur s'est produite lors de la vérification de l'identité de l'utilisateur.";
    }

    // Fermer la connexion à la base de données
    $db = null;
}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="email">Email :</label>
    <input type="text" name="email" id="email" required><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required><br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>
