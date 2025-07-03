<?php
header('Content-Type: application/json');

// Vérification méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Méthode non autorisée']));
}

// Récupération des données
$data = [
    'name' => htmlspecialchars($_POST['name'] ?? ''),
    'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
    'subject' => htmlspecialchars($_POST['subject'] ?? ''),
    'message' => htmlspecialchars($_POST['message'] ?? '')
];

// Validation
if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['error' => 'Email invalide']));
}

// Envoi d'email
$to = 'elabdolhadi@gmail.com';
$subject = 'Nouveau message: ' . $data['subject'];
$message = "Nom: {$data['name']}\nEmail: {$data['email']}\n\nMessage:\n{$data['message']}";
$headers = "From: {$data['email']}\r\nReply-To: {$data['email']}";

if (mail($to, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'envoi']);
}
?>