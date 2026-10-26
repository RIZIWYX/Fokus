<?php require_once __DIR__ . '/../php/session/data_user.php'; ?>
<?php require_once  __DIR__ . '/../php/session/categorie.php'; ?>

<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FOKUS - Mes Catégories</title>
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/mes-categories.css" />
    <link rel="icon" type="image/png" href="../image/logo.png" />
    <script src="../js/main.js" defer></script>
    <script src="../js/delete.js" defer></script>
    <script src="../js/errors.js" defer></script>
    <script src="../js/success.js" defer></script>
    <script src="../js/edit.js" defer></script>
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
        <?php if (empty($categories)): ?>
         <main class="app-main">
        <div class="cat-page-header">
          <div>
            <h1 class="page-title">Mes Catégories</h1>
            <p class="page-subtitle">Organisez vos tâches par catégories</p>
          </div>
        </div>
 <div class="empty-state">
    <div class="empty-state-icon">🗂️</div>
    <h2>Aucune catégorie pour l'instant</h2>
    <p>Créez une catégorie pour organiser vos tâches.</p>
    <button class="btn-gold" onclick="openModal('modal-add-categorie')">+ Créer ma première catégorie</button>
  </div>
        <?php else: ?>
       <main class="app-main">
        <div class="cat-page-header">
          <div>
            <h1 class="page-title">Mes Catégories</h1>
            <p class="page-subtitle">Organisez vos tâches par catégories</p>
          </div>
          <div class="cat-page-actions">
            <button class="btn-danger" onclick="SuppressionCategorieAll()">🗑 Tout supprimer</button>
            <button class="btn-outline" onclick="openModal('modal-add-categorie')">+ Nouvelle catégorie</button>
                <button class="btn-gold" onclick="openModal('modal-add-task')">+ Nouvelle tâche</button>
          </div>
        </div>
          <div class="cat-grid">
            <?php foreach ($categories as $cat):
              $sanitize  = fn($s) => str_replace(["\r\n", "\r", "\n"], ' ', trim($s ?? ''));
              $cat_nom   = htmlspecialchars(json_encode($sanitize($cat['NOM_C'])),   ENT_QUOTES);
              $cat_desc  = htmlspecialchars(json_encode($sanitize($cat['DESC_C'])),  ENT_QUOTES);
              $cat_color = htmlspecialchars(json_encode($sanitize($cat['COLOR_C'])), ENT_QUOTES);
            ?>
              <div class="cat-card">

                <div class="cat-card-header" style="border-top: 4px solid <?= htmlspecialchars($cat['COLOR_C']) ?>">
                  <div class="cat-card-title">
                    <span class="cat-color-dot" style="background: <?= htmlspecialchars($cat['COLOR_C']) ?>"></span>
                    <?= htmlspecialchars($cat['NOM_C']) ?>
                  </div>
                  <div class="cat-card-actions">
                    <button class="btn-col-action"
                      onclick="ouvrirEditCategorie(<?= $cat['ID_C'] ?>, <?= $cat_nom ?>, <?= $cat_desc ?>, <?= $cat_color ?>)"
                      title="Modifier">✏️</button>
                    <button class="btn-col-action del"
                      onclick="SuppressionCategorie(<?=$cat['ID_C'] ?>)"
                      title="Supprimer">🗑</button>
                  </div>
                </div>

                <?php if (empty($cat['DESC_C'])): ?>
                  <div class="cat-card-desc">Pas de description</div>
                <?php else: ?>
                <div class="cat-card-desc"><?= htmlspecialchars($cat['DESC_C']) ?></div>
                <?php endif; ?>
                <div class="cat-card-count">
                  <span class="cat-count-badge"><?= $cat['nb_taches'] ?> tâche<?= $cat['nb_taches'] > 1 ? 's' : '' ?></span>
                </div>

                <?php if (!empty($cat['taches'])): ?>
                  <div class="cat-task-list">
                    <?php foreach ($cat['taches'] as $tache): ?>
                      <div class="cat-task-item">
                        <span class="cat-task-dot status-<?= $tache['STATUT'] ?>"></span>
                        <span class="cat-task-name"><?= htmlspecialchars($tache['NOM_T']) ?></span>
                        <?php if ($tache['LIMIT_DATE']): ?>
                          <span class="cat-task-date"><?= $tache['LIMIT_DATE'] ?></span>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="cat-task-empty">Aucune tâche dans cette catégorie</div>
                <?php endif; ?>

              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </main>
    </div>

    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

    <?php include '../php/modals.php'; ?>
  </body>
</html>