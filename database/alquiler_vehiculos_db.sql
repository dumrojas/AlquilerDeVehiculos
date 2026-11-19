CREATE DATABASE IF NOT EXISTS alquiler_vehiculos_db
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;
USE alquiler_vehiculos_db;
SET NAMES utf8mb4;

DROP TABLE IF EXISTS reservas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS vehiculos;

CREATE TABLE vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,
    anio YEAR NOT NULL,
    categoria VARCHAR(50),
    estado ENUM('disponible', 'alquilado', 'mantenimiento') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(50),
    correo VARCHAR(100),
    numero_licencia VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    vehiculo_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('activa', 'completada', 'cancelada') DEFAULT 'activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id) ON DELETE CASCADE
);

-- Inseccion de datos 

INSERT INTO vehiculos (id, marca, modelo, anio, categoria, estado) VALUES
(2, 'Toyota',  'Corolla',  2023, 'Sedán',     'disponible'),
(3, 'Hyundai', 'Tucson',   2027, 'SUV',       'disponible'),
(4, 'Kia',     'Picanto',  2021, 'Hatchback', 'mantenimiento'),
(5, 'Nissan',  'Frontier', 2024, 'Pickup',    'alquilado'),
(6, 'Mazda',   'CX-5',     2022, 'SUV',       'disponible'),
(7, 'Renault', 'Logan',    2020, 'Sedán',     'disponible');

INSERT INTO clientes (id, nombre, telefono, correo, numero_licencia) VALUES
(2, 'Ana Rodríguez',   '8888-1111', 'ana@correo.com',    'LIC-1001'),
(3, 'Carlos Méndez',   '8777-2222', 'carlos@correo.com', 'LIC-1002'),
(4, 'María Fernández', '8666-3333', 'maria@correo.com',  'LIC-1003'),
(5, 'Luis Gómez',      '8555-4444', 'luis@correo.com',   'LIC-1004'),
(6, 'Laura Torres',    '8444-5555', 'laura@correo.com',  'LIC-1005');

INSERT INTO reservas (id, cliente_id, vehiculo_id, fecha_inicio, fecha_fin, estado) VALUES
(5, 2, 5, '2026-11-13', '2026-11-17', 'activa'),
(6, 3, 2, '2026-11-20', '2026-11-23', 'activa'),
(7, 4, 3, '2026-11-05', '2026-11-09', 'completada'),
(8, 5, 6, '2026-12-01', '2026-12-05', 'activa'),
(9, 6, 7, '2026-10-10', '2026-10-14', 'completada');
