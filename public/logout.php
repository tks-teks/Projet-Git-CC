<?php
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to the login page with a logout message
header("Location: login.php?message=Vous êtes déconnecté.");
exit;
?>