<?php

require_once __DIR__ . '/setup.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$email = filter_input(INPUT_POST, 'frmusuario', FILTER_VALIDATE_EMAIL);
$password = $_POST['frmpassword'] ?? null;

if ($email === false || $email === null || !is_string($password)) {
    header('Location: ../index.php?id=1', true, 303);
    exit;
}

try {
    $conexion = conectar();
    $stmt = $conexion->prepare(
        'SELECT nombre, password FROM usuarios WHERE email = ? LIMIT 1'
    );
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario && password_verify($password, $usuario['password'])) {
        session_regenerate_id(true);
        $_SESSION['nombre'] = $usuario['nombre'];
    }
} catch (Throwable $e) {
    error_log('Error durante la autenticación: ' . $e->getMessage());
}

header('Location: ../index.php?id=1', true, 303);
exit;
?>
