<?php

require_once 'bootstrap.php';

function jsonResponse($statusCode, $message, $data)
{
    header('Content-Type: application/json');

    return json_encode([
        'status' => $statusCode,
        'message' => $message,
        'data' => $data
    ]);
}

$params = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$controllerName = 'Api\\Presentation\\Controller\\' . ucfirst($params[0]) . 'Controller';
$controller = new $controllerName;

switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        if (isset($params[1])) {
            // get all
            echo $controller->get($params[1]);
        } else {
            // get one by id
            echo $controller->all();
        }

        break;
    case 'POST':
        // add new
        echo $controller->add($_POST);

        break;
    case 'PUT':
        // edit by id
        parse_str(file_get_contents("php://input"), $request);
        echo $controller->update($params[1], $request);

        break;
    case 'DELETE':
        // delete by id
        echo $controller->delete($params[1]);

        break;
}
