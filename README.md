Clara entity generator
===============

## Installation

```php
composer require ceddyg/clara-entity-generator
```

Add to your providers in 'config/app.php'
```php
CeddyG\ClaraEntityGenerator\EntityGeneratorServiceProvider::class,
```

Then to publish the files.
```php
php artisan vendor:publish --provider="CeddyG\ClaraEntityGenerator\EntityGeneratorServiceProvider"
```

## Use

You can select all the table in it and define what file you want :

- Controller
- Model
- Repository (that extend ceddyg/query-builder-repository)
- Request
- Index view
- Form view (to create or edit)
- Traduction files (en and fr)

You have just to define the relations, if they are hasMany or belongsToMany relations and what files you want to create

You can edit the generator to custom your files. The generator is in app/Services/Clara/Generator and the stubs are in ressources/stubs.
