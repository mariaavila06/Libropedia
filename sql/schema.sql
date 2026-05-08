CREATE DATABASE IF NOT EXISTS libropedia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE libropedia;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(150) NOT NULL,
    cedula VARCHAR(20) NOT NULL,
    contraseña_hash VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuario administrador (credenciales: Admin123 / 123456)
INSERT INTO usuarios (nombre, apellido, usuario, correo, cedula, contraseña_hash)
VALUES ('Admin', 'Sistema', 'Admin123', 'admin@libropedia.local', '0', '$2y$10$1J6HsO23180hMSL0Sn1vFetgZm3hZkDYMKQ5XffNPKPMdhERhvq0i')
ON DUPLICATE KEY UPDATE contraseña_hash = VALUES(contraseña_hash);

-- Tabla de géneros
CREATE TABLE IF NOT EXISTS generos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

-- Tabla de libros
CREATE TABLE IF NOT EXISTS libros (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    autor VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT NULL,
    precio_bs DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    pdf_path VARCHAR(255) DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relación muchos a muchos libro - género
CREATE TABLE IF NOT EXISTS libro_genero (
    libro_id INT UNSIGNED NOT NULL,
    genero_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (libro_id, genero_id),
    CONSTRAINT fk_libro
        FOREIGN KEY (libro_id) REFERENCES libros(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_genero
        FOREIGN KEY (genero_id) REFERENCES generos(id)
        ON DELETE CASCADE
);

-- Datos de prueba
INSERT INTO generos (nombre) VALUES
    ('Fantasía'),
    ('Clásico'),
    ('Novela contemporánea'),
    ('Misterio')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO libros (nombre, autor, descripcion, imagen, precio_bs, pdf_path) VALUES
    ('Cien años de soledad', 'Gabriel García Márquez', 'La obra maestra del realismo mágico latinoamericano.', 'assets/img/placeholder-libro.jpg', 250.00, 'storage/pdfs/cien-anos-de-soledad.pdf'),
    ('Don Quijote de la Mancha', 'Miguel de Cervantes', 'Las aventuras del ingenioso hidalgo Don Quijote y su escudero Sancho Panza.', 'assets/img/placeholder-libro.jpg', 300.00, 'storage/pdfs/don-quijote.pdf'),
    ('La sombra del viento', 'Carlos Ruiz Zafón', 'Un misterio literario ambientado en la Barcelona de la posguerra.', 'assets/img/placeholder-libro.jpg', 220.00, 'storage/pdfs/la-sombra-del-viento.pdf'),
    ('El nombre del viento', 'Patrick Rothfuss', 'La historia de Kvothe, un joven con un talento extraordinario.', 'assets/img/placeholder-libro.jpg', 280.00, 'storage/pdfs/el-nombre-del-viento.pdf');

-- Asociación simple de géneros a los libros de prueba
INSERT INTO libro_genero (libro_id, genero_id)
SELECT l.id, g.id
FROM libros l
JOIN generos g ON
    (l.nombre = 'Cien años de soledad' AND g.nombre = 'Novela contemporánea') OR
    (l.nombre = 'Don Quijote de la Mancha' AND g.nombre = 'Clásico') OR
    (l.nombre = 'La sombra del viento' AND g.nombre = 'Misterio') OR
    (l.nombre = 'El nombre del viento' AND g.nombre = 'Fantasía')
ON DUPLICATE KEY UPDATE genero_id = genero_id;

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    libro_id INT UNSIGNED NOT NULL,
    precio_bs DECIMAL(10,2) NOT NULL,
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_venta_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_venta_libro
        FOREIGN KEY (libro_id) REFERENCES libros(id)
        ON DELETE CASCADE
);

