USE users; 
CREATE TABLE products(
    id int PRIMARY KEY AUTO_INCREMENT,
    name varchar(250) NOT NULL,
    price DECIMAL NOT NULL
);


CREATE TABLE commands(
    id int PRIMARY KEY AUTO_INCREMENT,
    product_id int,
    FOREIGN KEY(product_id) REFERENCES products(id)
);