-- Agregar nuevos campos a la tabla libros
ALTER TABLE libros
ADD COLUMN editorial VARCHAR(100) NOT NULL DEFAULT '' AFTER descripcion,
ADD COLUMN anio_publicacion INT UNSIGNED NOT NULL DEFAULT 0 AFTER editorial,
ADD COLUMN numero_edicion INT UNSIGNED NOT NULL DEFAULT 1 AFTER anio_publicacion,
ADD COLUMN lugar_publicacion VARCHAR(100) NOT NULL DEFAULT '' AFTER numero_edicion;

-- Actualizar registros existentes para que cumplan con las validaciones
UPDATE libros SET editorial = 'Desconocida' WHERE editorial = '';
UPDATE libros SET anio_publicacion = 2000 WHERE anio_publicacion = 0;
UPDATE libros SET numero_edicion = 1 WHERE numero_edicion = 0;
UPDATE libros SET lugar_publicacion = 'Desconocido' WHERE lugar_publicacion = '';
