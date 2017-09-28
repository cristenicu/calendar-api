<?php

// Models
$models = glob(__DIR__ . '/Api/Domain/Model/*.php');
foreach ($models as $model) {
    require_once($model);
}

// Repositories
$repositories = glob(__DIR__ . '/Api/Domain/Repository/*.php');
foreach ($repositories as $repository) {
    require_once($repository);
}

// Controllers
$controllers = glob(__DIR__ . '/Api/Presentation/Controller/*.php');
foreach ($controllers as $controller) {
    require_once($controller);
}
