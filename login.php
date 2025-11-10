<?php
include 'db.php';
session_start();

$msg = "";

// Message après inscription
if (isset($_GET['registered'])) {
    $msg = "Inscription réussie, connectez-vous.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email ou mot de passe invalide.";
    } else {
        // Préparer la requête
        $stmt = $conn->prepare("SELECT id_pharma, password FROM pharmacies WHERE email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Utiliser bind_result à la place de get_result
            $stmt->bind_result($id_pharma, $hash);
            if ($stmt->fetch()) {
                if (password_verify($password, $hash)) {
                    session_regenerate_id(true);
                    $_SESSION['id_pharma'] = $id_pharma;
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $msg = "Mot de passe incorrect.";
                }
            } else {
                $msg = "Aucun compte trouvé avec cet email.";
            }
            $stmt->close();
        } else {
            $msg = "Erreur serveur, réessayez plus tard.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Pharmacie</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 shadow-lg rounded-2xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Connexion Pharmacie</h2>

        <?php if ($msg): ?>
            <p class="text-red-500 text-sm mb-4 text-center"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4" autocomplete="off">
            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                required 
                class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>"
            >
            <input 
                type="password" 
                name="password" 
                placeholder="Mot de passe" 
                required 
                class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
            <button class="bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Se connecter
            </button>
        </form>

        <p class="text-center mt-4 text-sm">
            Pas encore de compte ? 
            <a href="register.php" class="text-blue-600 hover:underline">S'inscrire</a>
        </p>
    </div>

</body>
</html>
