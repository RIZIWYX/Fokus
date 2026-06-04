USE grainray;

-- =============================================
-- UTILISATEUR DE TEST
-- Mot de passe en clair : password
-- Mail : testeur@fokus.fr
-- pas de photo de profil initialemnt
-- =============================================
INSERT INTO PERSONNE (
    NOM_P, PASSEWORD, MAIL, LAST_CONNEXION, SIGNIN_DATE,
    ROLE_P, QUESTION, REPONSE, NB_TACHES
) VALUES (
    'testeur',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'testeur@fokus.fr',
    CURDATE(),
    '2026-01-01',
    'Member',
    1,
    'test',
    5
);

INSERT INTO PHOTO_PROFIL (UTILISATEUR, PHOTO, DATE_UPLOAD) 
VALUES (
'testeur',
NULL,
 CURDATE()
);

-- =============================================
-- CATÉGORIES
-- =============================================
INSERT INTO CATEGORIE (UTILISATEUR_C, NOM_C, DESC_C, COLOR_C) VALUES
('testeur', 'Etudes',        'Cours et révisions',         '#1e88e5'),
('testeur', 'Projet Web',    'Développement FOKUS',        '#fbc02d'),
('testeur', 'Sport',         'Activités sportives',        '#43a047'),
('testeur', 'Perso',         'Vie personnelle',            '#e53935'),
('testeur', 'Administratif', 'Documents et démarches',     '#8e24aa');

-- =============================================
-- OBJECTIFS
-- =============================================
INSERT INTO OBJECTIF (TITRE_O, UTILISATEUR_O) VALUES
('Valider mon année universitaire', 'testeur'),
('Terminer FOKUS avant juin',       'testeur'),
('Courir un semi-marathon',         'testeur'),
('Trouver un stage développeur',    'testeur'),
('Lire 10 livres tech cette année', 'testeur');

-- =============================================
-- TÂCHES EN COURS
-- =============================================
INSERT INTO TACHES (UTILISATEUR_T, NOM_T, CATEGORIE_T, DESC_T, STATUT, LIMIT_DATE, URGENT) VALUES
('testeur', 'Finir rapport de stage',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Etudes' AND UTILISATEUR_C='testeur'),
    'Rapport de 30 pages',       'en_cours', '2026-05-25', FALSE),
('testeur', 'Corriger bug session',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Projet Web' AND UTILISATEUR_C='testeur'),
    'Déconnexion aléatoire',     'en_cours', '2026-05-22', TRUE),
('testeur', 'Préparer soutenance',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Etudes' AND UTILISATEUR_C='testeur'),
    'Slides et démonstration',   'en_cours', '2026-05-24', TRUE),
('testeur', 'Séance musculation',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Sport' AND UTILISATEUR_C='testeur'),
    'Haut du corps',             'en_cours', '2026-05-23', FALSE),
('testeur', 'Backup base de données',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Projet Web' AND UTILISATEUR_C='testeur'),
    'Export SQL complet',        'en_cours', '2026-05-21', FALSE);

-- =============================================
-- TÂCHES À FAIRE
-- =============================================
INSERT INTO TACHES (UTILISATEUR_T, NOM_T, CATEGORIE_T, DESC_T, STATUT, LIMIT_DATE, URGENT) VALUES
('testeur', 'Réviser algorithmique',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Etudes' AND UTILISATEUR_C='testeur'),
    'Dijkstra et tri fusion',    'a_faire', '2026-05-28', FALSE),
('testeur', 'Ajouter pagination',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Projet Web' AND UTILISATEUR_C='testeur'),
    'Pagination dashboard',      'a_faire', '2026-05-29', FALSE),
('testeur', 'Payer le loyer',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Administratif' AND UTILISATEUR_C='testeur'),
    'Avant le 10 du mois',      'a_faire', '2026-05-22', TRUE),
