<?php require_once  __DIR__ . '/../php/session/data_user.php'; ?>
<?php require_once  __DIR__ . '/../php/session/categorie.php'; ?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FOKUS - Dashboard</title>
    <link rel="stylesheet" href="../css/global.css" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <link rel="icon" type="image/png" href="../image/logo.png" />
        <script src="../js/main.js" defer></script>
    <script src="../js/calendar.js"></script>
    <script src="../js/delete.js" defer></script>
    <script src="../js/success.js" defer></script>
    <script src="../js/errors.js" defer></script>
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
          <a href=" mes-taches.php" class="nav-item">
            <span class="nav-icon">📋</span> Mes Tâches
            <span class="nav-count"><?= $nb_taches ?></span>
          </a>
          <a href=" mes-categories.php" class="nav-item">
            <span class="nav-icon">🗂️</span> Mes Catégories
            <span class="nav-count"><?= $nb_categories ?></span>
          </a>
          <a href=" agenda.php" class="nav-item">
            <span class="nav-icon">📅</span> Agenda
          </a>
          <a href=" statistiques.php" class="nav-item">
            <span class="nav-icon">📊</span> Statistiques
          </a>
          <span class="nav-section-label">Compte</span>
          <a href=" profil.php" class="nav-item">
            <span class="nav-icon">👤</span> Mon Profil
          </a>
           <a href="historique.php" class="nav-item">
            <span class="nav-icon">📜</span> Historique
          </a>
          <a href=" parametres.php" class="nav-item">
            <span class="nav-icon">⚙️</span> Paramètres
          </a>
        </nav>
        <div class="sidebar-footer">
          <a href=" ../php/login/logout.php" class="nav-item">
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
    <main class="app-main" id="main-content">
      <div class="dashboard-header-bar">
        <div>
          <h1 class="page-title">Tableau de bord</h1>
          <p class="page-subtitle">Bienvenue ! Voici un aperçu de vos tâches.</p>
        </div>
        <div class="dashboard-actions">
          <button class="btn-outline" onclick="openModal('modal-add-categorie')">+ Nouvelle catégorie</button>
          <button class="btn-gold" onclick="openModal('modal-add-task')">+ Nouvelle tâche</button>
        </div>
      </div>

      <div class="dashboard-grid">
        <section class="tasks-overview">

          <!-- Tâches en cours -->
          <article class="task-column" data-column="inprog">
            <div class="task-column-header">
              <div class="task-column-title">
                <span class="task-col-dot dot-inprog"></span> Tâches en cours
              </div>
              <div class="task-col-pager" data-pager="inprog">
                <button type="button" class="task-col-pager-btn" data-dir="prev" aria-label="Page précédente">‹</button>
                <span class="task-col-pager-info"><span class="page-current">1</span>/<span class="page-total">1</span></span>
                <button type="button" class="task-col-pager-btn" data-dir="next" aria-label="Page suivante">›</button>
              </div>
              <span class="task-col-count"><?= count($taches_en_cours) ?> tâche<?= count($taches_en_cours) > 1 ? 's' : '' ?></span>
            </div>
            <div class="task-list">
              <?php
              $total = count($taches_en_cours);
              $compteur = 0;
              $page = 1;
              if ($total > 0):
                echo '<div class="task-page active" data-page="1">';
                for ($i = 0; $i < $total; $i++):
                  $tache       = $taches_en_cours[$i];
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
                      <button
                          class="task-action-btn urgent-btn<?= $is_urgent ? ' is-urgent' : '' ?>"
                          title="<?= $is_urgent ? 'Retirer urgent' : 'Marquer urgent' ?>"
                          onclick="toggleUrgent(this, <?= $tache['ID_T'] ?>)">
                          <?= $is_urgent ? '❕' : '❗' ?>
                      </button>
                      <button class="task-action-btn" title="Modifier" onclick="ouvrirEditTache(<?= $tache['ID_T'] ?>, <?= $t_nom ?>, <?= $t_cat ?>, <?= $t_date ?>, <?= $t_stat ?>, <?= $t_desc ?>)">✏️</button>
                      <button class="task-action-btn del" title="Supprimer" onclick="SuppressionTache(<?= $tache['ID_T'] ?>)">🗑</button>
                    </div>
                  </div>
              <?php
                  $compteur++;
                  if ($compteur === $nb_taches_par_page && $i < $total - 1):
                    $compteur = 0; $page++;
                    echo '</div><div class="task-page" data-page="' . $page . '">';
                  endif;
                endfor;
                echo '</div>';
              else: ?>
                <div class="task-empty">Aucune tâche en cours</div>
              <?php endif; ?>
            </div>
            
          </article>

          <!-- À faire -->
          <article class="task-column" data-column="todo">
            <div class="task-column-header">
              <div class="task-column-title">
                <span class="task-col-dot dot-todo"></span> À faire
              </div>
              <div class="task-col-pager" data-pager="todo">
                <button type="button" class="task-col-pager-btn" data-dir="prev" aria-label="Page précédente">‹</button>
                <span class="task-col-pager-info"><span class="page-current">1</span>/<span class="page-total">1</span></span>
                <button type="button" class="task-col-pager-btn" data-dir="next" aria-label="Page suivante">›</button>
              </div>
              <span class="task-col-count"><?= count($taches_a_faire) ?> tâche<?= count($taches_a_faire) > 1 ? 's' : '' ?></span>
            </div>
            <div class="task-list">
              <?php
              $total = count($taches_a_faire);
              $compteur = 0;
              $page = 1;
              if ($total > 0):
                echo '<div class="task-page active" data-page="1">';
                for ($i = 0; $i < $total; $i++):
                  $tache       = $taches_a_faire[$i];
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
                      <button
                          class="task-action-btn urgent-btn<?= $is_urgent ? ' is-urgent' : '' ?>"
                          title="<?= $is_urgent ? 'Retirer urgent' : 'Marquer urgent' ?>"
                          onclick="toggleUrgent(this, <?= $tache['ID_T'] ?>)">
                          <?= $is_urgent ? '❕' : '❗' ?>
                      </button>
                      <button class="task-action-btn" title="Modifier" onclick="ouvrirEditTache(<?= $tache['ID_T'] ?>, <?= $t_nom ?>, <?= $t_cat ?>, <?= $t_date ?>, <?= $t_stat ?>, <?= $t_desc ?>)">✏️</button>
                      <button class="task-action-btn del" title="Supprimer" onclick="SuppressionTache(<?= $tache['ID_T'] ?>)">🗑</button>
                    </div>
                  </div>
              <?php
                  $compteur++;
                  if ($compteur === $nb_taches_par_page && $i < $total - 1):
                    $compteur = 0; $page++;
                    echo '</div><div class="task-page" data-page="' . $page . '">';
                  endif;
                endfor;
                echo '</div>';
              else: ?>
                <div class="task-empty">Aucune tâche à faire</div>
              <?php endif; ?>
            </div>
            
          </article>

          <!-- Terminées -->
          <article class="task-column" data-column="done">
            <div class="task-column-header">
              <div class="task-column-title">
                <span class="task-col-dot dot-done"></span> Terminées
              </div>
              <div class="task-col-pager" data-pager="done">
                <button type="button" class="task-col-pager-btn" data-dir="prev" aria-label="Page précédente">‹</button>
                <span class="task-col-pager-info"><span class="page-current">1</span>/<span class="page-total">1</span></span>
                <button type="button" class="task-col-pager-btn" data-dir="next" aria-label="Page suivante">›</button>
              </div>
              <span class="task-col-count"><?= count($taches_termines) ?> tâche<?= count($taches_termines) > 1 ? 's' : '' ?></span>
            </div>
            <div class="task-list">
              <?php
              $total = count($taches_termines);
              $compteur = 0;
              $page = 1;
              if ($total > 0):
                echo '<div class="task-page active" data-page="1">';
                for ($i = 0; $i < $total; $i++):
                  $tache       = $taches_termines[$i];
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
                      <button
                          class="task-action-btn urgent-btn<?= $is_urgent ? ' is-urgent' : '' ?>"
                          title="<?= $is_urgent ? 'Retirer urgent' : 'Marquer urgent' ?>"
                          onclick="toggleUrgent(this, <?= $tache['ID_T'] ?>)">
                          <?= $is_urgent ? '❕' : '❗' ?>
                      </button>
                      <button class="task-action-btn" title="Modifier" onclick="ouvrirEditTache(<?= $tache['ID_T'] ?>, <?= $t_nom ?>, <?= $t_cat ?>, <?= $t_date ?>, <?= $t_stat ?>, <?= $t_desc ?>)">✏️</button>
                      <button class="task-action-btn del" title="Supprimer" onclick="SuppressionTache(<?= $tache['ID_T'] ?>)">🗑</button>
                    </div>
                  </div>
              <?php
                  $compteur++;
                  if ($compteur === $nb_taches_par_page && $i < $total - 1):
                    $compteur = 0; $page++;
                    echo '</div><div class="task-page" data-page="' . $page . '">';
                  endif;
                endfor;
                echo '</div>';
              else: ?>
                <div class="task-empty">Aucune tâche terminée</div>
              <?php endif; ?>
            </div>
            
          </article>

        </section>

        <aside class="dashboard-aside">

          <?php
          $dates_taches = array_values(array_filter(array_column($toutes_taches, 'LIMIT_DATE')));
            $cal_mode = 'dashboard';
            include 'util_html_elem/calendar.php';
          ?>

          <div class="card">
            <div class="section-title section-title_notif">Tâche la plus proche</div>
            <div class="notif-list">
              <?php
              $today     = date('Y-m-d');
              $prochaine = null;
              foreach ($toutes_taches as $t) {
                if ($t['LIMIT_DATE'] && $t['STATUT'] !== 'termine') {
                  if (!$prochaine || $t['LIMIT_DATE'] < $prochaine['LIMIT_DATE']) {
                    $prochaine = $t;
                  }
                }
              }

              if ($prochaine):
                $diff      = (new DateTime($today))->diff(new DateTime($prochaine['LIMIT_DATE']));
                $jours     = (int)$diff->format('%r%a');
                $is_retard = $jours < 0;
                $is_today  = $jours === 0;
                $is_urgent = !empty($prochaine['URGENT']);

                if ($is_retard)      $label = 'En retard de ' . abs($jours) . ' jour' . (abs($jours) > 1 ? 's' : '');
                elseif ($is_today)   $label = "Aujourd'hui !";
                elseif ($jours <= 3) $label = 'Dans ' . $jours . ' jour' . ($jours > 1 ? 's' : '');
                else                 $label = 'Dans ' . $jours . ' jours';
              ?>
                <div class="notif-item">
                  <div class="notif-icon">
                    <?= $is_retard ? '🔴' : ($is_today ? '⚠️' : ($is_urgent ? '⚡' : '📅')) ?>
                  </div>
                  <div style="flex:1; min-width:0;">
                    <div class="notif-text">
                      <strong>"<?= htmlspecialchars($prochaine['NOM_T']) ?>"</strong>
                    </div>
                    <?php if ($prochaine['NOM_CAT']): ?>
                      <div style="margin-top: 4px;">
                        <span class="task-item-cat" style="background-color:<?= htmlspecialchars($prochaine['COLOR_CAT']) ?>;">
                          <?= htmlspecialchars($prochaine['NOM_CAT']) ?>
                        </span>
                      </div>
                    <?php endif; ?>
                    <div class="notif-time" style="margin-top: 4px; color: <?= $is_retard ? 'var(--status-urgent)' : ($is_today ? '#ff9800' : 'var(--text-muted)') ?>">
                      📅 <?= $prochaine['LIMIT_DATE'] ?> - <?= $label ?>
                    </div>
                    <?php if ($is_urgent): ?>
                      <div style="font-size:0.75rem; color:var(--status-urgent); margin-top:2px;">🚨 Marquée urgente</div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php else: ?>
                <div class="notif-item">
                  <div class="notif-icon">✅</div>
                  <div class="notif-text">Aucune tâche à venir</div>
                </div>
              <?php endif; ?>
            </div>
          </div>

        </aside>
      </div>
    </main>
    </div>

    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
    <script>
    initCalendar({
      datesTaches: <?= json_encode($dates_taches) ?>,
      titleId: 'cal-title',
      gridId:  'calendar-days',
      prevId:  'cal-prev',
      nextId:  'cal-next',
    });
    </script>
        <?php include '../php/modals.php'; ?>
  </body>
</html>