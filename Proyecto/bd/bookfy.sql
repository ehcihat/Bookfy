CREATE DATABASE IF NOT EXISTS BOOKFY;
USE BOOKFY;


DROP USER IF EXISTS 'prueba'@'localhost';


CREATE USER 'prueba'@'localhost' IDENTIFIED BY '1234';

GRANT ALL PRIVILEGES ON BOOKFY.* TO 'prueba'@'localhost';

FLUSH PRIVILEGES;


CREATE TABLE IF NOT EXISTS CATEGORIA(
    nom_cat VARCHAR(255) NOT NULL,
    id_cat INT(7) NOT NULL,
    PRIMARY KEY (id_cat)
);

CREATE TABLE IF NOT EXISTS USUARIO(
    email_usu VARCHAR(50) NOT NULL,
    pass_usu VARCHAR(255) NOT NULL,
    img_usu VARCHAR(255),
    nom_usu VARCHAR(255) NOT NULL,
    id_usu INT(9) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id_usu)
);

CREATE TABLE IF NOT EXISTS TOKEN(
    id_tok INT(9) NOT NULL AUTO_INCREMENT,
    value_tok VARCHAR(255) NOT NULL,
    date_creation DATE NOT NULL,
    date_exp DATE NOT NULL,
    id_usu INT(9),
    PRIMARY KEY (id_tok)
);


CREATE TABLE IF NOT EXISTS AUTOR(
    id_aut INT(9) NOT NULL AUTO_INCREMENT,
    nom_aut VARCHAR(50) NOT NULL,
    nac_aut DATE NOT NULL,
    email_aut VARCHAR(50),
    PRIMARY KEY (id_aut)
);

CREATE TABLE IF NOT EXISTS TIENDA(
    horA TIME NOT NULL,
    horB TIME NOT NULL,
    dir_tie VARCHAR(255) NOT NULL,
    nom_tie VARCHAR(255) NOT NULL,
    cod_tie INT(9) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (cod_tie)
);

CREATE TABLE IF NOT EXISTS GENERO(
    nom_gen VARCHAR(50) NOT NULL,
    id_gen INT(9) NOT NULL,
    PRIMARY KEY (id_gen)
);

CREATE TABLE IF NOT EXISTS EDITORIAL(
    id_edi INT(9) NOT NULL AUTO_INCREMENT,
    nom_edi VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_edi)
);

CREATE TABLE IF NOT EXISTS LIBRO(
    id_lib INT(9) NOT NULL AUTO_INCREMENT,
    isbn VARCHAR(17) NOT NULL,
    tit_lib VARCHAR(255) NOT NULL,
    num_pag INT NOT NULL,
    date_pub DATE NOT NULL,
    des_lib VARCHAR(255),
    precio DECIMAL(10,2) NOT NULL,
    lan_lib VARCHAR(255) NOT NULL,
    img_lib VARCHAR(255) NOT NULL,
    id_edi INT(9) NOT NULL,
    id_gen INT(9) NOT NULL,
    cod_tie INT(9) NOT NULL,
    PRIMARY KEY (id_lib),
    UNIQUE (isbn)
);

CREATE TABLE IF NOT EXISTS REVIEW(
    id_rev INT(9) NOT NULL AUTO_INCREMENT,
    est_rev TINYINT(9) NOT NULL,
    rate TINYINT UNSIGNED NOT NULL CHECK(
        rate > 0
        AND rate <= 5
    ),
    review TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_lib INT (9) NOT NULL,
    PRIMARY KEY (id_rev)
);

CREATE TABLE IF NOT EXISTS CATEGORIA_LIBROS(
    id INT(9) NOT NULL AUTO_INCREMENT,
    id_lib INT(9) NOT NULL,
    id_cat INT(7) NOT NULL,
    PRIMARY KEY (id)
);



CREATE TABLE IF NOT EXISTS AUTOR_LIBRO(
    id INT(9) NOT NULL AUTO_INCREMENT,
    id_aut INT(9) NOT NULL,
    id_lib INT(9) NOT NULL,
    PRIMARY KEY (id)
);

ALTER TABLE TOKEN
ADD FOREIGN KEY (id_usu) REFERENCES USUARIO(id_usu) ON DELETE CASCADE;


ALTER TABLE TOKEN
ADD FOREIGN KEY (id_usu) REFERENCES USUARIO(id_usu) ON DELETE CASCADE;


ALTER TABLE REVIEW
ADD FOREIGN KEY (id_lib) REFERENCES LIBRO(id_lib);

ALTER TABLE CATEGORIA_LIBROS
ADD FOREIGN KEY (id_lib) REFERENCES LIBRO(id_lib),
ADD FOREIGN KEY (id_cat) REFERENCES CATEGORIA(id_cat);


