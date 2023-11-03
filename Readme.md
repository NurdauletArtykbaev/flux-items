Установите пакет с помощью Composer:

``` bash
 composer require Nurdaulet/flux-items
```

## Конфигурация
После установки пакета, вам нужно опубликовать конфигурационный файл. Вы можете сделать это с помощью следующей команды:
``` bash
php artisan vendor:publish --tag=flux-items-config

php artisan vendor:publish --provider="Nurdaulet\FluxCatalog\FluxCatalogServiceProvider"
php artisan vendor:publish --provider="Nurdaulet\FluxBase\FluxBaseServiceProvider"
php artisan vendor:publish --provider="Nurdaulet\FluxItems\FluxItemsServiceProvider"
php artisan vendor:publish --tag flux-catalog-config
php artisan vendor:publish --tag flux-base-config
php artisan vendor:publish --tag flux-items-config

```

Вы можете самостоятельно добавить поставщика услуг административной панели Filament в файл config/app.php.
``` php
'providers' => [
    // ...
    Nurdaulet\FluxItems\FluxItemsFilamentServiceProvider::class,
];
```



