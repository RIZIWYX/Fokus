"use strict";

/* ----------------------------------------------------------
   CONFIRM MESSAGES
---------------------------------------------------------- */
const confirms = {
  delete_task: "Supprimer cette tâche ?",
  delete_all_tasks: "Supprimer toutes les tâches ?",
  delete_category: "Supprimer cette catégorie ?",
  delete_all_categories: "Supprimer toutes les catégories ?",
  delete_goal: "Supprimer cet objectif ?",
  delete_all_goals: "Supprimer tous vos objectifs ?",
  delete_hist: "Supprimer votre historique ? Cette action est irréversible.",
  delete_account: "Supprimer votre compte ? Cette action est irréversible.",
  delete_taskscolumn: "Supprimer toutes les tâches de cette colonne ?",
};

let confirmAction = null;

/* ----------------------------------------------------------
   JETON CSRF
   Récupéré depuis un <input name="csrf_token"> présent sur la
   page (généré par modals.php), avec repli sur une <meta>.
---------------------------------------------------------- */
function getCsrfToken() {
  const input = document.querySelector('input[name="csrf_token"]');
  if (input && input.value) return input.value;
  const meta = document.querySelector('meta[name="csrf-token"]');
  if (meta && meta.content) return meta.content;
  return "";
}

/* ----------------------------------------------------------
   ENVOI D'UNE ACTION SENSIBLE EN POST
   Construit dynamiquement un formulaire POST contenant le
   jeton CSRF et les paramètres fournis, puis le soumet. On
   conserve ainsi la navigation pleine page (et le suivi des
   redirections header("Location") côté serveur), tout en
   protégeant l'action contre le CSRF.
---------------------------------------------------------- */
function postAction(url, params = {}) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = url;

  const champs = { csrf_token: getCsrfToken(), ...params };
  for (const [nom, valeur] of Object.entries(champs)) {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = nom;
    input.value = valeur;
    form.appendChild(input);
  }

  document.body.appendChild(form);
  form.submit();
}

/* ----------------------------------------------------------
   OPEN MODAL CONFIRM
---------------------------------------------------------- */
function openConfirm(type, callback) {
  const msg = confirms[type];

  if (!msg) return;

  document.getElementById("modal-confirm-msg").textContent = msg;
  confirmAction = callback;

  openModal("modal-confirm");
}

/* ----------------------------------------------------------
   VALIDATION BOUTON "OUI"
---------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
  const yesBtn = document.getElementById("confirm-yes");

  yesBtn.addEventListener("click", () => {
    closeModal("modal-confirm");

    if (typeof confirmAction === "function") {
      confirmAction();
    }
  });
});

/* ----------------------------------------------------------
   SUPPRESSION TÂCHE
---------------------------------------------------------- */
function SuppressionTache(id) {
  openConfirm("delete_task", () => {
    postAction("../php/delete/delete_task.php", { id: id });
  });
}

/* ----------------------------------------------------------
   SUPPRESSION TOUTES TÂCHES
---------------------------------------------------------- */
function SuppressionTacheAll() {
  openConfirm("delete_all_tasks", () => {
    postAction("../php/delete/delete_alltasks.php");
  });
}
/* ----------------------------------------------------------
   SUPPRESSION TOUTES TÂCHES DE COLONNE
---------------------------------------------------------- */
function SuppressionTachesCol(statut) {
  openConfirm("delete_taskscolumn", () => {
    postAction("../php/delete/delete_taskscolumn.php", { statut: statut });
  });
}

/* ----------------------------------------------------------
   SUPPRESSION CATÉGORIE
---------------------------------------------------------- */
function SuppressionCategorie(id) {
  openConfirm("delete_category", () => {
    postAction("../php/delete/delete_categorie.php", { id: id });
  });
}

/* ----------------------------------------------------------
   SUPPRESSION TOUTES CATÉGORIES
---------------------------------------------------------- */
function SuppressionCategorieAll() {
  openConfirm("delete_all_categories", () => {
    postAction("../php/delete/delete_allcategories.php");
  });
}

/* ----------------------------------------------------------
   SUPPRESSION OBJECTIF
---------------------------------------------------------- */
function SuppressionObjectif(titre) {
  openConfirm("delete_goal", () => {
    postAction("../php/delete/delete_goal.php", { titre: titre });
  });
}

/* ----------------------------------------------------------
   SUPPRESSION TOUS OBJECTIFS
---------------------------------------------------------- */
function SuppressionObjectifAll() {
  openConfirm("delete_all_goals", () => {
    postAction("../php/delete/delete_allgoals.php");
  });
}

/* ----------------------------------------------------------
   SUPPRESSION historique
---------------------------------------------------------- */
function SuppressionHistorique() {
  openConfirm("delete_hist", () => {
    postAction("../php/delete/delete_historique.php");
  });
}
/* ----------------------------------------------------------
   SUPPRESSION COMPTE
---------------------------------------------------------- */
function SuppressionCompte() {
  openConfirm("delete_account", () => {
    postAction("../php/delete/delete_account.php");
  });
}
