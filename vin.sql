DROP DATABASE CaveAvin;
CREATE DATABASE CaveAvin;
USE CaveAvin;

CREATE TABLE IF NOT EXISTS LOGIN(
     eMail VARCHAR(100) UNIQUE NOT NULL,
     nomLogin VARCHAR(100) UNIQUE NOT NULL,
     passWord VARCHAR(100) NOT NULL,
     uuid_field VARCHAR(255) UNIQUE DEFAULT(uuid()),
     PRIMARY KEY (eMail)
);

CREATE TABLE IF NOT EXISTS CLIENT(
     idClient INT NOT NULL AUTO_INCREMENT,
     nomClient VARCHAR(100) NOT NULL,
     prenomClient VARCHAR(100) NOT NULL,
     adresseCLient VARCHAR(100) NOT NULL,
     telephone VARCHAR(32) NOT NULL,
     fk_eMail VARCHAR(100) NOT NULL,
     PRIMARY KEY (idClient),
     FOREIGN KEY(fk_eMail) REFERENCES LOGIN(eMail)
);


CREATE TABLE IF NOT EXISTS COMMANDE(
     idCommande INT NOT NULL AUTO_INCREMENT,
     dateCommande DATE NOT NULL,
     prixTotale VARCHAR(100) NOT NULL,
     fk_idClient INT NOT NULL,
     PRIMARY KEY (idCommande),
     FOREIGN KEY(fk_idClient) REFERENCES CLIENT(idClient)
);

CREATE TABLE IF NOT EXISTS CEPAGE(
     idCepage INT NOT NULL AUTO_INCREMENT,
     nomCepage VARCHAR(100) NOT NULL,
     PRIMARY KEY (idCepage)
);

CREATE TABLE IF NOT EXISTS COULEUR(
     idCouleur INT NOT NULL AUTO_INCREMENT,
     nomCouleur VARCHAR(100) NOT NULL,
     PRIMARY KEY (idCouleur)
);

CREATE TABLE IF NOT EXISTS VILLE(
     idVille INT NOT NULL AUTO_INCREMENT,
     nomVille VARCHAR(100) NOT NULL,
     cpville VARCHAR(100) NOT NULL,
     PRIMARY KEY (idVille)
);

CREATE TABLE IF NOT EXISTS CAVE(
     idCave INT NOT NULL AUTO_INCREMENT,
     nomCave VARCHAR(100) NOT NULL,
     fk_idVille INT NOT NULL,
     PRIMARY KEY (idCave),
     FOREIGN KEY(fk_idVille) REFERENCES VILLE(idVille)
);

CREATE TABLE IF NOT EXISTS VIN(
     idVin INT NOT NULL AUTO_INCREMENT,
     millesime VARCHAR(100) NOT NULL,
     degresAlcool INT NOT NULL,
     nomVin VARCHAR(100) NOT NULL,
     fk_idCepage INT NOT NULL,
     fk_idCouleur INT NOT NULL,
     PRIMARY KEY (idVin),
     FOREIGN KEY(fk_idCepage) REFERENCES CEPAGE(idCepage),
     FOREIGN KEY(fk_idCouleur) REFERENCES COULEUR(idCouleur)
);

CREATE TABLE IF NOT EXISTS BOUTEILLE(
     idBouteille INT NOT NULL AUTO_INCREMENT,
     prixUnitaire VARCHAR(100) NOT NULL,
     nomBouteille VARCHAR(100) NOT NULL,
     fk_idVin INT NOT NULL,
     fk_idCave INT NOT NULL,
     PRIMARY KEY (idBouteille),
     FOREIGN KEY(fk_idCave) REFERENCES CAVE(idCave),
     FOREIGN KEY(fk_idVin) REFERENCES VIN(idVin)
);

CREATE TABLE IF NOT EXISTS TAILLE(
     idTaille INT NOT NULL AUTO_INCREMENT,
     nomTaille VARCHAR(100) NOT NULL,
     quantite VARCHAR(100) NOT NULL,
     PRIMARY KEY (idTaille)
);

