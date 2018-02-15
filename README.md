[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/delfimov/GDImage/blob/master/LICENSE)

# Session

PSR-11 compatible easy to use library for managing PHP built-in sessions. PDO handler included.

## Requirements

 * [PHP >= 5.6](http://www.php.net/)

## How to install

Add this line to your composer.json file:

```json
"delfimov/session": "~1.0"
```

or

```sh
composer require delfimov/session
```

## An example

See [`example`](example) directory for sources.


```php
require_once __DIR__ . '/../vendor/autoload.php';
$session = new DElfimov\Session\Session(
    new DElfimov\Session\Handlers\PDOHandler(
            new \PDO('mysql:dbname=testdb;host=127.0.0.1', 'dbuser', 'dbpass')
    )
);

$session->set('a', 'value a');

try {
    echo $session->get('a');
} catch (\Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

if ($session->has('b')) {
    echo 'Wonder!';
}

$session->remove('a');

```

## TODO

 * Better code coverage
 * Handlers 
 