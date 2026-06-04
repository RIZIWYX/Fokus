<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set("log_errors", "1");
ini_set("display_errors", "0");   // 0 en production (mettre 1 en développement)

const HOST = "localhost";
const DBNAME ="grainray";
const MDP = "";
const LOGIN="root";


function getConnexion(): PDO {
    try {
        $dsn = "mysql:host=" . HOST . ";dbname=" . DBNAME . ";charset=utf8";
        $connexion = new PDO($dsn, LOGIN, MDP);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $connexion;
    } catch (PDOException $e) {
        error_log("Erreur BDD : " . $e->getMessage());   // détail journalisé
        http_response_code(500);
        exit("Une erreur interne est survenue. Veuillez réessayer plus tard.");
    }
}
?>
