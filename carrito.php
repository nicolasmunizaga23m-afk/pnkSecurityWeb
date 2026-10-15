<?php
include("setup/setup.php");
session_start();

$op = isset($_POST['op']) ? $_POST['op'] : '';

switch($op)
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
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $id < 1 || $id > 9999) {
        http_response_code(400);
        exit('Solicitud inválida: identificador de producto no válido.');
    }

    if ($cantidad !== null && ($cantidad === false || $cantidad < 1 || $cantidad > 50)) {
        http_response_code(400);
        exit('Solicitud inválida: la cantidad debe ser un número entero entre 1 y 50.');
    }

    $conexion = conectar();
    $stmt = $conexion->prepare("SELECT id, nombre, precio FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $datos = $result->fetch_array();
    
    // Lógica del carrito posterior...
}