CREATE TABLE IF NOT EXISTS DOMAINE(
     idDomaine INT NOT NULL AUTO_INCREMENT,
     nomDomaine VARCHAR(100) NOT NULL,
     PRIMARY KEY (idDomaine)
);

CREATE TABLE IF NOT EXISTS COMMANDE_CONCERNE_BOUTEILLE(
     idComdecoul INT NOT NULL AUTO_INCREMENT,
     fk_idCommande INT NOT NULL,
     fk_idBouteille INT NOT NULL,
     PRIMARY KEY (idComdecoul),
     FOREIGN KEY(fk_idCommande) REFERENCES COMMANDE(idCommande),
     FOREIGN KEY(fk_idBouteille) REFERENCES BOUTEILLE(idBouteille)
);

CREATE TABLE IF NOT EXISTS BOUTEILLE_POSSEDE_FORMAT(
     idBoutpossfor INT NOT NULL AUTO_INCREMENT,
     fk_idBouteilleF INT NOT NULL,
     fk_idTaille INT NOT NULL,
     PRIMARY KEY (idBoutpossfor),
     FOREIGN KEY(fk_idBouteilleF) REFERENCES BOUTEILLE(idBouteille),
     FOREIGN KEY(fk_idTaille) REFERENCES TAILLE(idTaille)
);

CREATE TABLE VIN_PROVIENT_DOMAINE(
    idVinprodom int NOT NULL AUTO_INCREMENT,
    fk_idVinP int NOT NULL,
    fk_idDomaine int NOT NULL,
    PRIMARY KEY (idVinprodom),
    FOREIGN KEY (fk_idVinP) REFERENCES VIN (idVin),
    FOREIGN KEY (fk_idDomaine) REFERENCES DOMAINE (idDomaine)
);


CREATE TABLE IF NOT EXISTS DOMAINE_LOCALISE_VILLE(
     idDomlocville INT NOT NULL AUTO_INCREMENT,
     fk_idDomaineL INT NOT NULL,
     fk_idVilleL INT NOT NULL,
     PRIMARY KEY (idDomlocville),
     FOREIGN KEY(fk_idDomaineL) REFERENCES DOMAINE(idDomaine),
     FOREIGN KEY(fk_idVilleL) REFERENCES VILLE(idVille)
);



INSERT INTO CEPAGE (nomCepage) VALUES ('Pinot Blanc'),('Crémant'),('Sylvaner'),('Riesling'),('Muscat'),('Gewurtzraminer'),('Pinot Noir');
INSERT INTO COULEUR (nomCouleur) VALUES ('Blanc'),('Rouge'),('Rosé');
INSERT INTO VILLE (nomVille,cpville) VALUES ('Mutzig','67190'),('Dorlisheim','67100'),('Molsheim','67300');
INSERT INTO CAVE (nomCave, fk_idVille) VALUES ('Cave de Dorlisheim',2),('Cave de Molsheim',3);
INSERT INTO TAILLE (nomTaille,quantite) VALUES ('Fillete','375'),('Bouteille','750'),('Magnum','1500');
INSERT INTO DOMAINE (nomDomaine) VALUES ('Domaine des miraculés'),('Domaine de Dorlisheim');
INSERT INTO VIN (millesime,degresAlcool,nomVin,fk_idCepage,fk_idCouleur) VALUES ('2012-01-01',18,'Cuvée Lydie',1,1),('2012-01-01',18,'Cuvée Hélène',1,2),('2012-01-01',18,'Hospice de Strasbourg',1,1),('2012-01-01',18,'Vendanges tardives',1,1),('2012-01-01',18,'Muscat',5,1),('2012-01-01',18,'Crémant Rosé',2,3),
('2012-01-01',18,'Gewurtz des familles',6,1),('2012-01-01',18,'Pinot Renoi',7,2),('2012-01-01',18,'Sylvaner de la cave du nu pied',3,1),('2012-01-01',18,'Riesling Mertzinger',4,1);

SET FOREIGN_KEY_CHECKS=1
