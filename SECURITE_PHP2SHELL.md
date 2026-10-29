# Sécurité : faille PHP2Shell (CVE-2026-109618 et CVE-2026-109619)

Ce document explique la faille PHP2Shell, ce qu'elle impliquait pour Fokus et ce que nous avons mis en place pour la corriger. On suit la même démarche que pour [la faille CSRF](SECURITE_CSRF.md) : problème, risque, correctif.

Date de la correction : 25 octobre 2026.

---

## Problème

Le 21 octobre 2026, l'équipe de PHP a publié en urgence des correctifs pour deux failles critiques dans le cœur de l'interpréteur, surnommées "PHP2Shell". Les deux sont notées 9.2 sur 10 (CVSS v4.0). La faille ne vient pas de notre code : elle est dans PHP lui-même, donc tous les sites PHP étaient concernés, quel que soit leur code.

**CVE-2026-109618.** Quand PHP reçoit un formulaire, il range les champs dans `$_POST` avec une table de hachage. En envoyant un nom de champ d'une taille bien choisie, un attaquant peut faire dépasser `memory_limit` pile au milieu d'une insertion. PHP s'arrête sur une erreur fatale mais la table garde une case comptée et jamais initialisée. Au nettoyage de fin de requête, PHP lit cette case comme si c'était une vraie valeur : l'attaquant contrôle donc de la mémoire que PHP va manipuler.

**CVE-2026-109619.** Un worker PHP (Apache mod_php ou PHP-FPM) sert plusieurs requêtes à la suite et garde certaines données d'une requête à l'autre. Si une erreur fatale tombe pendant leur remise à zéro, la requête suivante peut lire de la mémoire déjà libérée (use-after-free). Le chemin d'attaque confirmé passe par la fonction de suivi d'upload des sessions (`session.upload_progress`), active par défaut, et sur PHP 8.5 par les backtraces d'erreurs fatales.

Point important : tout ça se passe **avant** que notre script ne s'exécute, pendant que PHP lit la requête. Nos protections (CSRF, validation des champs, requêtes préparées) n'entrent jamais en jeu.

### Versions touchées

| Branche | Vulnérable | Corrigé à partir de |
|---------|------------|---------------------|
| 8.2 | < 8.2.35 | 8.2.35 |
| 8.3 | < 8.3.36 | 8.3.36 |
| 8.4 | < 8.4.27 | 8.4.27 |
| 8.5 | < 8.5.12 | 8.5.12 |
| 8.1 et avant | oui | correctifs partiels seulement, il faut changer de branche |

Notre README demandait seulement "PHP 8.0 ou supérieur", ce qui laissait passer des versions vulnérables.

---

## Risque

- **Exécution de code à distance sans être connecté.** N'importe qui peut envoyer un POST à n'importe quelle page PHP du site, y compris `page_login.php`. Pas besoin de compte.
- **Accès complet aux données.** Une fois du code exécuté sur le serveur, l'attaquant lit `BDD/config.php` et récupère les identifiants MySQL. Il a alors toute la base : noms, emails, hash des mots de passe, réponses aux questions secrètes (stockées en clair), tâches.
- **Exploitation déjà en cours.** Des tentatives ont été vues dès le 17 octobre, quatre jours avant la publication des correctifs, et du code d'exploitation circule.

Pour un projet comme Fokus, le plus grave est la fuite des réponses aux questions secrètes : elles permettent de réinitialiser le mot de passe de n'importe quel compte, et les gens réutilisent souvent ce genre de réponse ailleurs.

---

## Correctif

### 1. Mise à jour de PHP (la seule vraie correction)

Le serveur doit tourner sur 8.2.35, 8.3.36, 8.4.27, 8.5.12 ou plus récent. Sur Debian ou Ubuntu :

```bash
sudo apt update && sudo apt upgrade php*
sudo systemctl restart apache2      # ou php8.4-fpm selon l'installation
```

Attention : certaines distributions corrigent sans changer le numéro de version. Dans ce cas il faut vérifier le changelog du paquet (`apt changelog php8.4`) et chercher les numéros de CVE.

Le minimum est maintenant écrit à deux endroits dans le dépôt :

- `composer.json` de la branche `fix/php2shell` : `"php": "~8.2.35 || ~8.3.36 || ~8.4.27 || >=8.5.12"` (non ajouté sur `prod`, où Vercel lancerait `composer install` à chaque déploiement)
- `README.md`, section Prérequis

### 2. Vérification automatique de la version

