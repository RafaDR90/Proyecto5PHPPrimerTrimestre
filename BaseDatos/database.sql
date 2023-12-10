DROP DATABASE IF EXISTS blog;
CREATE DATABASE IF NOT EXISTS blog;
USE blog;
SET NAMES utf8mb4;

-- Tabla Usuarios
CREATE TABLE Usuarios (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nombre VARCHAR(100),
                          apellidos VARCHAR(100),
                          email VARCHAR(255),
                          password VARCHAR(255)
)ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


-- Añade tipo a la tabla Usuarios
ALTER TABLE Usuarios ADD COLUMN rol VARCHAR(50) AFTER password;

-- Tabla Categorias
CREATE TABLE Categorias (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            nombre VARCHAR(100)
)ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Tabla Entradas
CREATE TABLE Entradas (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          usuario_id INT,
                          categoria_id INT,
                          titulo VARCHAR(255),
                          descripcion MEDIUMTEXT,
                          fecha DATE,
                          FOREIGN KEY (usuario_id) REFERENCES Usuarios(id) ON DELETE CASCADE,
                          FOREIGN KEY (categoria_id) REFERENCES Categorias(id) ON DELETE CASCADE
)ENGINE=InnoDb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;



/*
UPDATE Usuarios
SET rol = 'admin'
WHERE nombre = 'admin';
*/

