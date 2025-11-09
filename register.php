<?php
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO pharmacies (nom, adresse, latitude, longitude, contact, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddss", $nom, $adresse, $latitude, $longitude, $contact, $email, $password);
    if ($stmt->execute()) {
        $msg = "Inscription réussie ! Vous pouvez vous connecter.";
    } else {
        $msg = "Erreur : " . $conn->error;
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
            <?php if ($msg): ?><p class="text-green-600 mb-4"><?= $msg ?></p><?php endif; ?>
            <form method="POST" class="grid grid-cols-2 gap-4">
                <input name="nom" placeholder="Nom de la pharmacie" class="col-span-2 p-3 border
                rounded-lg" required>
                <input name="adresse" placeholder="Adresse" class="col-span-2 p-3 border rounded-lg" required>
                <input name="latitude" placeholder="Latitude" class="p-3 border rounded-lg">
                <input name="longitude" placeholder="Longitude" class="p-3 border rounded-lg">
                <input name="contact" placeholder="Téléphone" class="p-3 border rounded-lg">
                <input type="email" name="email" placeholder="Email" class="col-span-2 p-3 border rounded￾lg" required>
                <input type="password" name="password" placeholder="Mot de passe" class="col-span-2 p- 3 border rounded-lg" required>
                <button class="col-span-2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue- 700">S'inscrire</button>
            </form>
        </div>
    </body>
</html>