<?php
include 'db.php';
session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM pharmacies WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['pharmacie_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Mot de passe incorrect.";
        }
    } else {
        $msg = "Email non trouvé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Pharmacie</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 shadow-lg rounded-2xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Connexion Pharmacie</h2>

        <?php if ($msg): ?>
            <p class="text-red-500 text-sm mb-4"><?= $msg ?></p>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">
            <input type="email" name="email" placeholder="Email" required class="p-3 border rounded-lg">
            <input type="password" name="password" placeholder="Mot de passe" required class="p-3 border rounded-lg">
            <button class="bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Se connecter</button>
        </form>

        <p class="text-center mt-4 text-sm">
            Pas encore de compte ? <a href="register.php" class="text-blue-600 hover:underline">S'inscrire</a>
        </p>
    </div>
</body>
</html>