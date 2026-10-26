<?php require_once  __DIR__ . '/../php/session/data_user.php'; ?>
<?php require_once  __DIR__ . '/../php/session/statistiques.php'; ?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FOKUS - Profil</title>
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/profil.css" />
    <link rel="icon" type="image/png" href="../image/logo.png" />
    <script src="../js/main.js" defer></script>
    <script src="../js/edit.js" defer></script>
    <script src="../js/delete.js" defer></script>
    <script src="../js/success.js" defer></script>
    <script src="../js/errors.js" defer></script>
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
        <h1 class="page-title">Mon Profil</h1>
        <p class="page-subtitle">Gérez vos informations personnelles et vos objectifs</p>

        <div class="profil-grid">
          <div>
            <div class="profil-identity">
              <div class="profil-banner"></div>
              <div class="profil-avatar-wrap">
                <div class="profil-avatar" onclick="openModal('modal-photo-profil')" style="cursor:pointer;">
                  <?php if ($photo_profil): ?>
                    <img src="<?= htmlspecialchars($photo_profil) ?>"
                        alt="Photo de profil"
                        class="avatar-img" />
                  <?php else: ?>
                    <div><?= htmlspecialchars($first_letter) ?></div>
                  <?php endif; ?>
                </div>
                <div class="profil-name"><?= htmlspecialchars($username) ?></div>
                <div class="profil-email"><?= htmlspecialchars($mail) ?></div>
                <div class="profil-since">Membre depuis : <?= htmlspecialchars($signin_date) ?></div>
              </div>
              <button class="quick-action" onclick="openModal('modal-modify-profil')">
                <span class="qa-icon">✏️</span> Modifier le profil
              </button>
              <div class="profil-mini-stats">
                <div class="mini-stat">
                  <span class="mini-stat-value"><?= $nb_taches ?></span>
                  <span class="mini-stat-label">Tâches totales</span>
                </div>
                <div class="mini-stat">
                  <span class="mini-stat-value"><?= $nb_categories ?></span>
                  <span class="mini-stat-label">Catégories</span>
                </div>
                <div class="mini-stat">
                  <span class="mini-stat-value" style="color:var(--status-done)"><?= $nb_taches_termines ?></span>
                  <span class="mini-stat-label">Terminées</span>
                </div>
                <div class="mini-stat">
                  <span class="mini-stat-value"><?= $nb_taches_en_cours ?></span>
                  <span class="mini-stat-label">En cours</span>
                </div>
              </div>
            </div>
          </div>

          <div class="profil-right">
            <div class="reinit-card">
              <div class="reinit-text">
                <h2>Réinitialiser mes tâches</h2>
                <p>Cette action supprimera toutes vos tâches de façon irréversible.</p>
              </div>
              <button class="btn-danger" onclick="SuppressionTacheAll()">Réinitialiser</button>
            </div>
            <div class="objectifs-card">
              <div class="objectifs-header">
                <div class="objectifs-title">Mes Objectifs</div>
                <div style="display: flex; gap: 6px; align-items: center;">
                  <button class="btn-add-obj" onclick="openModal('modal-add-objectif')" title="Ajouter">＋</button>
                  <button class="btn-del-obj" onclick="SuppressionObjectifAll()" title="Supprimer tous">🗑</button>
                </div>
              </div>
              <div class="objectifs-list" id="objectifs-list">
                <?php if (empty($objectifs)): ?>
                  <div class="objectif-item">
                    <span class="obj-text" style="opacity:0.5">Aucun objectif pour l'instant.</span>
                  </div>
                <?php else: ?>
                  <?php foreach ($objectifs as $obj): ?>
                    <div class="objectif-item">
                      <span class="obj-bullet"></span>
                      <span class="obj-text"><?= htmlspecialchars($obj['TITRE_O']) ?></span>
                      <button class="obj-delete"
                        onclick="ouvrirEditObjectif('<?= htmlspecialchars($obj['TITRE_O'], ENT_QUOTES) ?>')"
                        title="Modifier">
                        ✏️
                      </button>
                      <button class="obj-delete"
                        onclick="SuppressionObjectif('<?= htmlspecialchars($obj['TITRE_O'], ENT_QUOTES) ?>')"
                        title="Supprimer">
                        ✕
                      </button>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
    <?php include '../php/modals.php'; ?>
  </body>
</html>