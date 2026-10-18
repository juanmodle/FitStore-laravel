# Sprint 2: uso y comprobación

## Qué incluye

- Diseño entidad-relación y decisiones en `sprint-2-diseno.md`.
- Una migración de creación por tabla, incluidas las tablas técnicas de Laravel y las tablas pivote. Hay 35 migraciones de creación y una de ampliación de usuarios. Los índices se definen en la migración de su tabla.
- 24 modelos de negocio contando User y tres clases Pivot para interpretar sus campos adicionales.
- 23 factories nuevas y la factory de User que ya tenía Laravel.
- Un seeder por cada uno de los 24 modelos de negocio, cuatro para sus relaciones muchos a muchos y otro para asignar el ganador del sorteo, coordinados por DatabaseSeeder.
- Pruebas Pest de modelos, relaciones, restricciones y datos de ejemplo.

## Dónde se guardan los datos

La aplicación utiliza la conexión SQLite que ya trae Laravel y el archivo `database/database.sqlite`. Las pruebas utilizan SQLite en memoria y no borran la base de la aplicación.

No se han creado conexiones adicionales ni comandos Artisan propios.

## Ver las tablas en PhpStorm

1. Abre el panel Database.
2. Añade una fuente de datos SQLite.
3. Selecciona el archivo `database/database.sqlite` del proyecto.
4. Si PhpStorm solicita el controlador SQLite, permite su descarga.
5. Comprueba la conexión y abre las tablas.

Si tu edición no incluye el panel Database, puedes consultar la base con un visor SQLite o mediante Tinker.

## Comandos habituales

Desde la terminal de PhpStorm, dentro de la carpeta que contiene `artisan`:

```powershell
composer install
php artisan migrate
php artisan db:seed
php artisan test
```

En una copia nueva, si no existe `.env`, copia `.env.example` a `.env` y ejecuta `php artisan key:generate`. No sustituyas un `.env` que ya existe. La configuración SQLite por defecto de Laravel es suficiente.

`migrate` añade las migraciones pendientes sin borrar las tablas existentes. `db:seed` carga los ejemplos del sprint. El seeder se puede repetir sin duplicar esos ejemplos.

## Reconstruir desde cero

Solo en una base local que puedas borrar y después de guardar lo que necesites:

```powershell
php artisan migrate:fresh --seed
php artisan test
```

`migrate:fresh` elimina todas las tablas de la base configurada y las crea otra vez. No lo uses para conservar datos. Para ejecutar únicamente las pruebas utiliza `php artisan test`: trabaja en memoria.

## Organización de los seeders

Cada entidad tiene su propio archivo: `UserSeeder`, `ProductSeeder`, `OrderSeeder`, etc. Las tablas pivote tienen `ProductTagSeeder`, `PostTagSeeder`, `CouponUserSeeder` y `GiveawayEntrySeeder`. `GiveawayWinnerSeeder` asigna el ganador después de cargar las participaciones. `DatabaseSeeder` los llama en el orden necesario para que existan las referencias. También fija y restaura la fecha de los ejemplos. No hay seeders grandes que creen varias entidades distintas.

Las migraciones también están separadas: cada archivo de creación contiene una sola tabla. `Nothing to migrate` significa que todas las migraciones disponibles ya están aplicadas.

## Datos incluidos

- 4 clientes, con perfil, dirección y carrito.
- 4 categorías, 3 marcas, 8 productos y 16 variantes.
- Imágenes de ejemplo, etiquetas y traducciones en español e inglés.
- 4 pedidos, 8 líneas y pagos pendientes o pagados coherentes con sus importes.
- 3 reglas de descuento y 2 cupones, incluido uno caducado.
- 1 suscripción VIP con su pago.
- 2 sorteos con 4 participantes cada uno; uno tiene ganador.
- 2 publicaciones traducidas y etiquetadas.
- 6 registros de documentos.

Las imágenes y PDF son rutas de ejemplo, no archivos generados. La generación y subida de esos archivos corresponde a sprints posteriores.

Usuarios de demostración: `cliente1@fitstore.test` a `cliente4@fitstore.test`. Contraseña local de ejemplo: `password`. Las pantallas de autenticación todavía no están implementadas.

