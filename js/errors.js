"use strict";

const errorMessages = {
  /* -----------------------------
     CHAMPS & VALIDATION
     ----------------------------- */
  champs_vides: "Veuillez remplir tous les champs obligatoires.",
  champ_trop_long: "Un champ dépasse la longueur maximale autorisée.",
  couleur_invalide: "La couleur saisie est invalide.",
  date_invalide: "La date saisie est invalide.",
  cgu: "Conditions générales d'utilisations non acceptées.",
  nb_taches_invalide: "Nombre de tâches selectionées invalide.",

  /* -----------------------------
     CATÉGORIES / OBJECTIFS / TÂCHES
     ----------------------------- */
  add_categorie: "Échec de l'ajout de la catégorie. Veuillez réessayer.",
  edit_categorie: "Impossible de modifier la catégorie. Veuillez réessayer.",
  delete_categorie:
    "Échec de la suppression de la catégorie. Veuillez réessayer.",
  delete_allcategories:
    "Impossible de supprimer toutes les catégories. Veuillez réessayer.",

  add_objectif: "Échec de l'ajout de l'objectif. Veuillez réessayer.",
  edit_goal: "Impossible de modifier l'objectif. Veuillez réessayer.",
  delete_goal: "Échec de la suppression de l'objectif. Veuillez réessayer.",
  delete_allgoals:
    "Impossible de supprimer tous les objectifs. Veuillez réessayer.",

  add_task: "Échec de l'ajout de la tâche. Veuillez réessayer.",
  edit_task: "Impossible de modifier la tâche. Veuillez réessayer.",
  delete_task: "Échec de la suppression de la tâche. Veuillez réessayer.",
  delete_alltasks:
    "Impossible de supprimer toutes les tâches. Veuillez réessayer.",
  delete_taskscolumn:
    "Impossible de supprimer la colonne des tâches. Veuillez réessayer.",

  urgent: "Impossible de mettre la tâche en urgente. Veuillez réessayer.",
  forgot: "Échec de la demande de récupération. Veuillez réessayer.",

  /* -----------------------------
     AUTHENTIFICATION
     ----------------------------- */
  login:
    "Impossible de se connecter. Veuillez vérifier vos identifiants et réessayer.",
  register: "Impossible de créer le compte. Veuillez réessayer.",
  reset_password:
    "Impossible de réinitialiser le mot de passe. Veuillez réessayer.",
  reset_password_forgot:
    "Échec de la réinitialisation du mot de passe oublié. Veuillez réessayer.",
  reset_password_update:
    "Échec de la mise à jour du mot de passe. Veuillez réessayer.",
  identifiants_incorrects: "Email ou mot de passe incorrect.",
  mauvaise_reponse: "Réponse secrète incorrecte.",

  /* -----------------------------
     UTILISATEUR
     ----------------------------- */
  update_profile: "Impossible de mettre à jour le profil. Veuillez réessayer.",
  username_pris: "Ce nom d'utilisateur est déjà pris.",
  username_invalide: "Nom d'utilisateur invalide.",
  utilisateur_introuvable: "Utilisateur introuvable.",
  delete_account: "Impossible de supprimer le compte. Veuillez réessayer.",

  /* -----------------------------
     EMAIL
     ----------------------------- */
  email_invalide: "Adresse e-mail invalide.",
  email_pris: "Cet email est déjà utilisé.",

  /* -----------------------------
     MOT DE PASSE
     ----------------------------- */
  mdp_court: "Le mot de passe doit faire au moins 8 caractères.",
  mdp_trop_long: "Le mot de passe est trop long (maximum 72 caractères).",
  mdp_faible:
    "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.",
  mdp_different: "Les mots de passe ne correspondent pas.",
  mdp_faux: "Le mot de passe actuel est incorrect.",
  mdp_oblg: "Le mot de passe est obligatoire.",

  /* -----------------------------
     PHOTO
     ----------------------------- */
  photo_trop_lourde: "La photo ne doit pas dépasser 2 Mo.",
  upload_photo: "Échec du téléversement de la photo. Veuillez réessayer.",
  photo_type_invalide: "Format invalide. Utilisez JPG, PNG, WEBP ou GIF.",
  delete_photo: "Échec de la suppression de la photo. Veuillez réessayer.",

  /* -----------------------------
     SÉCURITÉ / GÉNÉRAL
     ----------------------------- */
  csrf_invalide: "Session expirée, veuillez recharger la page.",
  methode_invalide: "Requête invalide.",
  erreur_serveur: "Une erreur serveur est survenue, veuillez réessayer.",
  categorie: " Veuillez réessayer.",
  data_user: " Veuillez réessayer.",
  statistiques: " Veuillez réessayer.",

  /* -----------------------------
     HISTORIQUE
     ----------------------------- */
  delete_historique:
    "Impossible de supprimer l'historique. Veuillez réessayer.",
};

/* =========================================================
   RÉCUPÉRATION DU MESSAGE D'ERREUR VIA URL
   Exemple : ?error=login
   ========================================================= */
const params = new URLSearchParams(window.location.search);
const errorKey = params.get("error");

/* =========================================================
   AFFICHAGE DU MESSAGE DANS LA MODALE SI EXISTE
   ========================================================= */
if (errorKey && errorMessages[errorKey]) {
  document.getElementById("modal-error-msg").textContent =
    errorMessages[errorKey];

  openModal("modal-error");

  // Nettoyage de l'URL pour éviter le rechargement du message
  history.replaceState(null, "", window.location.pathname);
}
