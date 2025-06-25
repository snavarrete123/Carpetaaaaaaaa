CREATE DATABASE portafolio_db;
USE portafolio_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user'
);

CREATE TABLE proyectos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL,
  url_github VARCHAR(255),
  url_produccion VARCHAR(255),
  imagen VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES ('admin', MD5('123456'), 'admin');
-- Usuario normal de prueba
INSERT INTO users (username, password, role) VALUES ('usuario', MD5('usuario123'), 'user');
  