USE ecommerce;
CREATE TABLE categorie(
    id_categorie int PRIMARY KEY AUTO_INCREMENT,
    nom_categorie varchar(255) NOT NULL
);



CREATE TABLE produit (id int(11) PRIMARY KEY AUTO_INCREMENT,
nom varchar(255) NOT NULL,description varchar(255) NOT NULL,
quantiti int(11) NOT NULL,
 prix decimal(10) NOT NULL,
categorie_produit int(11) NOT NULL ,
FOREIGN KEY(categorie_produit) REFERENCES categorie(id_categorie)
,photo varchar(255) NOT NULL); 


USE ecommerce;
CREATE TABLE panier(
    id int PRIMARY KEY AUTO_INCREMENT,
    reference_visiteur varchar(255) NOT NULL
    
);
CREATE TABLE ligne_panier(
    id_ligne_panier int PRIMARY KEY AUTO_INCREMENT,
    idproduit int(11),FOREIGN KEY(idproduit) REFERENCES produit(id),
    idpanier int(11),FOREIGN KEY(idpanier) REFERENCES panier(id),
    produit_quantitePanier varchar(255)
);
