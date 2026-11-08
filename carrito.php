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
    $pos = filter_input(
        INPUT_POST,
        'pos',
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if (
        $pos === false ||
        $pos === null ||
        !isset($_SESSION['carrito']) ||
        !is_array($_SESSION['carrito']) ||
        !array_key_exists($pos, $_SESSION['carrito'])
    ) {
        http_response_code(400);
        exit('Posición de carrito no válida.');
    }

    unset($_SESSION['carrito'][$pos]);
}

function eliminartodo()
{
    session_destroy();
}
?>
