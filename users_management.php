<?php
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


if (isset($_POST['delete_utilisateur'])) {
    $utilisateur_id = $_POST['utilisateur_id'];
    $delete_query = "DELETE FROM utilisateur WHERE id = :id";
    $stmt = $db->prepare($delete_query);
    $stmt->bindParam(':id', $utilisateur_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        // Vous pouvez rediriger ici ou afficher un message de succès
        header("Location: users_management.php");
        exit;
    } else {
        echo "Une erreur s'est produite lors de la suppression de l'utilisateur.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="src/css/user_management.css">
</head>

<body>
<?php include 'src/menu_admin.php'; ?>
    <h1>Liste des Utilisateurs</h1>
    <a href="add_users.php">Ajouter un nouvel utilisateur</a>

    <table>
        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Email</th>
            <th>Mot de passe</th>
            <th>Role</th>
        </tr>

        <?php
        $query = "SELECT utilisateur.id, utilisateur.Nom, utilisateur.Prénom, utilisateur.Email, utilisateur.Mot_de_passe, role.libelle
            FROM utilisateur
            JOIN role ON role.id = utilisateur.idRole";
        $stmt = $db->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['Nom'] . '</td>';
            echo '<td>' . $row['Prénom'] . '</td>';
            echo '<td>' . $row['Email'] . '</td>';
            echo '<td>' . $row['Mot_de_passe'] . '</td>';
            echo '<td>' . $row['libelle'] . ' </td>';
            echo '<td>';
            echo '<form method="post">';
            echo '<input type="hidden" name="utilisateur_id" value="' . $row['id'] . '">';
            echo '<button type="submit" name="delete_utilisateur">Supprimer</button>';
            echo '</form>';
            // Bouton de modification
            echo '<form method="get" action="modify_users.php">';
            echo '<input type="hidden" name="utilisateur_id" value="' . $row['id'] . '">';
            echo '<button type="submit" name="modify_users">Modifier</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>

</body>

</html>