('testeur', 'Acheter chaussures running',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Sport' AND UTILISATEUR_C='testeur'),
    'Préparation semi-marathon', 'a_faire', '2026-05-30', FALSE),
('testeur', 'Envoyer CV',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Administratif' AND UTILISATEUR_C='testeur'),
    '5 entreprises ciblées',     'a_faire', '2026-06-01', FALSE),
('testeur', 'Lire Clean Architecture',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Perso' AND UTILISATEUR_C='testeur'),
    'Chapitre 4',                'a_faire', NULL, FALSE),
('testeur', 'Préparer entretien',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Administratif' AND UTILISATEUR_C='testeur'),
    'Questions techniques',      'a_faire', '2026-05-26', TRUE),
('testeur', 'Faire les courses',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Perso' AND UTILISATEUR_C='testeur'),
    'Liste sur le frigo',        'a_faire', '2026-05-21', FALSE);

-- =============================================
-- TÂCHES TERMINÉES
-- =============================================
INSERT INTO TACHES (UTILISATEUR_T, NOM_T, CATEGORIE_T, DESC_T, STATUT, LIMIT_DATE, URGENT) VALUES
('testeur', 'Créer la base de données',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Projet Web' AND UTILISATEUR_C='testeur'),
    'Tables et relations',       'termine', '2026-04-10', FALSE),
('testeur', 'Implémenter le login',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Projet Web' AND UTILISATEUR_C='testeur'),
    'Connexion utilisateur',     'termine', '2026-04-15', FALSE),
('testeur', 'Examen français',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Etudes' AND UTILISATEUR_C='testeur'),
    'Note : 15/20',              'termine', '2026-04-20', FALSE),
('testeur', 'Inscription salle de sport',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Sport' AND UTILISATEUR_C='testeur'),
    'Abonnement annuel',         'termine', '2026-04-22', FALSE),
('testeur', 'Déclaration impôts',
    (SELECT ID_C FROM CATEGORIE WHERE NOM_C='Administratif' AND UTILISATEUR_C='testeur'),
    'Déclaration validée',       'termine', '2026-04-30', FALSE);

-- =============================================
-- HISTORIQUE (3 colonnes : NOM_T, CATEGORIE_T, SAVE_DATE)
-- Dates réalistes étalées sur avril-mai 2026
-- =============================================
INSERT INTO HISTORIQUE (UTILISATEUR_T, NOM_T, CATEGORIE_T, SAVE_DATE) VALUES
-- Avril 2026
('testeur', 'Créer la base de données',     'Projet Web',    '2026-04-02'),
('testeur', 'Implémenter le login',         'Projet Web',    '2026-04-05'),
('testeur', 'Inscription salle de sport',   'Sport',         '2026-04-08'),
('testeur', 'Examen français',              'Etudes',        '2026-04-10'),
('testeur', 'Déclaration impôts',           'Administratif', '2026-04-12'),
('testeur', 'Lire Clean Architecture',      'Perso',         '2026-04-15'),
('testeur', 'Finir rapport de stage',       'Etudes',        '2026-04-20'),
('testeur', 'Backup base de données',       'Projet Web',    '2026-04-22'),
('testeur', 'Corriger bug session',         'Projet Web',    '2026-04-28'),
('testeur', 'Envoyer CV',                   'Administratif', '2026-04-30'),
-- Mai 2026
('testeur', 'Préparer soutenance',          'Etudes',        '2026-05-03'),
('testeur', 'Séance musculation',           'Sport',         '2026-05-05'),
('testeur', 'Acheter chaussures running',   'Sport',         '2026-05-08'),
('testeur', 'Réviser algorithmique',        'Etudes',        '2026-05-10'),
('testeur', 'Ajouter pagination',           'Projet Web',    '2026-05-10'),
('testeur', 'Payer le loyer',               'Administratif', '2026-05-12'),
('testeur', 'Préparer entretien',           'Administratif', '2026-05-15'),
('testeur', 'Faire les courses',            'Perso',         '2026-05-17');