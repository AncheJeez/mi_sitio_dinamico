-- ========================================================
-- 🛍️ Base de datos: tienda_php
-- Autor: profeinformatica101
-- Descripción: Crea la tabla 'usuarios' con roles admin, 
--              manager y usuario, e inserta datos iniciales.
-- ========================================================

-- 1️⃣ Crear base de datos
CREATE DATABASE IF NOT EXISTS tienda_php
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tienda_php;

-- 2️⃣ Crear tabla 'usuarios'
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS productos;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('admin','manager','usuario') DEFAULT 'usuario',
    creado_en DATETIME DEFAULT NOW()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  precio DOUBLE NOT NULL,
  stock INT NOT NULL,
  descripcion VARCHAR(50) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;