ALTER TABLE AUTOR_LIBRO
ADD FOREIGN KEY (id_aut) REFERENCES AUTOR(id_aut),
ADD FOREIGN KEY (id_lib) REFERENCES LIBRO(id_lib);

ALTER TABLE LIBRO
ADD FOREIGN KEY (id_gen) REFERENCES GENERO(id_gen),
ADD FOREIGN KEY (id_edi) REFERENCES EDITORIAL(id_edi),
ADD FOREIGN KEY (cod_tie) REFERENCES TIENDA(cod_tie);


INSERT IGNORE INTO CATEGORIA (id_cat,nom_cat) VALUES (1,'Cómic');
INSERT IGNORE INTO CATEGORIA (id_cat,nom_cat) VALUES (2,'Manga');
INSERT IGNORE INTO CATEGORIA (id_cat,nom_cat) VALUES (3,'Ebook');
INSERT IGNORE INTO CATEGORIA (id_cat,nom_cat) VALUES (4,'Trending');
INSERT IGNORE INTO CATEGORIA (id_cat,nom_cat) VALUES (5,'Español');

INSERT IGNORE INTO AUTOR (id_aut, nom_aut, nac_aut, email_aut) VALUES (1,'Cixin Liu', '1963-06-23', 'rapsoda@trisolaris.com');
INSERT IGNORE INTO AUTOR (id_aut, nom_aut, nac_aut, email_aut) VALUES (2,'Robin Hobb', '1952-03-05', 'fitz@apprentice.com');
INSERT IGNORE INTO AUTOR (id_aut, nom_aut, nac_aut, email_aut) VALUES (3,'Stephen King', '1947-09-21', 'SPKING@it.com');
INSERT IGNORE INTO AUTOR (id_aut, nom_aut, nac_aut, email_aut) VALUES (4,'Louise Penny', '1958-07-01', 'Louise@quebec.com');
INSERT IGNORE INTO AUTOR (id_aut, nom_aut, nac_aut, email_aut) VALUES (5,'Arthur Conan Doyle', '1859-05-22', 'Sherlock@holmes.com');
INSERT IGNORE INTO AUTOR (id_aut, nom_aut, nac_aut, email_aut) VALUES (6,'Nora Roberts', '1950-10-10', 'JDRobb@inheritance.com');

INSERT IGNORE INTO TIENDA (cod_tie, horA, horB, dir_tie, nom_tie) VALUES (1,'08:00:00', '18:00:00', 'Calle Principal 123', 'Librería Central');
INSERT IGNORE INTO TIENDA (cod_tie, horA, horB, dir_tie, nom_tie) VALUES (2,'09:00:00', '20:00:00', 'Avenida Central 456', 'Librería del Barrio');
INSERT IGNORE INTO TIENDA (cod_tie, horA, horB, dir_tie, nom_tie) VALUES (3,'10:00:00', '22:00:00', 'Plaza Mayor 789', 'Librería Plaza');
INSERT IGNORE INTO TIENDA (cod_tie, horA, horB, dir_tie, nom_tie) VALUES (4,'07:30:00', '17:30:00', 'Paseo Marítimo 234', 'Librería Mar');

INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (1,'Ciencia Ficción');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (2,'Fantasía');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (3,'Terror');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (4,'Misterio');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (5,'Policíaca');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (6,'Romance');

INSERT IGNORE INTO EDITORIAL (id_edi, nom_edi) VALUES (1,'Nova');
INSERT IGNORE INTO EDITORIAL (id_edi, nom_edi) VALUES (2,'Minotauro');
INSERT IGNORE INTO EDITORIAL (id_edi, nom_edi) VALUES (3,'B DE BOOKS');
INSERT IGNORE INTO EDITORIAL (id_edi, nom_edi) VALUES (4,'Del Rey');
INSERT IGNORE INTO EDITORIAL (id_edi, nom_edi) VALUES (5,'INSOLITA');

