<?php require_once __DIR__ . '/../php/session/data_user.php'; ?>
<?php require_once __DIR__ . '/../php/session/categorie.php'; ?>
<?php require_once __DIR__ . '/../php/session/statistiques.php'; ?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FOKUS - Mes Tâches</title>
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <link rel="stylesheet" href="../css/mes-taches.css" />
    <link rel="icon" type="image/png" href="../image/logo.png" />
    <script src="../js/main.js" defer></script>
    <script src="../js/delete.js" defer></script>
    <script src="../js/edit.js" defer></script>
    <script src="../js/errors.js" defer></script>
    <script src="../js/success.js" defer></script>
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
                <img src="<?= htmlspecialchars($photo_profil) ?>" alt="Photo de profil" class="avatar-img" />
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
          <a href="dashboard.php" class="nav-item"><span class="nav-icon">🏠</span> Accueil</a>
          <a href="mes-taches.php" class="nav-item"><span class="nav-icon">📋</span> Mes Tâches <span class="nav-count"><?= $nb_taches ?></span></a>
          <a href="mes-categories.php" class="nav-item"><span class="nav-icon">🗂️</span> Mes Catégories <span class="nav-count"><?= $nb_categories ?></span></a>
          <a href="agenda.php" class="nav-item"><span class="nav-icon">📅</span> Agenda</a>
          <a href="statistiques.php" class="nav-item"><span class="nav-icon">📊</span> Statistiques</a>
          <span class="nav-section-label">Compte</span>
          <a href="profil.php" class="nav-item"><span class="nav-icon">👤</span> Mon Profil</a>
          <a href="historique.php" class="nav-item"><span class="nav-icon">📜</span> Historique</a>
          <a href="parametres.php" class="nav-item"><span class="nav-icon">⚙️</span> Paramètres</a>
        </nav>
        <div class="sidebar-footer">
          <a href="../php/login/logout.php" class="nav-item"><span class="nav-icon">🚪</span> Déconnexion</a>
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
        'historique'     => ['Accueil', 'Historique'],
        'terms'          => ['Accueil', 'Conditions d\'utilisation'],
      ];
      $chemin = $breadcrumbs[$page_courante] ?? ['Accueil', ucfirst($page_courante)];
      ?>

      <!-- ==============================
           HEADER
      ============================== -->
      <header class="app-header">
        <button class="menu-toggle" id="menu-toggle" aria-label="Menu">☰</button>
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
          <button type="button" class="notif-btn" id="notif-btn" aria-label="Notifications" onclick="openModal('modal-notification')">
            🔔 <span class="notif-badge" aria-hidden="true"></span>
          </button>
          <a href="profil.php">
            <div class="avatar" style="width: 36px; height: 36px; font-size: 0.9rem">
              <?php if ($photo_profil): ?>
                <img src="<?= htmlspecialchars($photo_profil) ?>" alt="Photo de profil" class="avatar-img" />
              <?php else: ?>
                <div><?= htmlspecialchars($first_letter) ?></div>
              <?php endif; ?>
            </div>
          </a>
        </div>
      </header>

      <!-- ==============================
           CONTENU PRINCIPAL
      ============================== -->
      <?php if (empty($toutes_taches)): ?>

        <main class="app-main">
          <div class="taches-header-bar">
            <div>
              <h1 class="page-title">Mes Tâches</h1>
              <p class="page-subtitle">Organisez et suivez vos tâches par statut</p>
            </div>
          </div>
          <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h2>Aucune tâche pour l'instant</h2>
            <p>Créez votre première tâche pour commencer à vous organiser.</p>
            <button class="btn-gold" onclick="openModal('modal-add-task')">+ Créer ma première tâche</button>
          </div>
        </main>

      <?php else: ?>

        <main class="app-main">
