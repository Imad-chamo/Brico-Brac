<?php
// Incluez le fichier de connexion à la base de données
include 'src/bdd.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['loggedin'])) {
    if ($_SESSION['user_role'] == 3) {

    } else {
        // Rediriger l'utilisateur ou afficher un message d'erreur, car l'utilisateur n'a pas les droits nécessaires.
        echo "acces refusé";
    }
} else {
    // Rediriger l'utilisateur vers la page de connexion, car il n'est pas connecté.
    header('Location: login_user.php'); // Redirigez vers la page de connexion
}

if (isset($_GET['utilisateur_id'])) {
    $utilisateur_id = $_GET['utilisateur_id'];

    // Récupérez les informations de l'article à modifier depuis la base de données
    $query = "SELECT * FROM utilisateur WHERE ID = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $utilisateur_id, PDO::PARAM_INT);
    $stmt->execute();
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        // Le formulaire de modification
        echo '<h2>Modifier l\'utilisateur</h2>';
        echo '<form method="post" action="src/update_users.php">';
        echo '<input type="hidden" name="utilisateur_id" value="' . $utilisateur_id . '">';
        echo 'Nom : <input type="text" name="nom" value="' . $utilisateur['Nom'] . '"><br>';
        echo 'Prenom : <input type="text" name="prenom" value="' . $utilisateur['Prénom'] . '"><br>';
        echo 'Email : <input type="text" name="email" value="' . $utilisateur['Email'] . '"><br>';
        echo 'Mot de passe : <input type="text" name="mot_de_passe" value="' . $utilisateur['Mot_de_passe'] . '"><br>';
        echo 'Role : ';
        echo '<select name="id_role">';
        echo '<option value="1" ' . ($utilisateur['idRole'] == 1 ? 'selected' : '') . '>Client</option>';
        echo '<option value="2" ' . ($utilisateur['idRole'] == 2 ? 'selected' : '') . '>Gestionnaire de commande</option>';
        echo '<option value="3" ' . ($utilisateur['idRole'] == 3 ? 'selected' : '') . '>Administrateur</option>';
        echo '</select><br>';
        echo '<input type="submit" name="submit" value="Enregistrer les modifications">';
        echo '</form>';
    } else {
        echo 'L\'utilisateur n\'existe pas.';
    }
} else {
    echo 'L\'ID de l\'utilisateur n\'a pas été spécifié.';
}
?>