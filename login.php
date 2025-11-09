<?php
include 'db.php';
session_start();

$msg = "";

// message après inscription
if (isset($_GET['registered'])) {
    $msg = "Inscription réussie, connectez-vous.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // validation minimale
    if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email ou mot de passe invalide.";
    } else {
        // Requête sûre avec fallback : SELECT * pour éviter erreur si les noms de colonnes diffèrent
        $query = "SELECT * FROM pharmacies WHERE email = ? LIMIT 1";
        try {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
        } catch (Exception $e) {
            error_log('DB prepare/execute error (login): ' . $e->getMessage());
            $msg = "Erreur serveur.";
            $res = false;
        }

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();

            // Détecter dynamiquement la colonne du mot de passe
            $possiblePwdCols = ['password', 'passwd', 'pwd', 'motdepasse', 'mot_de_passe', 'pass'];
            $pwdField = null;
            foreach ($possiblePwdCols as $col) {
                if (array_key_exists($col, $user)) {
                    $pwdField = $col;
                    break;
                }
            }

            if ($pwdField === null) {
                error_log('No password column found in pharmacies table. Columns: ' . implode(',', array_keys($user)));
                $msg = "Erreur serveur (configuration DB).";
            } else {
                $storedHash = $user[$pwdField];

                // Support password_verify for hashed passwords; otherwise compare raw (not recommended)
                $verified = false;
                if (password_verify($password, $storedHash)) {
                    $verified = true;
                } elseif (hash_equals($storedHash, $password)) {
                    // fallback fragile: plain-text password stored (à éviter) — on autorise la connexion mais logguer
                    error_log('Plaintext password detected for user ' . $email . ' — migrate to hashed passwords.');
                    $verified = true;
                }

                if ($verified) {
                    // Détecter dynamiquement la colonne id pour la session
                    $possibleIdCols = ['id', 'id_pharmacie', 'id_pharma', 'pharmacie_id', 'pharmacy_id'];
                    $idField = null;
                    foreach ($possibleIdCols as $col) {
                        if (array_key_exists($col, $user)) {
                            $idField = $col;
                            break;
                        }
                    }

                    session_regenerate_id(true);
                    if ($idField !== null) {
                        $_SESSION['pharmacie_id'] = (int)$user[$idField];
                    } else {
                        // fallback : stocker l'entiereté minimale de l'utilisateur
                        $_SESSION['pharmacie'] = $user;
                    }

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $msg = "Email ou mot de passe incorrect.";
                }
            }
        } else {
            $msg = "Email ou mot de passe incorrect.";
        }
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
            <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4" autocomplete="off">
            <input type="email" name="email" placeholder="Email" required class="p-3 border rounded-lg" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>">
            <input type="password" name="password" placeholder="Mot de passe" required class="p-3 border rounded-lg">
            <button class="bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Se connecter</button>
        </form>

        <p class="text-center mt-4 text-sm">
            Pas encore de compte ? <a href="register.php" class="text-blue-600 hover:underline">S'inscrire</a>
        </p>
    </div>
</body>
</html>