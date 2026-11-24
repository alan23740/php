# Bitácora U3S08 — Modelado de datos y migración a MariaDB

## Alcance

Se adaptó la guía U3S08 al catálogo de comida rápida del proyecto. Los productos
y categorías de `datos/datos.json` son la fuente de migración; las entidades de
clientes, usuarios y pedidos están modeladas, pero no se inventaron registros
para ellas.

## Entorno de trabajo

- Fecha: 24 de noviembre de 2026.
- PHP CLI: 8.3.33.
- Extensión PDO: `pdo_mysql` habilitada.
- Base de datos: `proyecto`.
- Motor: MariaDB 10.11.14.
- Cliente: `mysql` / MariaDB CLI.
- Herramientas de entrega: Adminer 4.8.1 y Graphviz 2.43.0.
- Resultado de `php entorno/verificar-entorno.php`:

  ```text
  PHP 8.3.33: OK
  Extensión pdo_mysql: OK
  Conexión PDO a proyecto: OK (MariaDB/MySQL 10.11.14-MariaDB-0ubuntu0.24.04.1)
  ```

- Se agregaron `.devcontainer/devcontainer.json` y scripts de instalación e
  inicio de MariaDB. Su sintaxis JSON y Bash se validó. El contenedor no se
  reconstruyó en esta ejecución: se usó el entorno ya instalado y conectado.
- La configuración local de PDO está en `src/config/conexion.php` y está
  excluida de Git. La plantilla versionada es `src/config/conexion.ejemplo.php`.

## Modelo, esquema y carga

- El modelo relacional tiene seis tablas: `categorias`, `productos`, `clientes`,
  `usuarios`, `pedidos` y `detalles_pedido`.
- El archivo SQL recrea las tablas en orden seguro, usa InnoDB y UTF-8, declara
  llaves primarias, únicas y foráneas, define el estado del pedido y establece
  cascada de detalles al borrar un pedido.
- Los precios usan `DECIMAL(10,2)`. Las categorías son únicas; productos,
  pedidos y detalles referencian sus registros padres.
- El DBML está en `sql/er-comida-rapida.dbml`. La imagen del diagrama, generada
  desde su fuente Graphviz, está en `sql/er-comida-rapida.png`.
- Se ejecutó más de una vez la secuencia de importación (`sql/esquema.sql` y
  `sql/generar-inserts.php`). La recreación fue correcta. Antes de repetirla se
  comprobó que clientes, usuarios, pedidos y detalles estaban vacíos.
- El contrato de migración fue verificado con un JOIN:

  ```text
  OK: los 6 productos de MariaDB coinciden con datos/datos.json.
  ```

- Resultado final de conteos: 6 categorías, 6 productos, 0 clientes, 0 usuarios,
  0 pedidos y 0 detalles de pedido.
- Las columnas de `productos` y las cuatro llaves foráneas fueron consultadas
  directamente en MariaDB y coinciden con el modelo.

## Consultas

Se ejecutaron las cinco consultas de `sql/consultas.sql`: contrato JSON/SQL,
conteo, productos agotados, conteo por categoría y valor del inventario. Todas
terminaron correctamente. El resultado fue:

- 6 productos en 6 categorías.
- Agotados: Ensalada César y Taco de Carnitas.
- Valor total del inventario: 514.00.

El SQL completo y las filas devueltas están en
[registro-consultas-sql.md](registro-consultas-sql.md).

## Pruebas de integridad

Se ejecutó `php sql/probar-integridad.php`. Los cinco casos produjeron sus
códigos esperados; cada operación se hizo dentro de una transacción y terminó
con `ROLLBACK`.

| Prueba | Código SQLSTATE | Error MariaDB | Resultado |
|---|---|---:|---|
| Producto con categoría inexistente | `23000` | 1452 | Rechazado por `fk_productos_categoria`. |
| Categoría duplicada | `23000` | 1062 | Rechazada por `uq_categorias_nombre`. |
| Borrar categoría que tiene productos | `23000` | 1451 | Rechazado por la llave foránea. |
| Insertar producto sin descripción | `HY000` | 1364 | Rechazado por `NOT NULL` en modo estricto. |
| Consultar `productoss` | `42S02` | 1146 | Tabla inexistente, como se esperaba. |

Salida final:

```text
OK: las cinco pruebas produjeron el error esperado y no dejaron cambios.
```

Los mensajes originales y la comparación con el comportamiento posible en
JSON están en [laboratorio-de-integridad.md](laboratorio-de-integridad.md).

## Aplicación web y Adminer

- `http://localhost:3000/productos.php`: catálogo JSON disponible.
- `http://localhost:3000/productos-bd.php`: HTTP 200 y seis tarjetas desde PDO.
- `http://localhost:8080/adminer.php`: HTTP 200.
- El servidor de MariaDB quedó iniciado al terminar.
- Los dos servidores HTTP se iniciaron en procesos independientes. Para
  reproducirlos desde `comida_rapida/`:

  ```sh
  php -S localhost:3000 -t public
  php -S localhost:8080 -t dev
  ```

## Prueba breve con MariaDB detenida

Se detuvo MariaDB temporalmente para observar el comportamiento del catálogo
basado en PDO. Durante la interrupción:

```text
GET /productos-bd.php
HTTP 500
SQLSTATE[HY000] [2002] Connection refused
```

El servidor PHP registró el error de conexión al ejecutar `conectar()` en
`src/datos/conectar.php`. MariaDB se inició de inmediato; el catálogo volvió a
responder HTTP 200, y la consulta de migración volvió a confirmar los seis
productos. La base quedó disponible al finalizar.

## Aprendizajes de la bitácora

1. La tabla de categorías evita repetir y editar el mismo nombre en cada
   producto. La llave foránea protege los productos: intentar borrar una
   categoría en uso produce el error MariaDB 1451.
2. La prueba que más evidencia la integridad referencial es la categoría
   inexistente: MariaDB devuelve 1452. Un JSON sin validación relacional podría
   guardar ese texto, mientras que la llave foránea lo rechaza.
3. La migración conservó los campos y valores del producto, pero convirtió la
   categoría repetida en una relación `categoria_id`. El JOIN restituye el
   mismo contrato de siete campos y permite comprobar que no se perdió
   información.
   
documento hecho por CHATGPT