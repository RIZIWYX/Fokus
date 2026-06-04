# Sécurité — Correction d'une faille CSRF sur les actions de suppression

Ce document décrit une vulnérabilité **CSRF (Cross-Site Request Forgery)** identifiée dans Fokus et le correctif appliqué, selon une démarche **problème → risque → correctif**.

---

## Problème

Les actions sensibles de l'application (suppression de tâche, de catégorie, d'objectif, vidage de l'historique, **suppression de compte**, bascule du statut « urgent »…) étaient déclenchées par des requêtes **GET**, via `location.href` côté JavaScript :

```js
// Ancien code (vulnérable)
location.href = "../php/delete/delete_task.php?id=" + id;
```
---

## Risque

Une action sensible accessible en GET et sans jeton CSRF peut être déclenchée **à l'insu de l'utilisateur connecté**. Un attaquant n'a qu'à amener la victime (authentifiée sur Fokus) à charger une URL ou une page piégée :

```html
<!-- Sur un site malveillant, ou dans un email : -->
<img src="https://fokus.example/php/delete/delete_account.php">
```

Le simple chargement de cette balise `<img>` envoie une requête GET vers Fokus **avec les cookies de session de la victime**. Résultat : **suppression du compte sans aucune interaction consciente**. La gravité est maximale car parmi les actions exposées figurent la suppression de compte et le vidage de toutes les données.

L'usage de GET aggrave le risque : les requêtes GET sont déclenchables par de simples balises (`<img>`, `<iframe>`, préchargement de lien), là où une attaque sur du POST demande au minimum un formulaire auto-soumis.

---

## Correctif

Trois mesures combinées, pour appliquer une défense en profondeur :

**1. Imposer la méthode POST** sur chaque action sensible. Une requête GET est désormais rejetée :

```php
exiger_methode_post();   // rejette tout ce qui n'est pas POST
```

Cela neutralise à lui seul les attaques par `<img>`, `<iframe>` ou préchargement, qui sont en GET.

**2. Vérifier un jeton CSRF** présent en session et transmis dans la requête :

```php
verifier_csrf();   // compare $_POST['csrf_token'] au jeton de session (hash_equals)
```

Un site tiers ne peut pas connaître ce jeton (protégé par la *same-origin policy*) : même un formulaire POST auto-soumis depuis un domaine externe est rejeté.

**3. Côté client**, les actions ne passent plus par `location.href` (GET) mais par un formulaire POST construit dynamiquement, qui inclut le jeton CSRF. Ce jeton est lu depuis un champ caché déjà présent sur la page (généré par `modals.php`). Pour l'action « urgent » (appel AJAX), le même principe est appliqué via un `fetch` POST transmettant le jeton dans le corps de la requête.

Les paramètres sont par conséquent lus depuis `$_POST` (et non plus `$_GET`) dans les handlers.

### Couverture après correctif

Les 11 handlers sensibles vérifient désormais tous : authentification → méthode POST → jeton CSRF, avant toute opération en base.

---


## Pistes d'approfondissement
- Régénérer l'identifiant de session (`session_regenerate_id`) après connexion, pour limiter la fixation de session.
- Envisager un en-tête `Content-Security-Policy` pour réduire la surface d'attaque XSS (un XSS permettrait de lire le jeton CSRF).
