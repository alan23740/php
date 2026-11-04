# Laboratorio de fallas — U3S07

En cada experimento cambié una cosa, observé el resultado y restauré el archivo
antes de continuar.

| # | Qué rompí | Lo que predije | Excepción y mensaje real | ¿El mensaje me orienta? |
|---|---|---|---|---|
| 1 | En `src/probar-modelos.php`, cambié `datos/datos.json` por `datos/dato.json`. | Esperaba que el repositorio indicara que no encontraba el archivo de datos. | `RuntimeException`: `No se encontró el archivo de datos: /workspaces/php/comida_rapida/src/../datos/dato.json` | Sí. Identifica que falta el archivo y muestra la ruta que intentó abrir. |
| 2 | Quité la coma entre los dos primeros registros de `datos/datos.json`. | Esperaba un error al interpretar el JSON. | `JsonException`: `Syntax error` | Parcialmente. Identifica que el JSON no es válido, aunque no indica la coma o la posición exacta. |
| 3 | Quité el campo `stock` del primer producto en `datos/datos.json`. | Esperaba que la creación del producto fallara por faltar un campo obligatorio. | `InvalidArgumentException`: `Falta el campo obligatorio: stock` | Sí. Señala exactamente el campo ausente. |
| 4 | Llamé a `buscarPorId(9999)` sin el bloque `try/catch` en `src/probar-modelos.php`. | Esperaba que el producto inexistente produjera una excepción no capturada. | `OutOfBoundsException`: `No existe un producto con id 9999.` | Sí. Explica qué identificador no se encontró. |
| 5 | Cambié el precio del primer producto a `"gratis"` (texto) en `datos/datos.json`. | Esperaba un error de tipo por usar texto donde se espera un precio numérico. | No hubo excepción: el precio se convirtió a `0.0` y se mostró como `$0.00`. | No. El resultado parece válido y no advierte que el dato de entrada es incorrecto. |

El experimento 5 es el más peligroso: en lugar de fallar claramente, convierte
un valor inválido en un precio de cero. Esto puede ocultar datos incorrectos y
mostrar un precio engañoso.

Al terminar, restauré todos los cambios temporales. El JSON contiene sus seis
registros originales y `src/probar-modelos.php` vuelve a capturar la búsqueda
del ID `9999`.
