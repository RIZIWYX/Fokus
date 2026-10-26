# Sécurité : faille CSRF sur les actions de suppression

Ce document décrit une faille CSRF (Cross-Site Request Forgery) trouvée dans Fokus et la correction qu'on a faite. On le découpe en trois parties : problème, risque, correctif.

---

## Problème

Les actions sensibles (supprimer une tâche, une catégorie, un objectif, vider l'historique, supprimer le compte, passer une tâche en « urgent »...) se déclenchaient avec une requête GET, via `location.href` en JavaScript :

```js
// Ancien code (vulnérable)
location.href = "../php/delete/delete_task.php?id=" + id;
```
---

## Risque

Une action accessible en GET et sans jeton CSRF peut être lancée sans que l'utilisateur connecté s'en rende compte. Il suffit de lui faire charger une URL ou une page piégée :

```html
<!-- Sur un site malveillant, ou dans un email : -->
<img src="https://fokus.example/php/delete/delete_account.php">
```

Le navigateur charge l'image, donc envoie un GET vers Fokus avec les cookies de session de la victime, et le compte est supprimé sans qu'elle ait cliqué sur quoi que ce soit. C'est grave parce que parmi ces actions il y a la suppression du compte et le vidage de toutes les données.

Le fait d'utiliser GET rend l'attaque encore plus facile : une simple balise `<img>`, `<iframe>` ou un préchargement de lien suffit, alors qu'en POST il faut au moins un formulaire qui se soumet tout seul.

---

## Correctif

On a combiné trois protections.

**1. POST obligatoire** sur chaque action sensible, une requête GET est refusée :

```php
exiger_methode_post();   // rejette tout ce qui n'est pas POST
```

Ça bloque déjà les attaques par `<img>`, `<iframe>` ou préchargement, qui passent toutes en GET.

**2. Jeton CSRF** gardé en session et envoyé avec la requête :

```php
verifier_csrf();   // compare $_POST['csrf_token'] au jeton de session (hash_equals)
```

Un autre site ne peut pas lire ce jeton (same-origin policy), donc même un formulaire POST envoyé depuis un autre domaine est refusé.

**3. Côté client**, on n'utilise plus `location.href`. Les actions passent par un formulaire POST créé en JavaScript, qui contient le jeton CSRF lu dans un champ caché de la page (généré par `modals.php`). Pour « urgent », qui est en AJAX, on fait pareil avec un `fetch` en POST qui met le jeton dans le corps de la requête.

Du coup les handlers lisent leurs paramètres dans `$_POST` et plus dans `$_GET`.

### Après correction

Les 11 handlers sensibles font tous les mêmes vérifications, dans cet ordre, avant de toucher à la base : utilisateur connecté, méthode POST, jeton CSRF.

---

## Pour aller plus loin

- L'identifiant de session est maintenant régénéré à la connexion (`session_regenerate_id` dans `login.php`), ce qui limite la fixation de session.
- Ajouter un en-tête `Content-Security-Policy` pour limiter les risques de XSS (un XSS pourrait lire le jeton CSRF).
