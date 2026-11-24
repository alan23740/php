# Modelo de datos — Buen Bocado

| Entidad | Tabla | Qué guarda |
|---|---|---|
| Categoría | `categorias` | Nombre único de cada categoría del menú. |
| Producto | `productos` | Nombre, descripción, precio, existencias e imagen; referencia a una categoría. |
| Cliente | `clientes` | Nombre, correo único y hash de contraseña para futuras cuentas de clientes. |
| Usuario | `usuarios` | Nombre, correo único y hash de contraseña para futuras cuentas administrativas. |
| Pedido | `pedidos` | Cliente, fecha y estado del pedido. |
| Detalle de pedido | `detalles_pedido` | Productos, cantidades y precios unitarios registrados en cada pedido. |

## Relaciones y reglas

- Una categoría puede tener muchos productos; cada producto pertenece a una categoría.
- Un cliente puede tener muchos pedidos; cada pedido pertenece a un cliente.
- Un pedido tiene muchos detalles. Al borrar un pedido, sus detalles se eliminan en cascada.
- Cada detalle referencia un producto existente. Categorías, clientes y productos referenciados no se borran mientras tengan registros dependientes.
- Los correos de clientes y usuarios, y los nombres de categorías, son únicos.
- El precio y el precio unitario usan `DECIMAL(10,2)`; existencias y cantidades son enteros.
- `sql/er-comida-rapida.dbml` contiene el mismo modelo para visualizarlo en dbdiagram.io.
- El diagrama entregable es [er-comida-rapida.png](../sql/er-comida-rapida.png), generado desde `sql/er-comida-rapida.dot`.

## Migración desde `datos/datos.json`

| Campo JSON | Destino relacional | Cambio |
|---|---|---|
| `id` | `productos.id` | Se conserva. |
| `nombre` | `productos.nombre` | Se conserva. |
| `categoria` | `categorias.nombre` y `productos.categoria_id` | El texto se almacena una vez; el JOIN recupera su nombre. |
| `precio` | `productos.precio` | Se conserva como decimal de dos posiciones. |
| `descripcion` | `productos.descripcion` | Se conserva. |
| `stock` | `productos.stock` | Se conserva. |
| `imagen` | `productos.imagen` | Se conserva como ruta/URL. |

Para comprobar que el JOIN devuelve los mismos registros que el JSON, ejecuta
`php sql/verificar-migracion.php` después de importar el esquema y los datos.
