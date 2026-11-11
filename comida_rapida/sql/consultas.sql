SET NAMES utf8mb4;
USE proyecto;

-- Contrato de migración: mismos campos y valores del JSON, en su orden original.
SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.descripcion, p.stock, p.imagen
FROM productos AS p
INNER JOIN categorias AS c ON c.id = p.categoria_id
ORDER BY p.id;

SELECT COUNT(*) AS total_productos FROM productos;

SELECT nombre
FROM productos
WHERE stock = 0;

SELECT c.nombre AS categoria, COUNT(p.id) AS total_productos
FROM categorias AS c
LEFT JOIN productos AS p ON p.categoria_id = c.id
GROUP BY c.id, c.nombre
ORDER BY c.nombre;

SELECT c.nombre AS categoria, SUM(p.precio * p.stock) AS valor_inventario
FROM categorias AS c
INNER JOIN productos AS p ON p.categoria_id = c.id
GROUP BY c.id, c.nombre
ORDER BY c.nombre;