<div class="taches-header-bar">
  <!-- Gauche : titre -->
  <div>
    <h1 class="page-title">Mes Tâches</h1>
    <p class="page-subtitle">Organisez et suivez vos tâches par statut</p>
  </div>

  <!-- Centre : filtres + recherche -->
  <div class="taches-toolbar-center">
    <div class="filter-tabs" role="tablist">
      <button class="filter-tab active" data-filter="all">Toutes</button>
      <button class="filter-tab" data-filter="urgent">Urgentes</button>
      <button class="filter-tab" data-filter="en_cours">En cours</button>
      <button class="filter-tab" data-filter="a_faire">À faire</button>
      <button class="filter-tab" data-filter="termine">Terminées</button>
    </div>
    <div class="search-input-wrap">
      <span class="search-icon">🔍</span>
      <input type="search" class="search-input" id="search-tasks"
        placeholder="Rechercher..." aria-label="Rechercher une tâche" />
    </div>
  </div>

  <!-- Droite : boutons -->
  <div class="taches-toolbar-right">
    <button class="btn-danger" onclick="SuppressionTacheAll()">🗑 Tout supprimer</button>
    <button class="btn-outline" onclick="openModal('modal-add-categorie')">+ Nouvelle catégorie</button>
    <button class="btn-gold" onclick="openModal('modal-add-task')">+ Nouvelle tâche</button>
  </div>
