<?php
require_once __DIR__ . '/../php/session/data_user.php';

/* -- Récupération de l'historique trié par date DÉCROISSANTE -- */
try {
    $connexion = getConnexion();
    $stmt = $connexion->prepare(
        "SELECT ID_T, NOM_T, CATEGORIE_T, SAVE_DATE
         FROM HISTORIQUE
         WHERE UTILISATEUR_T = :user
         ORDER BY SAVE_DATE DESC, ID_T DESC"
    );
    $stmt->execute([':user' => $username]);
    $historique = $stmt->fetchAll();
    $connexion = null;
} catch (PDOException $e) {
    error_log("Erreur historique : " . $e->getMessage());
    $historique = [];
}

/* -- Regroupement par date -- */
$historique_par_date = [];
foreach ($historique as $h) {
    $historique_par_date[$h['SAVE_DATE']][] = $h;
}

/* -- Helper date en français -- */
function format_date_fr(string $date, array $MOIS_FR): string {
    $ts = strtotime($date);
    return date('d', $ts) . ' ' . strtr(date('F', $ts), $MOIS_FR) . ' ' . date('Y', $ts);
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FOKUS - Historique</title>
  <link rel="stylesheet" href="../css/global.css" />
  <link rel="stylesheet" href="../css/historique.css" />
  <link rel="icon" type="image/png" href="../image/logo.png" />
  <script src="../js/main.js" defer></script>
  <script src="../js/errors.js" defer></script>
  <script src="../js/success.js" defer></script>
  <script src="../js/delete.js" defer></script>
</head>
<body>

  <!-- ==============================
         NAVBARRE
    ============================== -->
    <div class="app-layout">
      <aside class="app-sidebar" id="sidebar">
        <a href="dashboard.php" class="sidebar-logo">
          <img src="../image/marque.png" alt="Fokus" class="header-image" />
        </a>
        <a href="profil.php">
          <div class="sidebar-user">
            <div class="avatar">
              <?php if ($photo_profil): ?>
              <img src="<?= htmlspecialchars($photo_profil) ?>"
                  alt="Photo de profil"
                  class="avatar-img" />
            <?php else: ?>
              <div><?= htmlspecialchars($first_letter) ?></div>
            <?php endif; ?>
            </div>
            <div class="user-info">
              <div class="user-name"><?= $username ?></div>
              <div class="user-role"><?= $role_p ?></div>
            </div>
          </div>
        </a>
        <nav class="sidebar-nav">
          <span class="nav-section-label">Menu</span>
          <a href="dashboard.php" class="nav-item">
            <span class="nav-icon">🏠</span> Accueil
          </a>
          <a href="mes-taches.php" class="nav-item">
            <span class="nav-icon">📋</span> Mes Tâches
            <span class="nav-count"><?= $nb_taches ?></span>
          </a>
          <a href="mes-categories.php" class="nav-item">
            <span class="nav-icon">🗂️</span> Mes Catégories
            <span class="nav-count"><?= $nb_categories ?></span>
          </a>
          <a href="agenda.php" class="nav-item">
            <span class="nav-icon">📅</span> Agenda
          </a>
          <a href="statistiques.php" class="nav-item">
            <span class="nav-icon">📊</span> Statistiques
          </a>
          <span class="nav-section-label">Compte</span>
          <a href="profil.php" class="nav-item">
            <span class="nav-icon">👤</span> Mon Profil
          </a>
           <a href="historique.php" class="nav-item">
            <span class="nav-icon">📜</span> Historique
          </a>
          <a href="parametres.php" class="nav-item">
            <span class="nav-icon">⚙️</span> Paramètres
          </a>
        </nav>
        <div class="sidebar-footer">
          <a href="../php/login/logout.php" class="nav-item">
            <span class="nav-icon">🚪</span> Déconnexion
          </a>
        </div>
     </aside>

     <?php
$page_courante = basename($_SERVER['PHP_SELF'], '.php');

$breadcrumbs = [
  'dashboard'      => ['Accueil'],
  'mes-taches'     => ['Accueil', 'Mes tâches'],
  'mes-categories' => ['Accueil', 'Mes catégories'],
  'agenda'         => ['Accueil', 'Agenda'],
  'statistiques'   => ['Accueil', 'Statistiques'],
  'profil'         => ['Accueil', 'Profil'],
  'parametres'     => ['Accueil', 'Paramètres'],
  'terms'          => ['Accueil', 'Conditions d\'utilisation'],
];

$chemin = $breadcrumbs[$page_courante] ?? ['Accueil', ucfirst($page_courante)];
?>

<!-- ==============================
     HEADER
============================== -->
<header class="app-header">
  <button class="menu-toggle" id="menu-toggle" aria-label="Menu">
    ☰
  </button>

  <nav class="breadcrumb">
    <?php foreach ($chemin as $i => $label): ?>
      <?php if ($i < count($chemin) - 1): ?>
        <a href="dashboard.php"><?= htmlspecialchars($label) ?></a>
        <span class="sep">›</span>
      <?php else: ?>
        <span class="current"><?= htmlspecialchars($label) ?></span>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>

  <div class="header-right">
    <div class="update-since">Dernière connexion : <?= $last_cnx ?></div>
    <!-- Notifications -->
    <button
      type="button"
      class="notif-btn"
      id="notif-btn"
      aria-label="Notifications"
      onclick="openModal('modal-notification')"
    >
      🔔
      <span class="notif-badge" aria-hidden="true"></span>
    </button>
    <a href="profil.php">
      <div class="avatar" style="width: 36px; height: 36px; font-size: 0.9rem">
      <?php if ($photo_profil): ?>
      <img src="<?= htmlspecialchars($photo_profil) ?>"
          alt="Photo de profil"
          class="avatar-img" />
    <?php else: ?>
      <div><?= htmlspecialchars($first_letter) ?></div>
    <?php endif; ?>
    </div>
    </a>
  </div>
</header>
  <main class="app-main">
    <div class="historique-header-bar">
      <div>
        <h1 class="page-title">Historique des tâches</h1>
        <p class="page-subtitle">Toutes les tâches créées, classées par date d'ajout</p>
      </div>
      <?php if (!empty($historique)): ?>
        <button type="button" class="btn-danger" onclick="SuppressionHistorique()">
            🗑 Vider l'historique
        </button>
        <!-- Formulaire caché, soumis par le JS -->
        <form id="form-delete-historique" method="POST" 
              action="../php/delete/delete_historique.php" style="display:none;">
        </form>
      <?php endif; ?>
    </div>
    <?php if (!empty($historique)): ?>
    <div class="historique-stats">
      <div class="stat-card">
        <div class="stat-num"><?= count($historique) ?></div>
        <div class="stat-label">Tâches au total</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?= count($historique_par_date) ?></div>
        <div class="stat-label">Jours d'activité</div>
      </div>
      <?php if (!empty($historique_par_date)): ?>
        <div class="stat-card">
          <div class="stat-num"><?= format_date_fr(array_key_last($historique_par_date), $MOIS_FR) ?></div>
          <div class="stat-label">Première tâche</div>
        </div>
      <?php endif; ?>
    </div>
     <?php endif; ?>
    <?php if (empty($historique)): ?>
  <div class="empty-state">
    <div class="empty-state-icon">📭</div>
    <h2>Aucun historique pour l'instant</h2>
    <p>Vos tâches créées apparaîtront ici, classées par date.</p>
    <a href="dashboard.php" class="btn-gold">Retour au tableau de bord</a>
  </div>
    <?php else: ?>

      <div class="historique-timeline">
        <?php foreach ($historique_par_date as $date => $taches): ?>
          <div class="historique-day">
            <div class="historique-day-header">
              <span class="historique-day-marker"></span>
              <h2 class="historique-day-title"><?= format_date_fr($date, $MOIS_FR) ?></h2>
              <span class="historique-day-count"><?= count($taches) ?> tâche<?= count($taches) > 1 ? 's' : '' ?></span>
            </div>

            <div class="historique-day-tasks">
              <?php foreach ($taches as $t): ?>
                <div class="historique-task">
                  <div class="historique-task-title"><?= htmlspecialchars($t['NOM_T']) ?></div>
                  <div class="historique-task-meta">
                    <?php if (!empty($t['CATEGORIE_T'])): ?>
                      <span class="historique-task-cat">🏷 <?= htmlspecialchars($t['CATEGORIE_T']) ?></span>
                    <?php else: ?>
                      <span class="historique-task-cat empty">Sans catégorie</span>
                    <?php endif; ?>
                    <span class="historique-task-date">📅 <?= date('d/m/Y', strtotime($t['SAVE_DATE'])) ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </main>

  <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
    <?php include __DIR__ . '/../php/modals.php'; ?>
</div> 
</body>
</html>