- `php/version_php.php` contient `php_est_vulnerable_php2shell()`, qui compare la version de PHP à la première version corrigée de sa branche.
- `BDD/bdd.php` l'appelle à chaque connexion et écrit une alerte dans les logs serveur si PHP n'est pas à jour. On a choisi de ne pas bloquer le site, pour ne pas le casser si la comparaison se trompe sur une version patchée par la distribution.
- `scripts/verifier_php.php` se lance à la main après une mise à jour :

```bash
php scripts/verifier_php.php
```

Il renvoie le code 0 si la version est corrigée et que l'upload progress est coupé, 1 sinon. Testé sur PHP 8.4.27.

### 3. Réduction de la surface d'attaque

Fokus n'utilise pas la barre de progression d'upload (la photo de profil est un upload classique), donc on peut la désactiver sans rien perdre. C'est aussi la seule protection contre CVE-2026-115560, une faille voisine annoncée le 23 octobre qui touche même les versions corrigées et sera réglée à la prochaine version de PHP.

Le fichier `conf/php-fokus.ini` est à copier dans le `conf.d` de PHP sur le serveur :

- `session.upload_progress.enabled = 0`
- `fatal_error_backtraces = 0` (utile en PHP 8.5)
- limites de taille : `post_max_size = 6M`, `upload_max_filesize = 4M` (la photo est limitée à 4 Mo dans `upload_photo.php`), `max_input_vars = 200`, `max_input_nesting_level = 8`
- `display_errors = Off`, `expose_php = Off`

Pour les hébergements où on n'a pas la main sur le `php.ini`, `.user.ini` (PHP-FPM) et `.htaccess` (mod_php) reprennent les réglages principaux. Le `.htaccess` bloque aussi l'accès direct aux dossiers `BDD/`, `conf/`, `scripts/` et `sql/`.

Ces réglages ne remplacent pas la mise à jour : ils compliquent l'attaque mais ne la rendent pas impossible.

### 4. Vérifier qu'on n'a pas déjà été touchés

Comme la faille était exploitée avant les correctifs, on ne peut pas juste mettre à jour et passer à autre chose. À faire sur le serveur :

- Chercher dans les logs PHP des erreurs `Allowed memory size ... exhausted` avec `Unknown on line 0`, et des `segmentation fault` (code `AH00052`) dans les logs Apache.
- Chercher dans les logs d'accès des POST avec des champs `PHP_SESSION_UPLOAD_PROGRESS` ou `PHPSESSID` dans le corps, ou des noms de champs très longs. Fokus n'envoie jamais ces champs.
- Regarder si l'utilisateur web (`www-data`) a lancé des processus inhabituels (`sh`, `curl`, `wget`) ou créé des fichiers dans le dossier du site.

Si on trouve quelque chose, ou si on ne peut pas vérifier :

1. Changer le mot de passe MySQL et mettre à jour `BDD/config.php`.
2. Invalider toutes les sessions (vider le dossier des sessions PHP).
3. Prévenir les utilisateurs de changer leur mot de passe et leur réponse secrète.

---

## Résultat

| Élément | Avant | Après |
|---------|-------|-------|
| Version PHP exigée | 8.0 ou plus | 8.2.35 / 8.3.36 / 8.4.27 / 8.5.12 minimum |
| Upload progress des sessions | actif (défaut PHP) | désactivé |
| Taille des requêtes | défauts PHP | limitée au besoin réel |
| Alerte si PHP pas à jour | aucune | message dans les logs + script de vérif |
| Accès direct à `BDD/`, `sql/` | possible selon le serveur | bloqué par `.htaccess` |

---

## Ce qu'on en retient

- Sécuriser notre code ne suffit pas : il faut aussi surveiller la version de PHP et du serveur, et suivre les annonces de sécurité de php.net.
- Écrire une version minimum précise, pas juste "PHP 8+".
- Désactiver ce qu'on n'utilise pas.
- Pistes pour la suite : hasher les réponses aux questions secrètes comme les mots de passe, et ajouter une limite de taille des requêtes au niveau d'Apache (`LimitRequestBody`).

## Sources

- [Annonce PHP du 21 octobre 2026](https://www.php.net/archive/2026.php#2026-10-21-5)
- [Datadog Security Labs, analyse technique de PHP2Shell](https://securitylabs.datadoghq.com/articles/php2shell-critical-vulnerabilities-in-php/)
- [Unit 42 (Palo Alto Networks), threat brief](https://unit42.paloaltonetworks.com/cve-2026-109618-cve-2026-109619/)
- [The Hacker News](https://thehackernews.com/2026/10/php-issues-emergency-patches-for-two.html)
