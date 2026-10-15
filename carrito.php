<?php

include("setup/setup.php");
session_start();

switch($_POST['op'])
{
    case "1": insertar();
        break;
    case "2": eliminaritems();
        break;
    case "3": eliminartodo();
        break;
}

function insertar()
{
    $id = filter_input(INPUT_POST, 'iditems', FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 9999]]);
    if ($id === false || $id === null) {
        http_response_code(400);
        exit('Solicitud inválida: identificador de producto no válido.');
    }
 
    $stmt = conectar()->prepare("SELECT id, nombre, precio FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
    if (!$datos) {
        http_response_code(404);
        exit('Producto no encontrado.');
    }
 
    if (!isset($_SESSION["carrito"])) {
        $_SESSION["carrito"] = array();
    }
    $pos = count($_SESSION["carrito"]) + 1;
    $productos = array("posicion" => $pos, "id" => $datos['id'],
                       "nombre" => $datos['nombre'], "precio" => $datos['precio']);
    $_SESSION["carrito"][$pos] = $productos;
}

function eliminaritems()
{
    unset($_SESSION["carrito"][$_POST['pos']]);
}

function eliminartodo()
{
    session_destroy();
}
?>