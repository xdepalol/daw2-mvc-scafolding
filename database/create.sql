CREATE DATABASE laliga CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laligauser'@'localhost' IDENTIFIED BY '2obmv2uqZj3pxx';
GRANT ALL PRIVILEGES ON laliga.* TO 'laligauser'@'localhost';
FLUSH PRIVILEGES;

USE laliga;

/* Equipo */
CREATE TABLE equipo (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    pais VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

INSERT INTO equipo (nombre, ciudad, pais) VALUES
('Real Madrid CF', 'Madrid', 'España'),
('FC Barcelona', 'Barcelona', 'España'),
('Atlético de Madrid', 'Madrid', 'España'),
('Athletic Club', 'Bilbao', 'España'),
('Real Sociedad', 'San Sebastián', 'España'),
('Villarreal CF', 'Villarreal', 'España'),
('Real Betis Balompié', 'Sevilla', 'España'),
('Sevilla FC', 'Sevilla', 'España'),
('Valencia CF', 'Valencia', 'España'),
('RC Celta de Vigo', 'Vigo', 'España'),
('CA Osasuna', 'Pamplona', 'España'),
('Rayo Vallecano', 'Madrid', 'España'),
('RCD Mallorca', 'Palma de Mallorca', 'España'),
('UD Las Palmas', 'Las Palmas de Gran Canaria', 'España'),
('Deportivo Alavés', 'Vitoria-Gasteiz', 'España'),
('Getafe CF', 'Getafe', 'España'),
('Girona FC', 'Girona', 'España'),
('CD Leganés', 'Leganés', 'España'),
('Real Valladolid CF', 'Valladolid', 'España'),
('RCD Espanyol', 'Barcelona', 'España');
