# Laboratorio de integridad — Buen Bocado

Ejecuta cada caso por separado contra una base con el esquema y los datos iniciales cargados. Inicia una transacción antes de cada prueba y ejecuta `ROLLBACK` después del error esperado. Si una sentencia no produce el error indicado, revierte inmediatamente y revisa el esquema.

El caso de campo obligatorio presupone un modo estricto de MariaDB; actívalo en la conexión de pruebas:

```sql
SET SESSION sql_mode = CONCAT(@@SESSION.sql_mode, ',STRICT_TRANS_TABLES');
```

| # | Prueba | Error esperado | Restricción |
|---|---|---|---|
| 1 | Insertar un producto con `categoria_id = 999999`. | `1452 Cannot add or update a child row` | `fk_productos_categoria` exige una categoría existente. |
| 2 | Insertar otra categoría con nombre `Hamburguesas` y un ID nuevo. | `1062 Duplicate entry` | `uq_categorias_nombre` hace único el nombre. |
| 3 | Borrar la categoría `Hamburguesas`, que tiene productos. | `1451 Cannot delete or update a parent row` | La llave foránea de productos impide borrar una categoría en uso. |
| 4 | Insertar un producto omitiendo `descripcion`. | `1364 Field 'descripcion' doesn't have a default value` en modo estricto | `productos.descripcion` es `NOT NULL` y no tiene valor por defecto. |
| 5 | Consultar `SELECT * FROM productoss;`. | `1146 Table 'proyecto.productoss' doesn't exist` | La tabla correcta se llama `productos`. |

## SQL para reproducir los errores

Ejecuta cada bloque de forma independiente. La primera sentencia debe fallar; luego ejecuta `ROLLBACK` en esa misma conexión.

```sql
START TRANSACTION;
INSERT INTO productos (id, nombre, descripcion, precio, stock, categoria_id, imagen)
VALUES (9001, 'Prueba', 'Categoría inexistente', 1.00, 1, 999999, 'prueba.jpg');
ROLLBACK;
```

```sql
START TRANSACTION;
INSERT INTO categorias (id, nombre) VALUES (9001, 'Hamburguesas');
ROLLBACK;
```

```sql
START TRANSACTION;
DELETE FROM categorias WHERE nombre = 'Hamburguesas';
ROLLBACK;
```

```sql
START TRANSACTION;
INSERT INTO productos (id, nombre, precio, stock, categoria_id, imagen)
SELECT 9001, 'Prueba', 1.00, 1, id, 'prueba.jpg'
FROM categorias
WHERE nombre = 'Hamburguesas';
ROLLBACK;
```

```sql
SELECT * FROM productoss;
```

Los códigos anteriores son los resultados esperados en MariaDB/MySQL; deben cotejarse con el mensaje real del servidor al ejecutar la práctica.
