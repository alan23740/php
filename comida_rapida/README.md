# Buen Bocado

Catálogo de comida rápida en PHP. La página `productos.php` conserva la lectura desde JSON; `productos-bd.php` muestra el mismo catálogo consultando MariaDB mediante PDO.

## Entorno y conexión

Se requiere PHP con `pdo_mysql` y un servidor MariaDB/MySQL accesible. En el entorno de la guía U3S08, los datos de desarrollo son: host `127.0.0.1`, puerto `3306`, base `proyecto`, usuario `app` y contraseña `app123`.

1. Inicia MariaDB y crea la base `proyecto` si todavía no existe.
2. Copia `src/config/conexion.ejemplo.php` a `src/config/conexion.php`. Este último archivo está excluido de Git; ajusta sus valores o define `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASSWORD`.
3. Crea las tablas y carga los productos convertidos desde JSON:

   ```sh
   mysql -u app -papp123 -h 127.0.0.1 proyecto < sql/esquema.sql
   php sql/generar-inserts.php | mysql -u app -papp123 -h 127.0.0.1 proyecto
   ```

4. Comprueba la equivalencia del JOIN con el archivo JSON:

   ```sh
   php sql/verificar-migracion.php
   ```

5. Inicia el servidor web y abre `http://localhost:3000/productos-bd.php`:

   ```sh
   php -S localhost:3000 -t public
   ```

`sql/esquema.sql` recrea las tablas desde cero. Para regenerar los INSERT después de cambiar `datos/datos.json`, vuelve a ejecutar el generador. `sql/consultas.sql` contiene consultas de conteo, filtrado, agrupación, suma y el JOIN de comprobación.

## Entregables del modelo

- Diagrama y reglas: [docs/modelo-de-datos.md](docs/modelo-de-datos.md)
- DBML importable en dbdiagram.io: [sql/er-comida-rapida.dbml](sql/er-comida-rapida.dbml)
- Pruebas de llaves y restricciones: [docs/laboratorio-de-integridad.md](docs/laboratorio-de-integridad.md)

## Respuestas de referencia para la bitácora

Adapta la segunda respuesta a los errores que observes al ejecutar el laboratorio.

1. En JSON el nombre de la categoría se repetía en cada producto, así que corregirlo podía requerir cambiar varias filas y era fácil dejar valores inconsistentes. La tabla `categorias` evita esa repetición; la llave foránea también evita borrar una categoría mientras haya productos que dependan de ella.
2. La llave foránea de `productos.categoria_id` detecta una referencia a una categoría inexistente (error 1452). Con el JSON sin validar, un nombre de categoría mal escrito podía guardarse sin que el archivo lo rechazara.
3. Se conservaron los productos y sus campos; la categoría pasó de texto repetido a `categoria_id`, que se vuelve a presentar como nombre mediante un JOIN. Mantener el contrato de campos permite que la vista reutilice el modelo y que la comparación entre JSON y SQL compruebe que la migración no perdió información.
