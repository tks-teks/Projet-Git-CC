<?php
$q = trim($_GET['q'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pharma Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">💊 Pharma Connect</h1>

        <form action="search.php" method="GET" class="flex flex-col gap-4" role="search" aria-label="Rechercher un médicament">
            <label for="q" class="sr-only">Recherchez un médicament</label>
            <input
                id="q"
                name="q"
                type="text"
                placeholder="Recherchez un médicament..."
                required
                maxlength="255"
                autofocus
                autocomplete="off"
                class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>"
            >
            <button
                type="submit"
                class="bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
            >
                🔍 Rechercher
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-600">
            Vous êtes une pharmacie ? 
            <a href="./pages/login.php" class="text-blue-600 hover:underline font-medium">Connectez-vous ici</a>
        </p>
    </div>

</body>
</html>
