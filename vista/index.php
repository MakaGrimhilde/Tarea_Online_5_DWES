<?php

header("HTTP/1.1 200");

require_once "../controlador/controlador.php";

//definición de objeto tipo controlador
$controlador = new controlador();

//serie de ifs que según el tipo de petición se accede a la función correspondiente
if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    $controlador->get();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $controlador->post();
}

if($_SERVER['REQUEST_METHOD'] == 'PUT'){

    $controlador->put();
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $controlador->delete();
}

?>