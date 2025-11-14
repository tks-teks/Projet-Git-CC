<?php
include __DIR__ . '/../db/db.php'; // Connexion mysqli

// Activer les erreurs PHP pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom       = $_POST['nom'] ?? '';
    $adresse   = $_POST['adresse'] ?? '';
    $latitude  = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $contact   = $_POST['contact'] ?? '';
    $email     = $_POST['email'] ?? '';
    $password  = $_POST['password'] ?? '';

    // Vérifier que tous les champs requis sont remplis
    if (empty($nom) || empty($adresse) || empty($email) || empty($password)) {
        echo "missing_fields";
        exit;
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $sql_check = "SELECT id_pharma FROM pharmacies WHERE email = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "email_exists";
        exit;
    }

    // Insérer les données
    $sql = "INSERT INTO pharmacies (nom, adresse, latitude, longitude, contact, email, password)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddsss", $nom, $adresse, $latitude, $longitude, $contact, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Pharmacie</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 shadow-lg rounded-2xl w-full max-w-lg">
    <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Inscription Pharmacie</h2>

    <!-- Bouton Retour -->
    <button onclick="window.history.back()" class="mb-4 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">← Retour</button>

    <form id="registerForm" class="grid grid-cols-2 gap-4">

        <input name="nom" placeholder="Nom de la pharmacie" class="col-span-2 p-3 border rounded-lg" required>
        <input name="adresse" placeholder="Adresse" class="col-span-2 p-3 border rounded-lg" required>
        <input name="latitude" type="number" step="any" placeholder="Latitude" class="p-3 border rounded-lg">
        <input name="longitude" type="number" step="any" placeholder="Longitude" class="p-3 border rounded-lg">
        <input name="contact" placeholder="Téléphone" class="p-3 border rounded-lg">
        <input type="email" name="email" placeholder="Email" class="col-span-2 p-3 border rounded-lg" required>
        <input type="password" name="password" placeholder="Mot de passe" class="col-span-2 p-3 border rounded-lg" required>

        <button type="submit" class="col-span-2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            S'inscrire
        </button>
    </form>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch('register.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.text();

    if (result.includes("success")) {
        Swal.fire({
            icon: "success",
            title: "Inscription réussie !",
            text: "Votre compte pharmacie a été créé.",
            confirmButtonColor: "#2563eb"
        });
        form.reset();
    } else if (result.includes("email_exists")) {
        Swal.fire({
            icon: "warning",
            title: "Email déjà utilisé",
            text: "Veuillez utiliser une autre adresse email.",
            confirmButtonColor: "#f59e0b"
        });
    } else if (result.includes("missing_fields")) {
        Swal.fire({
            icon: "error",
            title: "Champs manquants",
            text: "Veuillez remplir tous les champs obligatoires.",
            confirmButtonColor: "#dc2626"
        });
    } else {
        Swal.fire({
            icon: "error",
            title: "Erreur",
            text: "Une erreur s'est produite : " + result,
            confirmButtonColor: "#dc2626"
        });
    }
});
</script>

</body>
</html>
