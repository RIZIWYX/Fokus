<?php
declare(strict_types=1);

/* ==========================================================
   FOKUS - verifier_php.php
   A lancer sur le serveur après chaque mise à jour :
       php scripts/verifier_php.php
   Code de sortie 0 si tout est bon, 1 sinon.
   Attention : en CLI, PHP lit parfois un php.ini différent de
   celui d'Apache ou de PHP-FPM. Vérifier aussi phpinfo() côté web.
   ========================================================== */

require_once __DIR__ . '/../php/version_php.php';

$ok = true;

echo "Version de PHP : " . PHP_VERSION . "\n";

if (php_est_vulnerable_php2shell()) {
    echo "[ERREUR] Version vulnérable à PHP2Shell (CVE-2026-109618 / CVE-2026-109619).\n";
    echo "         Minimum requis : 8.2.35, 8.3.36, 8.4.27 ou 8.5.12.\n";
    $ok = false;
} else {
    echo "[OK] Version corrigée.\n";
}

if (ini_get('session.upload_progress.enabled')) {
    echo "[ATTENTION] session.upload_progress.enabled est actif (voir conf/php-fokus.ini).\n";
    $ok = false;
} else {
    echo "[OK] session.upload_progress désactivé.\n";
}

if (PHP_VERSION_ID >= 80500 && ini_get('fatal_error_backtraces')) {
    echo "[ATTENTION] fatal_error_backtraces est actif (voir conf/php-fokus.ini).\n";
    $ok = false;
}

exit($ok ? 0 : 1);
