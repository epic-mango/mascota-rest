CREATE DATABASE Mascotas;
USE Mascotas;
CREATE TABLE `usuarios` (
  `id` varchar(20) NOT NULL,
  `pass` varchar(45) NOT NULL,
  `nombres` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `apodo` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE Mascotas (
  id INT UNIQUE AUTO_INCREMENT,
  nombre VARCHAR (45),
  especie INT,
  raza INT,
  nacimiento LONG ,
  usuario VARCHAR (20),
  CONSTRAINT Mascota_Usuario
    FOREIGN KEY (usuario) REFERENCES Usuarios(id)
    ON DELETE SET NULL 
    ON UPDATE CASCADE,
  PRIMARY KEY (id)  
);

