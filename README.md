### Prueba API en Laravel

## Instrucciones

Lo más fácil es correr la aplicación en docker-compose.

```shell
cp .env.example .env
docker-compose up -d
```

Alternativamente, para correr la aplicación directamente en máquina habría que
```shell
# actualizar las dependencias de composer
composer update
# migrar la base de datos asegurandonos de 
# que en .env estan correctamente informados los datos de conexión a BD
php artisan migrate
# y correr el servidor de desarrollo en local
php artisan serve --host=0.0.0.0   
```

En este punto ya debería de estar disponible la aplicación en la 
url http://localhost:8000/api/tiendas por ejemplo.

Para añadir tiendas (y productos)
```shell
curl --location 'http://127.0.0.1:8000/api/tiendas' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'nombre=Pizza' \
--data-urlencode 'productos=[{"nombre": "lego", "cantidad": 3}]'
```
Para mostrar información sobre una tienda
```shell
curl --location 'http://127.0.0.1:8000/api/tiendas/1'
```
Para modificar una tienda
```shell
curl --location --request PATCH 'http://127.0.0.1:8000/api/tiendas/1' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'nombre=Segovia' \
--data-urlencode 'productos=[]'
```
Para eliminar una tienda
```shell
curl --location --request DELETE 'http://127.0.0.1:8000/api/tiendas/1'
```

Para correr los tests.
```shell
docker-compose exec laravel php vendor/bin/phpunit
``` 
en docker-compose o 
```shell
php venodr/bin/phpunit
``` 
directamente.
