<?php

passthru('php bin/console doctrine:schema:update --env=test --force');
passthru('php bin/console doctrine:fixtures:load --env=test -n');
require __DIR__ . '/autoload.php';
