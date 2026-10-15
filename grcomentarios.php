<?php
include("setup/setup.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Solicitud rechazada: Método no permitido.');
}

$usuario = trim((string) filter_input(INPUT_POST, 'usuario', FILTER_UNSAFE_RAW));
$comentario = trim((string) filter_input(INPUT_POST, 'comentario', FILTER_UNSAFE_RAW));
$id_restaurante = filter_input(INPUT_POST, 'id_restaurante', FILTER_VALIDATE_INT);

if (
    $usuario === '' || mb_strlen($usuario) < 3 || mb_strlen($usuario) > 60 ||
    !preg_match('/^[\p{L}\s]+$/u', $usuario) ||
    $comentario === '' || mb_strlen($comentario) < 5 || mb_strlen($comentario) > 300
) {
    http_response_code(400);
    exit('Solicitud inválida: verifique el formato y longitud de los datos ingresados.');
}

$conexion = conectar();
$stmt = $conexion->prepare("INSERT INTO comentarios (usuario, comentario, id_restaurante) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $usuario, $comentario, $id_restaurante);
$stmt->execute();

header('Location: index.php?id=' . $_SESSION['id']);
?>