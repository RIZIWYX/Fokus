# 🎯 Fokus

[![Démo](https://img.shields.io/badge/d%C3%A9mo-fokus--riziwyx.vercel.app-d4a017)](https://fokus-riziwyx.vercel.app/)
![PHP](https://img.shields.io/badge/PHP-8-777BB4)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Neon-4169E1)
![Vercel](https://img.shields.io/badge/d%C3%A9ploy%C3%A9%20sur-Vercel-black)
[![Licence MIT](https://img.shields.io/badge/licence-MIT-green)](LICENSE)

![Démo : connexion, création d'une tâche et tour de l'application](https://github.com/RIZIWYX/Fokus/raw/main/docs/demo.gif)

🌐 **Démo en ligne** : https://fokus-riziwyx.vercel.app/

Application web full-stack de gestion de tâches, conçue pour aider les étudiants
à organiser leur travail, visualiser leurs échéances et garder le focus.

Projet réalisé en binôme dans le cadre du cours **Langages web** — Licence 2 Informatique, Université de Rouen Normandie.

> **Branche `prod`** : c'est la version déployée sur Vercel, avec une base PostgreSQL hébergée chez Neon.
> La version de développement (MySQL, installation locale) se trouve sur la branche [`main`](https://github.com/RIZIWYX/Fokus/tree/main).

---

## ✨ Fonctionnalités

- 🔐 **Authentification sécurisée** — inscription, connexion, déconnexion, suppression de compte
- ✅ **Gestion complète des tâches** — création, modification, suppression, marquage urgent
- 📊 **Dashboard** — vue d'ensemble triée par statut (à faire, en cours, terminée) et tâches urgentes mises en avant
- 📅 **Agenda interactif** — calendrier mensuel avec navigation, jours cliquables et compteur de tâches par date
- 📈 **Statistiques personnelles** — suivi de la progression et de l'activité
- 🔎 **Recherche en temps réel** — filtrage instantané des tâches pendant la frappe
- 🎨 **Interface soignée** — thème sombre avec accents dorés, responsive

---

## 🛠️ Stack technique

| Couche | Technologies |
|--------|--------------|
| **Front-end** | HTML5, CSS3, JavaScript (vanilla) |
| **Back-end** | PHP 8 (PDO) |
| **Base de données** | PostgreSQL (Neon) |
| **Hébergement** | Vercel (runtime `vercel-php`) |

Aucun framework — tout est écrit à la main pour bien maîtriser les fondamentaux.

---

## 📁 Structure du projet

```
fokus/
├── api/
│   └── index.php          # Point d'entrée Vercel : route chaque requête vers le bon fichier PHP
├── vercel.json            # Configuration Vercel (runtime PHP, fichiers statiques)
├── index.php              # Page d'accueil
├── html/                  # Pages utilisateur (dashboard, agenda, login...)
├── php/                   # Logique métier
│   ├── security.php       # Bibliothèque centrale de sécurité
│   ├── version_php.php    # Détection d'une version de PHP vulnérable à PHP2Shell
│   ├── login/ add/ edit/ delete/ session/
├── js/                    # Scripts client (recherche, calendrier, édition)
├── css/                   # Feuilles de style
├── BDD/
│   └── bdd.php            # Connexion PDO à PostgreSQL (variables d'environnement)
├── conf/
│   └── php-fokus.ini      # Durcissement de PHP (PHP2Shell)
├── scripts/
│   └── verifier_php.php   # Vérification de la version de PHP (ligne de commande)
├── SECURITE_CSRF.md       # Analyse d'une faille CSRF corrigée
└── SECURITE_PHP2SHELL.md  # Faille PHP2Shell : risque et correctifs
```

---

## 🔒 Sécurité

La sécurité a été un fil rouge du projet. La bibliothèque `php/security.php` centralise toutes les protections.

- **Mots de passe** hashés avec `password_hash()` (bcrypt) — jamais stockés en clair
- **Protection CSRF** sur tous les formulaires **et sur les actions sensibles** (suppressions, bascule « urgent »), déclenchées en POST avec jeton vérifié — jamais en GET
- **Requêtes préparées** (PDO) sur tous les accès à la base, contre les injections SQL
- **Validation stricte** de toutes les entrées utilisateur (longueur, format, type, listes fermées)
- **Protection contre les open redirects** — vérification du host du referer avant redirection
- **Vérification de la méthode HTTP** (POST imposé sur les actions sensibles)
- **Contrôle de session** — vérification systématique de l'existence du compte en BDD
- **Identifiants de la base** fournis par les variables d'environnement, jamais dans le dépôt

### Faille PHP2Shell (octobre 2026)

Deux failles critiques de l'interpréteur PHP (CVE-2026-109618 et CVE-2026-109619) permettaient d'exécuter du code à distance sans être connecté. Correctifs appliqués :

- **Alerte automatique** : `BDD/bdd.php` écrit un avertissement dans les logs si la version de PHP est vulnérable
- **Surface d'attaque réduite** : `session.upload_progress` désactivé et tailles de requête limitées (`conf/php-fokus.ini`, avec `.htaccess` et `.user.ini` en repli)
- **Script de vérification** : `php scripts/verifier_php.php`, utilisable uniquement en ligne de commande

📄 Détails : [SECURITE_PHP2SHELL.md](SECURITE_PHP2SHELL.md) · [SECURITE_CSRF.md](SECURITE_CSRF.md)

---

## 🚀 Déploiement

### Prérequis

- PHP 8.2.35, 8.3.36, 8.4.27, 8.5.12 ou plus récent (les versions plus anciennes sont touchées par PHP2Shell)
- Une base PostgreSQL (par exemple chez [Neon](https://neon.tech))
- Un compte [Vercel](https://vercel.com)

### Variables d'environnement

À définir dans les réglages du projet Vercel :

| Variable | Rôle |
|---|---|
| `PGHOST` | Hôte PostgreSQL (endpoint Neon) |
| `PGDATABASE` | Nom de la base |
| `PGUSER` | Utilisateur |
| `PGPASSWORD` | Mot de passe |
| `PGPORT` | Port (5432 par défaut) |

Le site en ligne est déployé depuis cette branche.

---

## 👥 Auteurs

Projet réalisé par **Rayane Graïne** et **Accyl Benadjaoud** — L2 Informatique, Université de Rouen Normandie, 2025-2026.

---

## 📄 Licence

Distribué sous licence **MIT**, voir [LICENSE](LICENSE).
