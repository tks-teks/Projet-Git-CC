<?php
// Chargements initiaux
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../inc/db.php';

session_start();

/**
 * Autoloader simple : convertit un nom de classe / namespace en fichier sous ../src/
 * Exemple : App\Controllers\DashboardController -> ../src/App/Controllers/DashboardController.php
 * Permet de supporter des classes namespacées ou non.
 */
spl_autoload_register(function ($class) {
    $base = __DIR__ . '/../src/';
    $path = $base . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
        return;
    }

    // fallback : chercher directement dans Controllers/ (classe non namespacée)
    $ctrlPath = $base . 'Controllers/' . $class . '.php';
    if (file_exists($ctrlPath)) {
        require_once $ctrlPath;
    }
});

// Récupération du controller/action demandés (sanitisation basique)
$requestedController = $_GET['controller'] ?? 'Dashboard';
$requestedAction = $_GET['action'] ?? 'index';

// Autoriser uniquement lettres, chiffres et underscore pour sécurité
if (!preg_match('/^[A-Za-z0-9_]+$/', $requestedController) || !preg_match('/^[A-Za-z0-9_]+$/', $requestedAction)) {
    http_response_code(400);
    echo 'Requête invalide.';
    exit;
}

$baseControllerClass = $requestedController . 'Controller';

// Possibles namespaces / positions du contrôleur (ordre de priorité)
$candidates = [
    $baseControllerClass,
    'App\\Controllers\\' . $baseControllerClass,
    'Src\\Controllers\\' . $baseControllerClass,
    'Controllers\\' . $baseControllerClass,
];

// Trouver la classe existante
$controllerClass = null;
foreach ($candidates as $cand) {
    if (class_exists($cand)) {
        $controllerClass = $cand;
        break;
    }
}

if ($controllerClass === null) {
    // Classe non trouvée -> message utile pour débogage
    error_log('Controller not found: tried ' . implode(', ', $candidates));
    http_response_code(500);
    echo 'Contrôleur introuvable. Vérifiez que la classe existe et que l\'autoloader est correct.';
    exit;
}

// Vérifier que l'action existe sur la classe
if (!method_exists($controllerClass, $requestedAction)) {
    http_response_code(404);
    echo 'Action introuvable.';
    exit;
}

try {
    $controllerInstance = new $controllerClass();
    // Appel de l'action
    $controllerInstance->{$requestedAction}();
} catch (Throwable $e) {
    error_log('Controller execution error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Erreur serveur.';
    exit;
}
?>