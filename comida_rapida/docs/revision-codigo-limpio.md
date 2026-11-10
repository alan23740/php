# Revisión de código limpio

## Mejoras aplicadas

1. **Validación de los datos del producto:** `Producto::desdeArray()` ya no convierte silenciosamente valores incorrectos, como `"gratis"`, en un precio de `$0.00`. Ahora valida tipos, textos requeridos, identificadores, existencias y precios, y reporta errores con `InvalidArgumentException`.
2. **Alta de productos completa:** el formulario ahora solicita la URL de imagen y el manejador valida que sea una URL HTTP o HTTPS antes de guardar. También persiste `imagen`, campo obligatorio para crear un `Producto`; así los productos nuevos pueden cargarse en el catálogo sin romper el modelo.
3. **Nivel de encabezado válido:** `mostrarTarjetaProducto()` rechaza niveles fuera de `h1` a `h6` con una excepción explícita, en vez de generar etiquetas HTML inválidas.

## Verificación

- `php -l` en el modelo, la vista y el formulario.
- Prueba del modelo con datos correctos y con un precio no numérico para confirmar que el valor inválido se rechaza.
- Renderizado de las páginas de catálogo y detalle con los registros actuales.
