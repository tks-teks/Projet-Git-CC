<?php
include 'db.php';
$msg = "";
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

    <p id="msg" class="text-red-600 mb-4"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>

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
        <button type="submit" class="col-span-2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">S'inscrire</button>
    </form>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // empêche le rechargement de la page

    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch('register.php', {
        method: 'POST',
        body: formData
    });

    const text = await response.text();

    // On peut extraire le message depuis la réponse ou remplacer tout le bloc
    document.getElementById('msg').innerHTML = text.includes('Inscription réussie') 
        ? 'Inscription réussie, connectez-vous.' 
        : 'Erreur lors de l\'inscription. Veuillez vérifier les champs.';
});
</script>

</body>
</html>
