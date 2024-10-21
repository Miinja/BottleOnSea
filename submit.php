<?php
session_start();

// Génération d'un jeton CSRF unique pour chaque session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérification du jeton CSRF pour protéger contre les attaques CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    die('Échec de la vérification CSRF.');
}

// Configuration de la connexion à la base de données
$host = 'localhost';
$db = 'bottleonsea';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Connexion sécurisée à la base de données
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log($e->getMessage());
    die('Erreur de connexion au serveur.');
}

// Vérification et sanitation des entrées utilisateur
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $announcement = sanitizeInput($_POST['announcement']);
    $pgpKey = sanitizeInput($_POST['pgpKey']);
    $signature = sanitizeInput($_POST['signature']);

    // Vérification de la signature PGP avec GnuPG
    putenv("GNUPGHOME=/var/www/.gnupg"); // Utilisation de GnuPG en ligne de commande, adapter le chemin selon l'hébergement
    $gpg = new gnupg();
    $gpg->seterrormode(gnupg::ERROR_EXCEPTION);
    $gpg->import($pgpKey);

    $isSignatureValid = $gpg->verify($announcement, $signature);

    if ($isSignatureValid) {
        // Insérer l'annonce et la clé publique dans la base de données
        $stmt = $pdo->prepare("INSERT INTO annonces (announcement, pgp_key, signature) VALUES (:announcement, :pgpKey, :signature)");
        $stmt->execute([
            ':announcement' => $announcement,
            ':pgpKey' => $pgpKey,
            ':signature' => $signature,
        ]);

        echo "Annonce publiée avec succès !";
    } else {
        echo "Échec de la vérification de la signature PGP.";
    }
}
?>