INSERT IGNORE INTO LIBRO (id_lib, tit_lib, des_lib, num_pag, date_pub, precio, lan_lib, img_lib, isbn, id_edi, id_gen, cod_tie) 
VALUES 
(1,'El problema de los tres cuerpos',"El público y la crítica de los cinco continentes se rinden ante esta obra maestra, enormemente visionaria, sobre el papel de la ciencia en nuestras sociedades, que nos ayuda a comprender el pasado y el futuro de China, pero también, leída en clave geopolítica, del mundo en que vivimos.
", 400, '2008-01-01', 19.99, 'es', 'el_problema_de_los_tres_cuerpos.jpg', '9788499082478', 1, 1, 1),
(2,'Aprendiz de Asesino',"El joven Traspié es el hijo bastardo del príncipe Hidalgo, heredero al trono de los Seis Ducados. En la corte, crece bajo la tutela del arisco caballerizo de su padre. ", 400, '1995-05-09', 12.99, 'es', 'aprendiz_de_asesino.jpg', '9788448032493', 2, 2, 2), 
(3,'It',"Esto es lo que se proponen averiguar los protagonistas de esta novela. Tras veintisiete años de tranquilidad y lejanía, una antigua promesa infantil les hace volver al lugar en el que vivieron su infancia y juventud como una terrible pesadilla. Regresan a Derry para enfrentarse con su pasado y enterrar definitivamente la amenaza que los amargó durante su niñez.", 1138, '1986-09-15', 24.99, 'es', 'it.jpg', '9781501198848', 2, 3, 3),
(4, 'Naturaleza muerta',"A medida que la niebla matutina se va despejando el domingo de Acción de Gracias, los hogares de Three Pines cobran vida; todos menos uno... Para los lugareños el pueblo es un reducto de seguridad. De modo que, cuando encuentran muerto a un miembro muy querido de la comunidad en el bosque de arces, les invade la perplejidad. Sin duda debió de ser un accidente, la flecha extraviada de un cazador. ¿Quién iba a querer a Jane Neal muerta?", 320, '2005-07-22', 17.99, 'es', 'naturaleza_muerta.jpg', '9780312367749', 4, 4, 4),
(5,'El Sabueso de los Baskerville',"Sobre los Baskerville pesa una terrible maldición: un demonio en forma de perro gigantesco se les aparece cuando llega la hora de la muerte de algún familiar. La maldición se ha renovado con la repentina muerte de sir Charles. El diabólico can deambula por los páramos y el último vástago de los Baskerville, sir Henry, regresa de Canadá para hacerse cargo de la heredad." ,256, '1902-01-01', 9.99, 'es', 'el_sabueso_de_los_baskerville.jpg', '9780141329380', 5, 4, 1),
(6,'La herencia',"Sonya recibe la noticia de que su difunto padre tenía un hermano gemelo poco después de romper su compromiso matrimonial y perder su trabajo, así que, cuando descubre que su tío desconocido le ha dejado en herencia una mansión victoriana en la costa de Maine, decide instalarse en ella para descubrir por qué los niños fueron separados al nacer y la razón por la que todo se mantuvo en secreto.", 384, '2012-06-26', 21.99, 'es', 'la_herencia.jpg', '9788492916970', 5, 6, 2);

INSERT IGNORE INTO CATEGORIA_LIBROS (id,id_lib, id_cat)  VALUES (1, 1, 3);
INSERT IGNORE INTO CATEGORIA_LIBROS (id,id_lib, id_cat)  VALUES (2, 2, 4);
INSERT IGNORE INTO CATEGORIA_LIBROS (id,id_lib, id_cat)  VALUES (3, 3, 3);
INSERT IGNORE INTO CATEGORIA_LIBROS (id,id_lib, id_cat)  VALUES (4, 4, 3);
INSERT IGNORE INTO CATEGORIA_LIBROS (id,id_lib, id_cat)  VALUES (5, 5, 3);
INSERT IGNORE INTO CATEGORIA_LIBROS (id,id_lib, id_cat)  VALUES (6, 6, 3);


INSERT IGNORE INTO AUTOR_LIBRO (id, id_aut, id_lib) VALUES (1, 1, 1);
INSERT IGNORE INTO AUTOR_LIBRO (id, id_aut, id_lib) VALUES (2, 2, 2);
INSERT IGNORE INTO AUTOR_LIBRO (id, id_aut, id_lib) VALUES (3, 3, 3);
INSERT IGNORE INTO AUTOR_LIBRO (id, id_aut, id_lib) VALUES (4, 4, 4);
INSERT IGNORE INTO AUTOR_LIBRO (id, id_aut, id_lib) VALUES (5, 5, 5);
INSERT IGNORE INTO AUTOR_LIBRO (id, id_aut, id_lib) VALUES (6, 6, 6);

INSERT IGNORE INTO REVIEW (est_rev, rate, review, id_lib) VALUES (1, 5, 'Excelente libro, altamente recomendado.', 1);
INSERT IGNORE INTO REVIEW (est_rev, rate, review, id_lib) VALUES (1, 5, 'Una obra maestra del género. No puedo esperar a leer más de este autor.', 2);
INSERT IGNORE INTO REVIEW (est_rev, rate, review, id_lib) VALUES (1, 5, 'Inolvidable. Un clásico instantáneo.', 3);


