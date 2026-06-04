<?php
declare(strict_types=1);
session_start();
require_once  __DIR__ . '/../../BDD/bdd.php';
require_once  __DIR__ . '/../security.php';

/* 1. Authentification + méthode POST + CSRF (action sensible) */
$user = exiger_utilisateur_connecte();
exiger_methode_post();
verifier_csrf();

/* 2. Validation de l'id */
$id_tache = valider_id($_POST['id'] ?? null);

/* 3. Bascule URGENT (gère le cas NULL grâce à IF) */
try {
    $connexion = getConnexion();
    $sql = "UPDATE TACHES 
            SET URGENT = IF(URGENT = 1, 0, 1) 
            WHERE ID_T = :id AND UTILISATEUR_T = :user";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([':id' => $id_tache, ':user' => $user]);
    $connexion = null;
} catch (PDOException $e) {
    gerer_erreur_pdo($e, 'urgent');
}
/* 4. Succès */
rediriger_succes('urgent_updated');
?>