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

## How to configure

The session management system supports a number of configuration options 
which you can place in your php.ini file or redefine in your code.

The most important settings with recommended values:

```ini
session.name = PHPSESSID
session.gc_probability = 1
session.gc_divisor = 1000
session.gc_maxlifetime = 2592000
; lifetime of sessions = 60*60*24*30 = 30 days
session.use_cookies = 1
session.use_only_cookies = 1
session.cookie_lifetime = 2592000
; same as session lifetime
```

I highly recommend to use https protocol and marks the cookie as accessible 
only through the HTTP protocol.

```ini
session.cookie_secure = 1
session.cookie_httponly = 1
```

See [PHP Sessions Runtime Configuration](https://secure.php.net/manual/en/session.configuration.php) 
for more details.

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
 