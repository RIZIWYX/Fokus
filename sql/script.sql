DROP DATABASE IF EXISTS grainray;
CREATE DATABASE grainray;
USE grainray;

-- =========================================================
-- TABLE PERSONNE
-- =========================================================

CREATE TABLE PERSONNE (
    NOM_P           VARCHAR(20) PRIMARY KEY,
    PASSEWORD       VARCHAR(72),
    MAIL            VARCHAR(50),
    LAST_CONNEXION  DATE,
    SIGNIN_DATE     DATE,
    ROLE_P          VARCHAR(10),
    QUESTION        INT,
    REPONSE         VARCHAR(30),
    NB_TACHES       INT DEFAULT 5
) ENGINE=InnoDB;

-- =========================================================
-- TABLE OBJECTIF
-- =========================================================

CREATE TABLE OBJECTIF (
    TITRE_O         VARCHAR(200) PRIMARY KEY,
    UTILISATEUR_O   VARCHAR(20),

    FOREIGN KEY (UTILISATEUR_O)
        REFERENCES PERSONNE(NOM_P)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- TABLE CATEGORIE
-- =========================================================

CREATE TABLE CATEGORIE (
    ID_C            INT AUTO_INCREMENT PRIMARY KEY,
    UTILISATEUR_C   VARCHAR(20),
    NOM_C           VARCHAR(50),
    DESC_C          VARCHAR(200),
    COLOR_C         VARCHAR(20),

    FOREIGN KEY (UTILISATEUR_C)
        REFERENCES PERSONNE(NOM_P)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- TABLE TACHES
-- =========================================================

CREATE TABLE TACHES (
    ID_T            INT AUTO_INCREMENT PRIMARY KEY,
    UTILISATEUR_T   VARCHAR(20),
    NOM_T           VARCHAR(100),
    CATEGORIE_T     INT,
    DESC_T          VARCHAR(200),
    STATUT          VARCHAR(10),
    LIMIT_DATE      DATE,
    URGENT          BOOL,

    FOREIGN KEY (UTILISATEUR_T)
        REFERENCES PERSONNE(NOM_P)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (CATEGORIE_T)
        REFERENCES CATEGORIE(ID_C)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- TABLE PHOTO_PROFIL
-- =========================================================

CREATE TABLE PHOTO_PROFIL (
    ID_PHOTO        INT AUTO_INCREMENT PRIMARY KEY,
    UTILISATEUR     VARCHAR(20) NOT NULL,
    PHOTO           LONGTEXT,
    DATE_UPLOAD     DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (UTILISATEUR)
        REFERENCES PERSONNE(NOM_P)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- TABLE HISTORIQUE
-- =========================================================

CREATE TABLE HISTORIQUE (
    ID_T            INT AUTO_INCREMENT PRIMARY KEY,
    UTILISATEUR_T   VARCHAR(20),
    NOM_T           VARCHAR(100),
    CATEGORIE_T     VARCHAR(100),
    SAVE_DATE       DATE,

    FOREIGN KEY (UTILISATEUR_T)
        REFERENCES PERSONNE(NOM_P)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;