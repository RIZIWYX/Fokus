# FOKUS

Application web de gestion de tâches pour aider les étudiants à organiser leur travail, voir leurs échéances et garder le focus.

Projet fait en binôme pour le cours Langages web, Licence 2 Informatique, Université de Rouen Normandie.

## Fonctionnalités

- Comptes : inscription, connexion, déconnexion, suppression du compte
- Tâches : création, modification, suppression, marquage urgent
- Dashboard : tâches triées par statut (à faire, en cours, terminée), les urgentes en avant
- Agenda : calendrier mensuel, jours cliquables avec le nombre de tâches par date
- Statistiques sur la progression et l'activité
- Recherche des tâches pendant la frappe
- Thème sombre avec accents dorés, responsive

## Stack technique

| Couche | Technologies |
|--------|--------------|
| Front-end | HTML5, CSS3, JavaScript sans framework |
| Back-end | PHP 8.2+ (PDO) |
| Base de données | MySQL |
| Outils | Git, GitHub, VS Code |

Pas de framework, on a tout écrit à la main pour bien comprendre les bases.

## Structure du projet

```
fokus/
├── html/              # Pages utilisateur
│   ├── dashboard.php
│   ├── agenda.php
│   ├── page_login.php
│   └── ...
├── php/               # Logique métier
│   ├── security.php   # Fonctions de sécurité communes
│   ├── version_php.php
│   ├── login/         # Authentification (login, register)
│   ├── add/           # Création
│   ├── delete/        # Suppression (tâches, compte...)
│   └── session/       # Données de session et statistiques
├── js/                # Scripts client
│   ├── main.js        # Recherche, interactions
│   ├── calendar.js    # Calendrier
│   └── edit.js        # Édition de tâches
├── css/               # Feuilles de style
├── BDD/
│   └── bdd.php        # Connexion PDO
├── conf/
│   └── php-fokus.ini  # Réglages PHP à mettre sur le serveur
├── scripts/
│   └── verifier_php.php
└── sql/
    └── script.sql     # Schéma et données de départ
```

## Sécurité

Les protections sont regroupées dans `php/security.php` :

- Mots de passe hashés avec `password_hash()` (bcrypt), jamais stockés en clair
- Jeton CSRF sur tous les formulaires et sur les actions sensibles (suppressions, bascule urgent), qui passent en POST et plus en GET
- Requêtes préparées PDO pour tous les accès à la base
- Validation des entrées : longueur, format, type, listes de valeurs autorisées
- Pas d'open redirect : on vérifie le host du referer avant de rediriger
- Erreurs PDO écrites dans les logs serveur, message générique pour l'utilisateur
- À chaque requête on vérifie que le compte de la session existe encore en base

Failles traitées :

- [CSRF sur les actions de suppression](SECURITE_CSRF.md)
- [PHP2Shell, CVE-2026-109618 et CVE-2026-109619](SECURITE_PHP2SHELL.md)

## Installation

### Prérequis

- PHP 8.2.35, 8.3.36, 8.4.27, 8.5.12 ou plus récent. Les versions plus anciennes sont touchées par PHP2Shell.
- MySQL ou MariaDB
- Apache (ou un autre serveur web)

### Étapes

1. Cloner le dépôt

   ```bash
   git clone https://github.com/riziwyx/fokus.git
   cd fokus
   ```

2. Vérifier la version de PHP

   ```bash
   php scripts/verifier_php.php
   ```

3. Créer la base de données

   ```bash
   mysql -u root -p < sql/script.sql
   ```

4. Configurer la connexion : copier `BDD/config.example.php` en `BDD/config.php` et mettre ses identifiants MySQL.

5. Sur un serveur, copier `conf/php-fokus.ini` dans le `conf.d` de PHP puis redémarrer Apache ou PHP-FPM.

6. Lancer le serveur en local

   ```bash
   php -S localhost:8000
   ```

7. Ouvrir [http://localhost:8000/html/page_de_garde.php](http://localhost:8000/html/page_de_garde.php)

## Utilisation

1. Créer un compte (mot de passe de 8 caractères minimum avec une minuscule, une majuscule et un chiffre)
2. Se connecter
3. Ajouter des tâches depuis le dashboard : titre, description, échéance, statut, urgence
4. Voir l'agenda pour la vue calendrier
5. Suivre l'avancement dans les statistiques

## Auteurs

Rayane Graine et Accyl Benadjaoud, L2 Informatique, Université de Rouen Normandie, 2025-2026.

## Licence

Projet académique, usage pédagogique.