</div>

          <!-- Colonnes Kanban -->
          <div class="kanban-grid" id="kanban-grid">

            <!-- Colonne : En cours -->
            <div class="kanban-col task-column" data-col="en_cours">
              <div class="task-column-header">
                <div class="task-column-title">
                  <span class="task-col-dot dot-inprog"></span> En cours
                </div>
                <div class="kanban-col-actions">
                  <span class="task-col-count"><?= count($taches_en_cours) ?> tâche<?= count($taches_en_cours) > 1 ? 's' : '' ?></span>
                  <button class="btn-col-action del" title="Vider la colonne" onclick="SuppressionTachesCol('en_cours')">🗑</button>
                </div>
              </div>
              <div class="task-list">
                <?php foreach ($taches_en_cours as $tache):
                  $is_urgent   = !empty($tache['URGENT']);
                  $t_nom       = htmlspecialchars(json_encode($tache['NOM_T']), ENT_QUOTES);
                  $t_cat       = htmlspecialchars(json_encode($tache['CATEGORIE_T']), ENT_QUOTES);
                  $t_date      = htmlspecialchars(json_encode($tache['LIMIT_DATE']), ENT_QUOTES);
                  $t_stat      = htmlspecialchars(json_encode(trim($tache['STATUT'])), ENT_QUOTES);
                  $t_desc      = htmlspecialchars(json_encode($tache['DESC_T'] ?? ''), ENT_QUOTES);
                  $t_nom_cat   = htmlspecialchars($tache['NOM_CAT'] ?? '');
                  $t_color_cat = htmlspecialchars($tache['COLOR_CAT'] ?? '');
                ?>
                  <div class="task-item<?= $is_urgent ? ' urgent' : '' ?>"
                       data-id="<?= $tache['ID_T'] ?>"
                       data-nom="<?= htmlspecialchars($tache['NOM_T']) ?>"
                       data-desc="<?= htmlspecialchars($tache['DESC_T'] ?? '') ?>"
                       data-statut="<?= htmlspecialchars(trim($tache['STATUT'])) ?>"
                       data-date="<?= htmlspecialchars($tache['LIMIT_DATE'] ?? '') ?>"
                       data-cat="<?= $t_nom_cat ?>"
                       data-cat-color="<?= $t_color_cat ?>"
                       data-urgent="<?= $is_urgent ? '1' : '0' ?>"
                       onclick="ouvrirViewTache(this)">
                    <div class="task-item-body">
                      <div class="task-item-title"><?= htmlspecialchars($tache['NOM_T']) ?></div>
                      <div class="task-item-meta">
                        <?php if ($tache['NOM_CAT']): ?>
                          <span class="task-item-cat" style="background-color: <?= $t_color_cat ?>;"><?= $t_nom_cat ?></span>
                        <?php endif; ?>
                        <?php if ($tache['LIMIT_DATE']): ?>
                          <span class="task-item-date">📅 <?= $tache['LIMIT_DATE'] ?></span>
                        <?php endif; ?>
                        <span class="badge badge-inprog">En cours</span>
                      </div>
                    </div>
                    <div class="task-item-actions" onclick="event.stopPropagation()">
                      <button class="task-action-btn urgent-btn<?= $is_urgent ? ' is-urgent' : '' ?>"
                              title="<?= $is_urgent ? 'Retirer urgent' : 'Marquer urgent' ?>"
                              onclick="toggleUrgent(this, <?= $tache['ID_T'] ?>)">
                        <?= $is_urgent ? '❕' : '❗' ?>
                      </button>
                      <button class="task-action-btn" title="Modifier" onclick="ouvrirEditTache(<?= $tache['ID_T'] ?>, <?= $t_nom ?>, <?= $t_cat ?>, <?= $t_date ?>, <?= $t_stat ?>, <?= $t_desc ?>)">✏️</button>
                      <button class="task-action-btn del" title="Supprimer" onclick="SuppressionTache(<?= $tache['ID_T'] ?>)">🗑</button>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (empty($taches_en_cours)): ?>
                  <div class="task-empty">Aucune tâche en cours</div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Colonne : À faire -->
            <div class="kanban-col task-column" data-col="a_faire">
              <div class="task-column-header">
                <div class="task-column-title">
                  <span class="task-col-dot dot-todo"></span> À faire
                </div>
                <div class="kanban-col-actions">
                  <span class="task-col-count"><?= count($taches_a_faire) ?> tâche<?= count($taches_a_faire) > 1 ? 's' : '' ?></span>
                  <button class="btn-col-action del" title="Vider la colonne" onclick="SuppressionTachesCol('a_faire')">🗑</button>
                </div>
              </div>
              <div class="task-list">
                <?php foreach ($taches_a_faire as $tache):
                  $is_urgent   = !empty($tache['URGENT']);
                  $t_nom       = htmlspecialchars(json_encode($tache['NOM_T']), ENT_QUOTES);
                  $t_cat       = htmlspecialchars(json_encode($tache['CATEGORIE_T']), ENT_QUOTES);
                  $t_date      = htmlspecialchars(json_encode($tache['LIMIT_DATE']), ENT_QUOTES);
                  $t_stat      = htmlspecialchars(json_encode(trim($tache['STATUT'])), ENT_QUOTES);
                  $t_desc      = htmlspecialchars(json_encode($tache['DESC_T'] ?? ''), ENT_QUOTES);
                  $t_nom_cat   = htmlspecialchars($tache['NOM_CAT'] ?? '');
                  $t_color_cat = htmlspecialchars($tache['COLOR_CAT'] ?? '');
                ?>
                  <div class="task-item<?= $is_urgent ? ' urgent' : '' ?>"
                       data-id="<?= $tache['ID_T'] ?>"
                       data-nom="<?= htmlspecialchars($tache['NOM_T']) ?>"
                       data-desc="<?= htmlspecialchars($tache['DESC_T'] ?? '') ?>"
                       data-statut="<?= htmlspecialchars(trim($tache['STATUT'])) ?>"
                       data-date="<?= htmlspecialchars($tache['LIMIT_DATE'] ?? '') ?>"
                       data-cat="<?= $t_nom_cat ?>"
                       data-cat-color="<?= $t_color_cat ?>"
                       data-urgent="<?= $is_urgent ? '1' : '0' ?>"
                       onclick="ouvrirViewTache(this)">
                    <div class="task-item-body">
                      <div class="task-item-title"><?= htmlspecialchars($tache['NOM_T']) ?></div>
                      <div class="task-item-meta">
                        <?php if ($tache['NOM_CAT']): ?>
                          <span class="task-item-cat" style="background-color: <?= $t_color_cat ?>;"><?= $t_nom_cat ?></span>
                        <?php endif; ?>
                        <?php if ($tache['LIMIT_DATE']): ?>
                          <span class="task-item-date">📅 <?= $tache['LIMIT_DATE'] ?></span>
                        <?php endif; ?>
                        <span class="badge badge-todo">À faire</span>
                      </div>
                    </div>
                    <div class="task-item-actions" onclick="event.stopPropagation()">
                      <button class="task-action-btn urgent-btn<?= $is_urgent ? ' is-urgent' : '' ?>"
                              title="<?= $is_urgent ? 'Retirer urgent' : 'Marquer urgent' ?>"
                              onclick="toggleUrgent(this, <?= $tache['ID_T'] ?>)">
                        <?= $is_urgent ? '❕' : '❗' ?>
                      </button>
                      <button class="task-action-btn" title="Modifier" onclick="ouvrirEditTache(<?= $tache['ID_T'] ?>, <?= $t_nom ?>, <?= $t_cat ?>, <?= $t_date ?>, <?= $t_stat ?>, <?= $t_desc ?>)">✏️</button>
                      <button class="task-action-btn del" title="Supprimer" onclick="SuppressionTache(<?= $tache['ID_T'] ?>)">🗑</button>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (empty($taches_a_faire)): ?>
                  <div class="task-empty">Aucune tâche à faire</div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Colonne : Terminées -->
            <div class="kanban-col task-column" data-col="termine">
              <div class="task-column-header">
                <div class="task-column-title">
                  <span class="task-col-dot dot-done"></span> Terminées
                </div>
                <div class="kanban-col-actions">
                  <span class="task-col-count"><?= count($taches_termines) ?> tâche<?= count($taches_termines) > 1 ? 's' : '' ?></span>
                  <button class="btn-col-action del" title="Vider la colonne" onclick="SuppressionTachesCol('termine')">🗑</button>
                </div>
              </div>
              <div class="task-list">
                <?php foreach ($taches_termines as $tache):
                  $is_urgent   = !empty($tache['URGENT']);
                  $t_nom       = htmlspecialchars(json_encode($tache['NOM_T']), ENT_QUOTES);
                  $t_cat       = htmlspecialchars(json_encode($tache['CATEGORIE_T']), ENT_QUOTES);
                  $t_date      = htmlspecialchars(json_encode($tache['LIMIT_DATE']), ENT_QUOTES);
                  $t_stat      = htmlspecialchars(json_encode(trim($tache['STATUT'])), ENT_QUOTES);
                  $t_desc      = htmlspecialchars(json_encode($tache['DESC_T'] ?? ''), ENT_QUOTES);
                  $t_nom_cat   = htmlspecialchars($tache['NOM_CAT'] ?? '');
                  $t_color_cat = htmlspecialchars($tache['COLOR_CAT'] ?? '');
                ?>
                  <div class="task-item<?= $is_urgent ? ' urgent' : '' ?>"
                       data-id="<?= $tache['ID_T'] ?>"
                       data-nom="<?= htmlspecialchars($tache['NOM_T']) ?>"
                       data-desc="<?= htmlspecialchars($tache['DESC_T'] ?? '') ?>"
                       data-statut="<?= htmlspecialchars(trim($tache['STATUT'])) ?>"
                       data-date="<?= htmlspecialchars($tache['LIMIT_DATE'] ?? '') ?>"
                       data-cat="<?= $t_nom_cat ?>"
                       data-cat-color="<?= $t_color_cat ?>"
                       data-urgent="<?= $is_urgent ? '1' : '0' ?>"
                       onclick="ouvrirViewTache(this)">
                    <div class="task-item-body">
                      <div class="task-item-title"><?= htmlspecialchars($tache['NOM_T']) ?></div>
                      <div class="task-item-meta">
                        <?php if ($tache['NOM_CAT']): ?>
                          <span class="task-item-cat" style="background-color: <?= $t_color_cat ?>;"><?= $t_nom_cat ?></span>
                        <?php endif; ?>
                        <?php if ($tache['LIMIT_DATE']): ?>
                          <span class="task-item-date">📅 <?= $tache['LIMIT_DATE'] ?></span>
                        <?php endif; ?>
                        <span class="badge badge-done">Terminée</span>
                      </div>
                    </div>
                    <div class="task-item-actions" onclick="event.stopPropagation()">
                      <button class="task-action-btn urgent-btn<?= $is_urgent ? ' is-urgent' : '' ?>"
                              title="<?= $is_urgent ? 'Retirer urgent' : 'Marquer urgent' ?>"
                              onclick="toggleUrgent(this, <?= $tache['ID_T'] ?>)">
                        <?= $is_urgent ? '❕' : '❗' ?>
                      </button>
                      <button class="task-action-btn" title="Modifier" onclick="ouvrirEditTache(<?= $tache['ID_T'] ?>, <?= $t_nom ?>, <?= $t_cat ?>, <?= $t_date ?>, <?= $t_stat ?>, <?= $t_desc ?>)">✏️</button>
                      <button class="task-action-btn del" title="Supprimer" onclick="SuppressionTache(<?= $tache['ID_T'] ?>)">🗑</button>
                    </div>
                  </div>
                <?php endforeach; ?>
                <?php if (empty($taches_termines)): ?>
                  <div class="task-empty">Aucune tâche terminée</div>
                <?php endif; ?>
              </div>
            </div>

          </div><!-- /.kanban-grid -->

      <?php endif; ?>
        </main>
    </div><!-- /.app-layout -->

    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
    <?php include '../php/modals.php'; ?>
  </body>
</html>