<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set("log_errors", "1");
ini_set("display_errors", "0");

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../php/version_php.php';

// Alerte dans les logs serveur si PHP n'a pas été mis à jour (PHP2Shell)
if (php_est_vulnerable_php2shell()) {
    error_log("SECURITE : PHP " . PHP_VERSION . " est vulnérable à PHP2Shell (CVE-2026-109618/109619), mettre à jour.");
}

function getConnexion(): PDO {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $connexion = new PDO($dsn, DB_USER, DB_PASS);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $connexion;
    } catch (PDOException $e) {
        error_log("Erreur BDD : " . $e->getMessage());
        http_response_code(500);
        exit("Une erreur interne est survenue. Veuillez réessayer plus tard.");
    }
}
?>
