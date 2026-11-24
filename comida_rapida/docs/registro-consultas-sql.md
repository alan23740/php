# Registro de ejecución de consultas SQL

## Contexto

- Fecha de ejecución: 2026-11-24.
- Base de datos: `proyecto`.
- Motor: MariaDB `10.11.14-MariaDB-0ubuntu0.24.04.1`.
- Archivo ejecutado: `sql/consultas.sql`.
- Comando equivalente para repetir la ejecución: `mysql --table --default-character-set=utf8mb4 -u app -p -h 127.0.0.1 < sql/consultas.sql` (solicita la contraseña de forma interactiva).
- La contraseña usada durante la ejecución no se incluye en este registro.
- Resultado general: las cinco consultas terminaron correctamente. Todas son consultas de lectura.

## 1. Contrato de migración: productos con su categoría

```sql
SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.descripcion, p.stock, p.imagen
FROM productos AS p
INNER JOIN categorias AS c ON c.id = p.categoria_id
ORDER BY p.id;
```

| id | nombre | categoria | precio | descripcion | stock | imagen |
|---:|---|---|---:|---|---:|---|
| 1 | Hamburguesa Clásica | Hamburguesas | 9.50 | Carne a la parrilla, lechuga, tomate y salsa de la casa. | 20 | `https://example.com/images/hamburguesa_clasica.jpg` |
| 2 | Papas Fritas | Acompañamientos | 3.00 | Papas doradas y crujientes, servidas calientes. | 30 | `https://example.com/images/papas_fritas.jpg` |
| 3 | Malteada de Vainilla | Bebidas | 4.50 | Malteada cremosa preparada con helado de vainilla. | 12 | `https://example.com/images/malteada_vainilla.jpg` |
| 4 | Pizza Pepperoni | Pizzas | 12.00 | Pizza con salsa de tomate, queso mozzarella y pepperoni. | 15 | `https://example.com/images/pizza_pepperoni.jpg` |
| 5 | Ensalada César | Ensaladas | 7.00 | Lechuga fresca, crutones, queso parmesano y aderezo César. | 0 | `https://example.com/images/ensalada_cesar.jpg` |
| 6 | Taco de Carnitas | Tacos | 5.00 | Taco con carne de cerdo asada, cebolla, cilantro y salsa. | 0 | `https://example.com/images/taco_carnitas.jpg` |

Resultado: 6 filas.

## 2. Cantidad de productos

```sql
SELECT COUNT(*) AS total_productos FROM productos;
```

| total_productos |
|---:|
| 6 |

## 3. Productos sin existencias

```sql
SELECT nombre
FROM productos
WHERE stock = 0;
```

| nombre |
|---|
| Ensalada César |
| Taco de Carnitas |

Resultado: 2 filas.

## 4. Cantidad de productos por categoría

```sql
SELECT c.nombre AS categoria, COUNT(p.id) AS total_productos
FROM categorias AS c
LEFT JOIN productos AS p ON p.categoria_id = c.id
GROUP BY c.id, c.nombre
ORDER BY c.nombre;
```

| categoria | total_productos |
|---|---:|
| Acompañamientos | 1 |
| Bebidas | 1 |
| Ensaladas | 1 |
| Hamburguesas | 1 |
| Pizzas | 1 |
| Tacos | 1 |

## 5. Valor del inventario por categoría

```sql
SELECT c.nombre AS categoria, SUM(p.precio * p.stock) AS valor_inventario
FROM categorias AS c
INNER JOIN productos AS p ON p.categoria_id = c.id
GROUP BY c.id, c.nombre
ORDER BY c.nombre;
```

| categoria | valor_inventario |
|---|---:|
| Acompañamientos | 90.00 |
| Bebidas | 54.00 |
| Ensaladas | 0.00 |
| Hamburguesas | 190.00 |
| Pizzas | 180.00 |
| Tacos | 0.00 |

**Valor total del inventario: 514.00.**

## Conclusión

El catálogo contiene 6 productos en 6 categorías. El JOIN devuelve los siete
campos del contrato de migración, y dos productos están agotados. El valor
calculado del inventario es 514.00 en la moneda usada por los precios del
catálogo.
