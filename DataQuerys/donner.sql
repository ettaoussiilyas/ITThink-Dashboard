#CREATE DATABASE ITThink
USE ITThink;
/*CREATE TABLE Utilisateurs(
	id_utilisateur INT AUTO_INCREMENT PRIMARY KEY ,
	nom_utilisateur VARCHAR(50) NOT NULL ,
	mot_de_passe VARCHAR(50) NOT NULL ,
	email VARCHAR(100) NOT NULL UNIQUE 
);*/

/*CREATE TABLE Categories(
	id_categorie INT AUTO_INCREMENT PRIMARY KEY ,
	nom_categorie VARCHAR(100) NOT NULL 
);*/

/*CREATE TABLE Sous_Categories(
	id_sous_categorie INT AUTO_INCREMENT PRIMARY KEY ,
	nom_sous_categorie VARCHAR(50) NOT NULL ,
	id_categorie INT NOT NULL ,
	FOREIGN KEY (id_categorie) REFERENCES Categories(id_categorie) ON DELETE CASCADE 
);*/

/*CREATE TABLE Projets(
	id_projet INT AUTO_INCREMENT PRIMARY KEY ,
	titre_project VARCHAR(100) NOT NULL ,
	descreption TEXT NOT NULL ,
	id_categorie INT NOT NULL ,
	id_sous_categorie INT NOT NULL ,
	id_utilisateur INT NOT NULL ,
	FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie) ON DELETE CASCADE ,
	FOREIGN KEY (id_sous_categorie) REFERENCES sous_categories(id_sous_categorie) ON DELETE CASCADE ,
	FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE 
	
);*/
/*CREATE TABLE Freelances(
	id_freelance INT AUTO_INCREMENT PRIMARY KEY ,
	nom_freelance VARCHAR(50) NOT NULL ,
	competences TEXT NOT NULL ,
	id_utilisateur INT NOT NULL ,
	FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE 
	
);*/
/*CREATE TABLE Offres(
	id_offre INT AUTO_INCREMENT PRIMARY KEY ,
	monatant DECIMAL (10,2) NOT NULL ,
	delai INT NOT NULL ,
	id_freelance INT NOT NULL ,
	id_projet INT NOT NULL ,
	FOREIGN KEY (id_freelance) REFERENCES freelances(id_freelance) ON DELETE CASCADE ,
	FOREIGN KEY (id_projet) REFERENCES projets(id_projet) ON DELETE CASCADE 
);*/
#USE itthink;
/*CREATE TABLE Temoignages(
	id_temoignage INT AUTO_INCREMENT PRIMARY KEY ,
	commentaire TEXT NOT NULL ,
	id_utilisateur INT NOT NULL ,
	FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE 
);*/
/*INSERT INTO Utilisateurs (nom_utilisateur, mot_de_passe, email)
VALUES
('salim', 'password_1', 'salim@gmail.com'),
('ilyas', 'password_2', 'ilyas@gmail.com'),
('ali', SHA1('password_3'), 'ali@gmail.com'),
('ahmade', SHA1('password_4'), 'ahmade@gmail.com'),
('sara', SHA1('password_5'), 'sara@gmail.com'),
('jihan', SHA1('password_6'), 'jihane@gmail.com');*/
#SELECT * FROM Utilisateurs;

#USE itthink;
/*INSERT INTO Categories (nom_categorie)
VALUES
('Ecom Site'),
('Calc App'),
('Web Scrapping');*/
#SELECT * FROM categories;
#DELETE FROM Categories WHERE id_categorie BETWEEN 4 AND 6;

#USE itthink;
/*INSERT INTO Sous_Categories (nom_sous_categorie, id_categorie)
VALUES
('Frontend Development', 1),
('Backend Development', 1),
('UI/UX Design', 2),
('iOS Development', 3),
('Android Development', 3);*/
#SELECT * FROM sous_categories;

#USE itthink;
/*INSERT INTO Projets (titre_project, descreption, id_categorie, id_sous_categorie, id_utilisateur)
VALUES
('Site Web E-commerce', 'Développement d’un site web pour une boutique en ligne', 1, 1, 1),
('Application Mobile', 'Développement d’une application mobile pour une entreprise', 3, 4, 2),
('Design de logo', 'Création d’un logo pour une startup', 2, 3, 3);*/

#USE itthink;
/*INSERT INTO Freelances (nom_freelance, competences, id_utilisateur)
VALUES
('Emma Lefevre', 'HTML, CSS, JavaScript, React', 1),
('Lucas Martin', 'Swift, iOS Development', 2),
('Sophie Riviere', 'Photoshop, Illustrator, UI Design', 3);*/

#USE itthink;
/*INSERT INTO Offres (monatant, delai, id_freelance, id_projet)
VALUES
(1500.00, 30, 1, 1),
(2500.00, 45, 2, 2),
(1000.00, 15, 3, 3);*/

#USE itthink;
/*INSERT INTO Temoignages (commentaire, id_utilisateur)
VALUES
('Excellente plateforme, très réactive et efficace.', 1),
('Les projets sont très intéressants, j’ai beaucoup appris.', 2),
('Une expérience agréable, j’ai pu travailler avec des clients très sympas.', 3);*/

#adding column

#ALTER TABLE projets ADD date_creation DATE ;
#UPDATE projets SET date_creation = '2024-01-01' WHERE date_creation IS NULL;
#SELECT * FROM projets ;


#update

#SELECT * FROM projets;
#UPDATE projets SET titre_project='Design Graphique' , descreption='design des choses' WHERE id_projet=3;
#update freelances SET nom_freelance= 'samid ghilan' WHERE id_freelance=1
#SELECT * FROM freelances
#UPDATE freelances SET nom_freelance= 'amal sghir' WHERE id_freelance=3

#delate

/*SELECT * FROM temoignages;
DELETE FROM temoignages WHERE commentaire LIKE '%Une%';
SELECT * FROM temoignages;*/

#jointure

/*SELECT * FROM projets INNER JOIN categories ON projets.id_categorie = categories.id_categorie WHERE categories.id_categorie=1  ;
SELECT * FROM projets INNER JOIN categories ON prjets.id_categorie = categories.id_categorie ;
SELECT * FROM categories;
SELECT * FROM projets;*/
