<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Easy to use library for managing PHP built-in sessions">
    <meta name="author" content="Dmitry Elfimov <elfimov@gmail.com>">

    <title>Session</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>


<div class="container">

    <div class="starter-template">
        <h1>Session</h1>
        <p class="lead">
            Easy to use library for managing PHP built-in sessions
        </p>
    </div>

    <hr>

<pre><?php

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

?></pre>

</body>
</html>



