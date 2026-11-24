# Buen Bocado

Catálogo de comida rápida en PHP. La página `productos.php` conserva la lectura desde JSON; `productos-bd.php` muestra el mismo catálogo consultando MariaDB mediante PDO.

## Entorno y conexión

El repositorio configura Codespaces con [.devcontainer/devcontainer.json](../.devcontainer/devcontainer.json). En una reconstrucción instala PHP, `pdo_mysql`, MariaDB, Adminer y Graphviz; al iniciar prepara MariaDB y la base de desarrollo.

Desde la raíz del repositorio, verifica el entorno y, si MariaDB no está activa, iníciala:

```sh
bash .devcontainer/iniciar-mariadb.sh
php comida_rapida/entorno/verificar-entorno.php
```

El script crea `src/config/conexion.php` desde la plantilla solo si el archivo aún no existe; no sobrescribe una configuración local existente.

En Codespaces, usa los puertos reenviados 3000, 8080 y 3306. Para configurar otra instalación, los datos locales de desarrollo son: host `127.0.0.1`, puerto `3306`, base `proyecto`, usuario `app`, contraseña `app123`.

## Importar y consultar el catálogo

Desde la carpeta del proyecto (`comida_rapida/`), crea las tablas y carga los productos actuales desde JSON:

```sh
mysql -u app -papp123 -h 127.0.0.1 proyecto < sql/esquema.sql
php sql/generar-inserts.php | mysql -u app -papp123 -h 127.0.0.1 proyecto
php sql/verificar-migracion.php
```

**Advertencia:** `sql/esquema.sql` elimina y recrea las tablas; la importación borra los datos que haya en ellas. El generador conserva los IDs, precios y demás campos del catálogo JSON y normaliza las categorías.

`sql/consultas.sql` contiene consultas para el JOIN de migración, conteo, productos agotados, agrupación por categoría y valor del inventario. Para ejecutar la prueba de integridad sin dejar cambios:

```sh
php sql/probar-integridad.php
```

## Servidores locales

Abre dos terminales desde la carpeta `comida_rapida/`. Inicia el sitio en el puerto 3000 y Adminer en el 8080:

```sh
php -S localhost:3000 -t public
php -S localhost:8080 -t dev
```

El catálogo JSON está en `http://localhost:3000/productos.php` y el catálogo SQL en `http://localhost:3000/productos-bd.php`. Adminer abre en `http://localhost:8080/adminer.php`; selecciona MariaDB/MySQL, servidor `127.0.0.1`, base `proyecto` y las credenciales locales descritas arriba.

El modelo relacional está en [sql/er-comida-rapida.dbml](sql/er-comida-rapida.dbml) y su imagen en [sql/er-comida-rapida.png](sql/er-comida-rapida.png). El PNG se genera desde su fuente Graphviz con `dot -Tpng -Gdpi=160 sql/er-comida-rapida.dot -o sql/er-comida-rapida.png`.

## Entregables del modelo

- Diagrama y reglas: [docs/modelo-de-datos.md](docs/modelo-de-datos.md)
- DBML importable en dbdiagram.io: [sql/er-comida-rapida.dbml](sql/er-comida-rapida.dbml)
- Pruebas de llaves y restricciones: [docs/laboratorio-de-integridad.md](docs/laboratorio-de-integridad.md)
- Registro general de las actividades U3S08: [docs/bitacora-u3s08.md](docs/bitacora-u3s08.md)
- Resultados completos de las consultas: [docs/registro-consultas-sql.md](docs/registro-consultas-sql.md)

## Prueba con MariaDB detenida

El 24 de noviembre de 2026 se detuvo MariaDB durante una comprobación breve. `productos-bd.php` respondió HTTP 500 y el servidor registró `SQLSTATE[HY000] [2002] Connection refused`. Se inició MariaDB inmediatamente después; la página volvió a responder HTTP 200 y el catálogo conservó sus seis productos. El detalle está en [docs/bitacora-u3s08.md](docs/bitacora-u3s08.md).

## Respuestas de bitácora

1. En JSON el nombre de la categoría se repetía en cada producto, así que corregirlo podía requerir cambiar varias filas y era fácil dejar valores inconsistentes. La tabla `categorias` evita esa repetición; la llave foránea también evita borrar una categoría mientras haya productos que dependan de ella.
2. **Respuesta sugerida para personalizar:** Me sorprendió que la base rechazara borrar una categoría que todavía tiene productos (error 1451). La llave foránea conserva la integridad; con JSON no existe esa protección automática.
3. Se conservaron los productos y sus campos; la categoría pasó de texto repetido a `categoria_id`, que se vuelve a presentar como nombre mediante un JOIN. Mantener el contrato de campos permite que la vista reutilice el modelo y que la comparación entre JSON y SQL compruebe que la migración no perdió información.
