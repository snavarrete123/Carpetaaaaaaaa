CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'viewer') NOT NULL
);
CREATE TABLE IF NOT EXISTS proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    enlace VARCHAR(255)
);
INSERT INTO usuarios (usuario, password, rol) VALUES ('admin', '1234', 'admin')
    ON DUPLICATE KEY UPDATE usuario=usuario; 