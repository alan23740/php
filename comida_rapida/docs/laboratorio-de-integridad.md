# Laboratorio de integridad — Buen Bocado

Se ejecutaron los cinco casos contra la base `proyecto` en MariaDB. El script [probar-integridad.php](../sql/probar-integridad.php) abre una transacción por caso y ejecuta `ROLLBACK` tanto tras el error esperado como antes de fallar si una sentencia no genera una excepción.

El caso de campo obligatorio requiere el modo estricto. La sesión probada tenía `STRICT_TRANS_TABLES`.

| # | Prueba | Resultado real | Regla | Qué habría pasado en JSON |
|---|---|---|---|---|
| 1 | Insertar un producto con `categoria_id = 999999`. | SQLSTATE `23000`, MariaDB `1452`. | `fk_productos_categoria` requiere que exista la categoría. | Un nombre de categoría inventado podría almacenarse como texto; el JSON no comprueba referencias. |
| 2 | Insertar otra categoría llamada `Hamburguesas`. | SQLSTATE `23000`, MariaDB `1062`: `Duplicate entry 'Hamburguesas' for key 'uq_categorias_nombre'`. | `uq_categorias_nombre` impide nombres repetidos en la tabla de categorías. | La categoría se repite como texto por producto; no hay una tabla de categorías que garantice unicidad. |
| 3 | Borrar la categoría `Hamburguesas`, que tiene productos. | SQLSTATE `23000`, MariaDB `1451`. | La llave foránea impide borrar la categoría mientras existan productos dependientes. | No hay una fila padre que proteger; borrar o renombrar el texto requeriría actualizar productos y podría dejar inconsistencias. |
| 4 | Insertar un producto omitiendo `descripcion`. | SQLSTATE `HY000`, MariaDB `1364`: `Field 'descripcion' doesn't have a default value`. | `productos.descripcion` es `NOT NULL` y no tiene valor por defecto. | JSON permite omitir la clave al editar el archivo; `Producto::desdeArray()` lo detectaría al cargar el producto. |
| 5 | Consultar `SELECT * FROM productoss;`. | SQLSTATE `42S02`, MariaDB `1146`: `Table 'proyecto.productoss' doesn't exist`. | El nombre correcto de la tabla es `productos`. | No hay tablas ni SQL; el consumidor del JSON no ejecutaría esta consulta. |

Cada caso mostró el código esperado y terminó con `ROLLBACK`; el ensayo no añadió ni borró filas.

### Mensajes exactos de MariaDB observados

```text
1. Cannot add or update a child row: a foreign key constraint fails (`proyecto`.`productos`, CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`))
2. Duplicate entry 'Hamburguesas' for key 'uq_categorias_nombre'
3. Cannot delete or update a parent row: a foreign key constraint fails (`proyecto`.`productos`, CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`))
4. Field 'descripcion' doesn't have a default value
5. Table 'proyecto.productoss' doesn't exist
```

## Reproducir

```sh
php sql/probar-integridad.php
```

Salida final observada:

```text
OK: las cinco pruebas produjeron el error esperado y no dejaron cambios.
```

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

Los mensajes reales pueden incluir nombres de esquema o formato distintos según la versión de MariaDB/MySQL; se registran aquí los códigos y mensajes obtenidos en esta ejecución.
