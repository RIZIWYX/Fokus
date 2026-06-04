<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../BDD/bdd.php';
require_once __DIR__ . '/../security.php';

/* 1. Méthode HTTP, authentification, CSRF */
$user = exiger_utilisateur_connecte();
exiger_methode_post();
verifier_csrf();

/* 2. Suppression de tout l'historique de l'utilisateur */
try {
    $connexion = getConnexion();
    $stmt = $connexion->prepare("DELETE FROM HISTORIQUE WHERE UTILISATEUR_T = :user");
    $stmt->execute([':user' => $user]);
    $connexion = null;
} catch (PDOException $e) {
    gerer_erreur_pdo($e, 'delete_historique');
}

/* 3. Succès */
rediriger_succes('historique_vide');
?>
