<?php
declare(strict_types=1);

/* ==========================================================
   FOKUS - version_php.php
   Contrôle de la version de PHP face aux failles PHP2Shell
   (CVE-2026-109618 et CVE-2026-109619, octobre 2026).
   ========================================================== */

/* Première version corrigée pour chaque branche maintenue */
const PHP2SHELL_VERSIONS_CORRIGEES = [
    '8.2' => '8.2.35',
    '8.3' => '8.3.36',
    '8.4' => '8.4.27',
    '8.5' => '8.5.12',
];

/**
 * Retourne true si la version de PHP passée (ou celle qui tourne)
 * est vulnérable à PHP2Shell.
 * Les branches 7.4, 8.0 et 8.1 n'ont reçu que des correctifs
 * minimaux, sans changement de numéro chez certains distributeurs :
 * on les considère vulnérables, il faut passer en 8.2 minimum.
 */
function php_est_vulnerable_php2shell(string $version = PHP_VERSION): bool
{
    $branche = implode('.', array_slice(explode('.', $version), 0, 2));

    if (!isset(PHP2SHELL_VERSIONS_CORRIGEES[$branche])) {
        // Branche plus récente que 8.5 : corrigée. Plus ancienne : vulnérable.
        return version_compare($version, '8.6.0', '<');
    }

    return version_compare($version, PHP2SHELL_VERSIONS_CORRIGEES[$branche], '<');
}
