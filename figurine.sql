CREATE TABLE UTILISATEUR(
   id_utilisateur INT AUTO_INCREMENT,
   nom VARCHAR(50)  NOT NULL,
   prenom VARCHAR(50)  NOT NULL,
   mail VARCHAR(100)  NOT NULL,
   date_creation DATE,
   mdp VARCHAR(100)  NOT NULL,
   adresse VARCHAR(50) ,
   PRIMARY KEY(id_utilisateur),
   UNIQUE(mail)
);

CREATE TABLE PRODUIT(
   id_produit INT AUTO_INCREMENT,
   nom VARCHAR(50)  NOT NULL,
   prix DECIMAL(19,4) NOT NULL,
   description TEXT NOT NULL,
   taille DECIMAL(15,2)  ,
   image_1 VARCHAR(50)  NOT NULL,
   image_2 VARCHAR(50) ,
   image_3 VARCHAR(50) ,
   date_produit DATE,
   PRIMARY KEY(id_produit)
);

CREATE TABLE CATEGORIE(
   id_categorie INT AUTO_INCREMENT,
   nom VARCHAR(30)  NOT NULL,
   PRIMARY KEY(id_categorie)
);

CREATE TABLE COMMANDE(
   id_commande INT AUTO_INCREMENT,
   date_commande DATETIME,
   statut ENUM('en cours','prepartion','expedier','livrer'),
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_commande),
   FOREIGN KEY(id_utilisateur) REFERENCES UTILISATEUR(id_utilisateur)
);

CREATE TABLE ARTICLE(
   id_article INT AUTO_INCREMENT,
   titre VARCHAR(50)  NOT NULL,
   image VARCHAR(50) ,
   description VARCHAR(50)  NOT NULL,
   date_publication DATETIME,
   PRIMARY KEY(id_article)
);

CREATE TABLE COMMENTAIRE(
   id_commentaire INT AUTO_INCREMENT,
   msg_blog TEXT,
   date_commentaire DATETIME,
   id_article INT NOT NULL,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_commentaire),
   FOREIGN KEY(id_article) REFERENCES ARTICLE(id_article),
   FOREIGN KEY(id_utilisateur) REFERENCES UTILISATEUR(id_utilisateur)
);

CREATE TABLE AVIS(
   id_avis INT AUTO_INCREMENT,
   msg_avis TEXT,
   id_produit INT NOT NULL,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_avis),
   FOREIGN KEY(id_produit) REFERENCES PRODUIT(id_produit),
   FOREIGN KEY(id_utilisateur) REFERENCES UTILISATEUR(id_utilisateur)
);

CREATE TABLE produit_categorie(
   id_produit INT,
   id_categorie INT,
   PRIMARY KEY(id_produit, id_categorie),
   FOREIGN KEY(id_produit) REFERENCES PRODUIT(id_produit),
   FOREIGN KEY(id_categorie) REFERENCES CATEGORIE(id_categorie)
);

CREATE TABLE produit_commande(
   id_produit INT,
   id_commande INT,
   PRIMARY KEY(id_produit, id_commande),
   FOREIGN KEY(id_produit) REFERENCES PRODUIT(id_produit),
   FOREIGN KEY(id_commande) REFERENCES COMMANDE(id_commande)
);
