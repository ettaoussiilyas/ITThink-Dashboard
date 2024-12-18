-- Increase the size of mot_de_passe column to store hashed passwords
ALTER TABLE Utilisateurs MODIFY COLUMN mot_de_passe VARCHAR(255) NOT NULL;