Los seeders usan fechas fijas alrededor del 18 de octubre de 2026. Con el paso del tiempo, las fechas de vigencia se conservan: un ejemplo de VIP activo en esa fecha dejará de estar vigente después del 1 de noviembre de 2026.

## Ejemplos para comprobar relaciones

Inicia Tinker:

```powershell
php artisan tinker
```

Tinker utiliza la conexión habitual del proyecto.

Consulta un producto y sus relaciones:

```php
$product = App\Models\Product::with('category', 'brand', 'variants', 'tags')->first();
$product->variants;
$product->tags->first()->pivot->priority;
$product->tags->first()->pivot->is_visible;
```

Comprueba un pedido y sus importes:

```php
$order = App\Models\Order::with('items', 'payments')->first();
$order->items->sum('line_total_cents') === $order->subtotal_cents;
$order->payments->first()->amount_cents === $order->total_cents;
```

Comprueba cupón y participaciones:

```php
$coupon = App\Models\Coupon::where('code', 'BIENVENIDA10')->first();
$coupon->users->first()->pivot->times_used;
$giveaway = App\Models\Giveaway::where('status', 'closed')->first();
$giveaway->users->contains($giveaway->winner);
```

Las pruebas y los seeders contienen ejemplos ejecutables de `attach`, `updateExistingPivot` y `syncWithoutDetaching`. El registro de uso de un cupón no implementa todavía el checkout: la validación completa de compra y la actualización transaccional de stock pertenecen al Sprint 4.

## Pruebas incluidas

- Cada factory crea una entidad válida.
- Relaciones uno a uno, uno a muchos y muchos a muchos.
- Lectura y actualización de prioridad y visibilidad en product_tag.
- Contador y fecha de uso en coupon_user.
- Participaciones sin duplicados y ganador inscrito.
- Restricciones para impedir precios negativos, importes decimales, cantidades inválidas, estados desconocidos y duplicados.
- Pagos con un único destino y fecha obligatoria cuando están pagados.
- Historial de pedido conservado al editar o retirar productos y usuarios.
- Contraseñas almacenadas como hash.
- Carga repetida de seeders sin duplicar los ejemplos ni cambiar sus datos.
- Reconstrucción completa dos veces con `migrate:fresh --seed` en memoria y comparación de los datos de negocio. Se excluyen los hashes aleatorios de contraseña y el registro técnico de migraciones.

## Resultado de la comprobación

- `php artisan migrate`: las 36 migraciones constan como aplicadas en la base habitual; no hay migraciones pendientes.
- `php artisan db:seed`: los seeders de entidades y relaciones terminan correctamente.
- `php artisan test`: 81 pruebas correctas y 273 comprobaciones.
- Reconstrucción desde cero y claves foráneas comprobadas mediante la prueba Sprint2RebuildTest.
- Se conserva una copia anterior a las migraciones en `storage/app/private/backups/antes-sprint-2.sqlite`.
- Antes de separar los archivos se guardó otra copia en `storage/app/private/backups/antes-separar-migraciones.sqlite`. Se adaptó únicamente el historial de migraciones a los nombres separados y se comprobó que las tablas y sus datos no cambiaron.
- No se han creado comandos Artisan propios ni conexiones de base de datos adicionales.

Composer incorpora Pest y su integración con Laravel como dependencias de desarrollo. `composer.lock` guarda las versiones utilizadas. Es necesario incluir ambos archivos de Composer en la entrega.

No se afirma una cobertura global del 85 %. Ese requisito es del proyecto completo y se trabajará al ampliar las pruebas de los sprints restantes.

## Commits sugeridos

Revisa los archivos desde GitHub Desktop y agrúpalos así:

1. `Sprint 2: documentar el diseño de datos` — documentación de diseño.
2. `Sprint 2: crear tablas y relaciones` — migraciones y modelos.
3. `Sprint 2: preparar factories y seeders` — datos de prueba.
4. `Sprint 2: probar modelos relaciones y restricciones` — tests, phpunit.xml, composer.json, composer.lock y guía de comprobación.

El repositorio Git está en la carpeta principal de la aplicación. En GitHub Desktop se debe seleccionar `C:/Users/juano/Desktop/php/FitStore`. El commit guarda una versión local; para publicarla se necesita conectar el repositorio remoto y hacer push.
