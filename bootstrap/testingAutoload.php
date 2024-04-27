<?php

passthru("php artisan --env='testing' migrate:fresh --force --seed");
require_once __DIR__ . '/../vendor/autoload.php';
