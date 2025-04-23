# 🧝‍♀️ Throne of Miniatures

Bienvenue sur le dépôt officiel du projet **Throne of Miniatures**, une boutique en ligne dédiée aux passionné·e·s de figurines imprimées en 3D, peintes à la main et inspirées d’univers gothiques ou fantasy.  
Ce projet a été réalisé dans le cadre de la formation **Développeur Web Fullstack** dispensée par le **GRETA de Vannes**.

🔗 **Accès rapide :**  
- 🌐 [Voir le site en ligne](https://stagiaires-kercode9.greta-bretagne-sud.org/delphine-marchisone/Figurine/home)  
- 📁 [Accéder au repository GitHub](https://github.com/Delphine5692/Figurine.git)

---

## 🚀 Fonctionnalités principales

- 🛒 Vente de figurines imprimées et peintes
- 📱 Interface responsive adaptée aux mobiles
- 🧾 Ajout de commentaires et d'avis clients
- ✍️ Section blog sur la peinture et les techniques
- 👤 Espace personnel (profil utilisateur)
- ⚙️ Panneau d'administration
- 📦 Suivi et gestion des commandes

---

## 🛠️ Technologies utilisées

- **Frontend** : HTML, SCSS, JavaScript
- **Backend** : PHP (MVC)
- **Base de données** : MySQL
- **Outils** : Composer

---

## 📦 Installation du projet

### 1. Cloner le projet
```bash
git clone https://github.com/Delphine5692/Figurine.git
```

### 2. Installer les dépendances PHP
Assurez-vous d’avoir [Composer](https://getcomposer.org/) installé, puis lancez :
```bash
composer install
```

### 3. Configuration de l’environnement
Renommer le fichier `.env.exemple` en `.env` puis complètez-le avec vos identifiants :
```env
DB_NAME = nom_de_la_base
DB_HOST = localhost
DB_PORT = 3306
DB_LOGIN = utilisateur
DB_PASSWORD = mot_de_passe
```

### 4. Importer la base de données
Le fichier `figurine.sql` est disponible dans le dossier `Ressources/`.  
Vous pouvez l’importer via phpMyAdmin ou un outil MySQL équivalent.

---

## 👥 Comptes de test

### Utilisateur standard
- **Email** : utilisateur@figurine.com  
- **Mot de passe** : figurine

### Administrateur
- **Email** : admin@figurine.com  
- **Mot de passe** : figurine
Pour accèder au panel admin : /admin dans la barre du site

---

## 📄 Licence

Ce projet est à visée pédagogique et non commercial.  
Merci de respecter le travail réalisé en ne le réutilisant pas sans autorisation.
