<?php
// Démarrer la session
session_start();

// Génération d'un jeton CSRF unique pour chaque session si non déjà défini
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Assurez-vous que le jeton CSRF est défini avant de l'afficher dans le formulaire
$csrf_token = isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token']) : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BottleOnSea - Publier une annonce</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>BottleOnSea - Publier une Annonce</h1>
        <form id="announceForm" action="submit.php" method="POST">
            <!-- Ajout du jeton CSRF dans le formulaire -->
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="form-group">
                <label for="announcement">Votre Annonce :</label>
                <textarea id="announcement" name="announcement" required placeholder="Écrivez votre annonce ici..." maxlength="5000"></textarea>
            </div>
            <div class="form-group">
                <label for="pgpKey">Votre Clé Publique PGP :</label>
                <textarea id="pgpKey" name="pgpKey" required placeholder="Entrez votre clé publique PGP ici..." maxlength="1000"></textarea>
            </div>
            <div class="form-group">
                <label for="signature">Signature PGP de l'Annonce :</label>
                <textarea id="signature" name="signature" required placeholder="Entrez la signature PGP de votre annonce..." maxlength="1000"></textarea>
            </div>
            <button type="submit">Publier l'Annonce</button>
        </form>
        <div id="message"></div>
    </div>
    <script src="script.js"></script>
</body>
</html>