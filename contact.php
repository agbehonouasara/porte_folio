<?php
// =============================================================================
// CONTACT.PHP — Traitement du formulaire de contact du portfolio
// Ce script ne renvoie jamais de HTML : il traite les données envoyées par
// le formulaire (voir <form> dans porte_fo.php), puis redirige toujours
// l'utilisateur vers porte_fo.php avec un message de succès ou d'erreur.
// C'est le pattern "PRG" (Post/Redirect/Get) : il évite qu'un rafraîchissement
// de page (F5) ne renvoie le formulaire une deuxième fois.
// =============================================================================

declare(strict_types=1); // Vérification stricte des types PHP

// -----------------------------------------------------------------------
// 1) On n'accepte que les requêtes POST (envoyées par le <form method="POST">).
// Si quelqu'un ouvre contact.php directement dans le navigateur (GET), on le
// renvoie simplement vers la section contact du portfolio.
// -----------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: porte_fo.php#contact');
    exit; // exit() est obligatoire après un header('Location: ...') pour stopper le script
}

// -----------------------------------------------------------------------
// 2) Récupération et nettoyage des champs du formulaire.
// "?? ''" évite une erreur PHP si le champ n'existe pas dans $_POST.
// trim() retire les espaces inutiles au début/fin (ex: " Sara " -> "Sara").
// -----------------------------------------------------------------------
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

// -----------------------------------------------------------------------
// 3) Validation côté serveur (indispensable : la validation JS du navigateur
// peut toujours être contournée, donc on revérifie ici).
// filter_var(..., FILTER_VALIDATE_EMAIL) vérifie que l'email a un format valide.
// -----------------------------------------------------------------------
if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Un champ est vide ou l'email est invalide : on repart avec un statut d'erreur
    header('Location: porte_fo.php?status=error#contact');
    exit;
}

// -----------------------------------------------------------------------
// 4) Construction de l'entrée à enregistrer (un message = un tableau associatif)
// -----------------------------------------------------------------------
$entry = [
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'date' => date('Y-m-d H:i:s'), // Horodatage au format lisible
];

// -----------------------------------------------------------------------
// 5) Préparation du dossier de stockage (data/).
// On le crée automatiquement s'il n'existe pas encore (0777 = droits larges,
// nécessaires ici car XAMPP/Apache doit pouvoir écrire dedans).
// -----------------------------------------------------------------------
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

// -----------------------------------------------------------------------
// 6) Lecture du fichier existant (s'il y en a un) pour ne pas écraser les
// anciens messages, puis ajout du nouveau message à la liste.
// -----------------------------------------------------------------------
$file = $dataDir . '/messages.json';
$messages = [];

if (file_exists($file)) {
    $content = file_get_contents($file);
    $decoded = json_decode($content, true); // true = retourne un tableau PHP (pas un objet)
    if (is_array($decoded)) {
        $messages = $decoded;
    }
}

$messages[] = $entry; // Ajoute le nouveau message à la fin du tableau

// -----------------------------------------------------------------------
// 7) Réécriture complète du fichier JSON avec la liste mise à jour.
// JSON_PRETTY_PRINT = fichier lisible/indenté (facile à ouvrir et vérifier).
// JSON_UNESCAPED_UNICODE = garde les accents lisibles (é, à...) au lieu de é.
// -----------------------------------------------------------------------
file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// -----------------------------------------------------------------------
// 8) Redirection finale vers le portfolio avec un statut de succès,
// affiché ensuite comme message vert grâce à $_GET['status'] dans porte_fo.php.
// -----------------------------------------------------------------------
header('Location: porte_fo.php?status=success#contact');
exit;
