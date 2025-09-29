-- Crear base de datos
CREATE DATABASE IF NOT EXISTS control_maestro
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE control_maestro;

-- Tabla de equipos a controlar
CREATE TABLE IF NOT EXISTS equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    mac VARCHAR(20) NOT NULL,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de acciones realizadas sobre los equipos
CREATE TABLE IF NOT EXISTS acciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    tipo_accion ENUM('APAGADO','WOL') NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    resultado VARCHAR(255),
    FOREIGN KEY (id_equipo) REFERENCES equipos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Tabla de usuarios (opcional, para control de acceso)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','user') DEFAULT 'user',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- Insertar un equipo
INSERT INTO equipos (nombre, ip, mac, descripcion)
VALUES ('PC-Salon', '192.168.1.50', 'AA:BB:CC:DD:EE:FF', 'Ordenador del salón');

-- Registrar una acción de Wake-on-LAN
INSERT INTO acciones (id_equipo, tipo_accion, resultado)
VALUES (1, 'WOL', 'Paquete mágico enviado');