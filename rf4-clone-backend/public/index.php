<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\App;

$config = require __DIR__ . '/../config/database.php';

$app = new App();

require __DIR__ . '/../routes/api.php';

$app->run();
