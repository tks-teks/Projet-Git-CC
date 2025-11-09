<?php
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et nettoyage
    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $latitude = trim($_POST['latitude'] ?? '');
    $longitude = trim($_POST['longitude'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? '';

    // Validation minimale
    if ($nom === '' || $adresse === '' || $email === '' || $password_raw === '') {
        $msg = "Veuillez remplir les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email invalide.";
    } else {
        // Vérifier si l'email existe déjà
        $check = $conn->prepare("SELECT id FROM pharmacies WHERE email = ?");
        if ($check === false) {
            $msg = "Erreur serveur (préparation).";
        } else {
            $check->bind_param("s", $email);
            $check->execute();
            $res = $check->get_result();
            if ($res && $res->num_rows > 0) {
                $msg = "Cet email est déjà utilisé.";
            } else {
                // Hash du mot de passe
                $password = password_hash($password_raw, PASSWORD_DEFAULT);

                // Normaliser latitude/longitude : allow empty (NULL)
                $lat_val = $latitude === '' ? null : $latitude;
                $lon_val = $longitude === '' ? null : $longitude;

                // Préparer l'insertion
                $stmt = $conn->prepare("INSERT INTO pharmacies (nom, adresse, latitude, longitude, contact, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt === false) {
                    $msg = "Erreur serveur (préparation insertion).";
                } else {
                    // Utiliser types string pour simplicité ; MySQL convertira si colonnes numériques
                    $stmt->bind_param("sssssss", $nom, $adresse, $lat_val, $lon_val, $contact, $email, $password);
                    if ($stmt->execute()) {
                        // succès : rediriger vers la page de connexion ou afficher message
                        header("Location: login.php?registered=1");
                        exit;
                    } else {
                        $msg = "Erreur lors de l'inscription : " . $conn->error;
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Inscription Pharmacie</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 shadow-lg rounded-2xl w-full max-w-lg">
            <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Inscription Pharmacie</h2>

            <?php if ($msg): ?>
                <p class="text-red-600 mb-4"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form method="POST" class="grid grid-cols-2 gap-4">
                <input name="nom" placeholder="Nom de la pharmacie" class="col-span-2 p-3 border rounded-lg" required>
                <input name="adresse" placeholder="Adresse" class="col-span-2 p-3 border rounded-lg" required>
                <input name="latitude" type="number" step="any" placeholder="Latitude" class="p-3 border rounded-lg">
                <input name="longitude" type="number" step="any" placeholder="Longitude" class="p-3 border rounded-lg">
                <input name="contact" placeholder="Téléphone" class="p-3 border rounded-lg">
                <input type="email" name="email" placeholder="Email" class="col-span-2 p-3 border rounded-lg" required>
                <input type="password" name="password" placeholder="Mot de passe" class="col-span-2 p-3 border rounded-lg" required>
                <button class="col-span-2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">S'inscrire</button>
            </form>
        </div>
    </body>
</html>