
CREATE TABLE IF NOT EXISTS SAGA(
    nombre_saga VARCHAR(255) NOT NULL,
    id_saga INT(9) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id_saga)
);

CREATE TABLE IF NOT EXISTS COLECCION(
    nom_col VARCHAR(255) NOT NULL,
    id_usu INT(9) NOT NULL,
    fila INT(9) NOT NULL,
    col_num INT(9) NOT NULL,
    es_predeterminada BOOLEAN NOT NULL DEFAULT FALSE,
    id_col INT(9) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id_col)
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
    des_lib TEXT,
    lan_lib VARCHAR(255) NOT NULL,
    img_lib TEXT NOT NULL,
    id_edi INT(9) NOT NULL,
    id_gen INT(9) NOT NULL,
    id_saga INT(9),
    orden_saga INT(9),
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
    id_usu INT(9) NOT NULL,
    PRIMARY KEY (id_rev)
);



CREATE TABLE IF NOT EXISTS AUTOR_LIBRO(
    id INT(9) NOT NULL AUTO_INCREMENT,
    id_aut INT(9) NOT NULL,
    id_lib INT(9) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS COLECCION_LIBRO(
    id INT(9) NOT NULL AUTO_INCREMENT,
    id_col INT(9) NOT NULL,
    id_lib INT(9) NOT NULL,
    estado_lectura ENUM('quiero_leer', 'en_progreso', 'leido', 'abandonado') NOT NULL DEFAULT 'quiero_leer',
    fecha_inicio DATE,
    fecha_fin DATE,
    orden_visual INT(9),
    PRIMARY KEY (id)
);

ALTER TABLE TOKEN
ADD FOREIGN KEY (id_usu) REFERENCES USUARIO(id_usu) ON DELETE CASCADE;


ALTER TABLE REVIEW
ADD FOREIGN KEY (id_lib) REFERENCES LIBRO(id_lib),
ADD FOREIGN KEY (id_usu) REFERENCES USUARIO(id_usu);


ALTER TABLE AUTOR_LIBRO
ADD FOREIGN KEY (id_aut) REFERENCES AUTOR(id_aut),
ADD FOREIGN KEY (id_lib) REFERENCES LIBRO(id_lib);

ALTER TABLE LIBRO
ADD FOREIGN KEY (id_gen) REFERENCES GENERO(id_gen),
ADD FOREIGN KEY (id_edi) REFERENCES EDITORIAL(id_edi),
ADD FOREIGN KEY (id_saga) REFERENCES SAGA(id_saga);

ALTER TABLE COLECCION
ADD FOREIGN KEY (id_usu) REFERENCES USUARIO(id_usu) ON DELETE CASCADE;


ALTER TABLE COLECCION_LIBRO
ADD UNIQUE (id_col, id_lib),
ADD FOREIGN KEY (id_col) REFERENCES COLECCION(id_col) ON DELETE CASCADE,
ADD FOREIGN KEY (id_lib) REFERENCES LIBRO(id_lib);

INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (1,'Ciencia Ficción');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (2,'Fantasía');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (3,'Terror');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (4,'Misterio');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (5,'Policíaca');
INSERT IGNORE INTO GENERO (id_gen, nom_gen) VALUES (6,'Romance');



