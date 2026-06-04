<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set("log_errors", "1");
ini_set("display_errors", "0");

require_once __DIR__ . '/config.php';

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
