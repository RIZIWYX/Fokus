<?php
require_once  __DIR__ . '/../php/session/data_user.php';
require_once  __DIR__ . '/../php/session/categorie.php';
$cal_mode = 'agenda';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FOKUS — Agenda</title>
  <link rel="stylesheet" href="../css/global.css" />
  <link rel="stylesheet" href="../css/agenda.css" />
  <link rel="icon" type="image/png" href="../image/logo.png" />
  <script src="../js/calendar.js"></script>
  <script src="../js/main.js" defer></script>
  <script src="../js/errors.js" defer></script>
  <script src="../js/success.js" defer></script>
</head>
<body>

 <!-- ══════════════════════════════
         NAVBARRE
    ══════════════════════════════ -->
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
/* Calcul automatique du breadcrumb selon la page courante.
   On lit le nom du fichier PHP exécuté*/
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

<!-- ══════════════════════════════
     HEADER
══════════════════════════════ -->
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

    <div class="agenda-header-bar">
      <div>
        <h1 class="page-title">Agenda</h1>
        <p class="page-subtitle">Visualisez vos tâches par date limite</p>
      </div>
      <div class="cat-page-actions">
        <button class="btn-outline" onclick="openModal('modal-add-categorie')">+ Nouvelle catégorie</button>
        <button class="btn-gold" onclick="openModal('modal-add-task')">+ Nouvelle tâche</button>
      </div>
    </div>

    <div class="agenda-layout">

      <!-- Col 1 : Calendrier -->
      <div class="agenda-left">
        <?php include 'util_html_elem/calendar.php'; ?>
      </div>
 <!-- Col 2 : Tâches du jour -->
      <div class="agenda-day-col">
        <div class="agenda-side-card" id="agenda-day-detail">
          <div class="agenda-side-header gold" id="agenda-detail-title">📅 Tâches du jour</div>
          <div class="agenda-side-list" id="agenda-detail-list">
            <div style="color:var(--text-muted);font-size:0.78rem;padding:8px 0;text-align:center">
              Cliquez sur un jour du calendrier
            </div>
          </div>
        </div>
      </div>
 
      <!-- Col 3 : Deadlines proches -->
      <div class="agenda-middle">
        <div class="agenda-side-card">
          <div class="agenda-side-header danger">⚠️ Deadlines proches</div>
          <div class="agenda-side-list">
            <?php if (!empty($taches_urgentes)): ?>
              <?php foreach ($taches_urgentes as $t): ?>
                <div class="agenda-task-item">
                  <div class="agenda-task-left">
                    <span class="agenda-task-dot" style="background:<?= $STATUT_COLORS[$t['STATUT']] ?? 'var(--gold)' ?>"></span>
                    <div class="agenda-task-name"><?= htmlspecialchars($t['NOM_T']) ?></div>
                  </div>
                  <div class="agenda-task-right">
                    <span class="agenda-task-date">📅 <?= $t['LIMIT_DATE'] ?></span>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div style="color:var(--text-muted);font-size:0.78rem;padding:8px 0;text-align:center">
                Aucune deadline proche
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
 
<!-- Col 4 : Toutes les deadlines -->
<div class="agenda-right">
  <div class="agenda-side-card">
    <div class="agenda-side-header">📋 Toutes les deadlines</div>
    <div class="agenda-side-list">
      <?php if (empty($toutes_taches_agenda)): ?>
        <div style="color:var(--text-muted);font-size:0.78rem;padding:8px 0;text-align:center">
          Aucune deadline.
        </div>
      <?php else: ?>
        <?php
        $mois_affiche = '';
        foreach ($toutes_taches_agenda as $t):
          $mois_label = strtr(date('F Y', strtotime($t['LIMIT_DATE'])), $MOIS_FR);
        ?>
          <?php if ($mois_label !== $mois_affiche): ?>
            <div class="agenda-month-label"><?= $mois_label ?></div>
            <?php $mois_affiche = $mois_label; ?>
          <?php endif; ?>

          <div class="agenda-task-item">
            <div class="agenda-task-left">
              <span class="agenda-task-dot" style="background:<?= $STATUT_COLORS[$t['STATUT']] ?? 'var(--gold)' ?>"></span>
              <div class="agenda-task-name"><?= htmlspecialchars($t['NOM_T']) ?></div>
            </div>
            <div class="agenda-task-right">
              <span class="agenda-task-date">📅 <?= date('d/m', strtotime($t['LIMIT_DATE'])) ?></span>
            </div>
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

  <script>
    const toutesDeadlines = <?= json_encode($toutes_taches_agenda) ?>;
    const tachesParDate   = <?= json_encode($taches_par_date) ?>;
    const statutColors    = <?= json_encode($STATUT_COLORS) ?>;
    const statutLabels    = <?= json_encode($STATUT_LABELS) ?>;
    const MOIS_NOMS       = ['','Janvier','Février','Mars','Avril','Mai','Juin',
                             'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

    function afficherTachesJour(jour, mois, annee) {
      const dateKey = `${annee}-${String(mois).padStart(2,'0')}-${String(jour).padStart(2,'0')}`;
      const taches  = tachesParDate[dateKey] || [];
      const titre   = document.getElementById('agenda-detail-title');
      const liste   = document.getElementById('agenda-detail-list');

      titre.textContent = `📅 Tâches du ${jour} ${MOIS_NOMS[mois]}`;
      liste.innerHTML   = taches.length
        ? taches.map(t => `
            <div class="agenda-task-item">
              <div class="agenda-task-left">
                <span class="agenda-task-dot" style="background:${statutColors[t.STATUT] || 'var(--gold)'}"></span>
                <div class="agenda-task-name">${t.NOM_T}</div>
              </div>
              <span class="agenda-task-statut" style="background:${statutColors[t.STATUT]}22;color:${statutColors[t.STATUT]}">
                ${statutLabels[t.STATUT]}
              </span>
            </div>`).join('')
        : '<div style="color:var(--text-muted);font-size:0.78rem;padding:8px 0;text-align:center">Aucune tâche ce jour.</div>';
    }

    initCalendar({
      datesTaches:   <?= json_encode($dates_taches) ?>,
      titleId:       'cal-title',
      gridId:        'calendar-days',
      prevId:        'cal-prev',
      nextId:        'cal-next',
      dayCls:        'agenda-day',
      onDayClick:    (jour, mois, annee) => afficherTachesJour(jour, mois, annee),
    });
  </script>
    <?php include '../php/modals.php'; ?>
</body>
</html>