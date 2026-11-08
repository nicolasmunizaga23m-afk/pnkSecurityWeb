<?php

// Mantener errores fuera de la respuesta HTTP y registrarlos en el log de PHP.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

function conectar()
{
    static $con = null;

    if ($con instanceof mysqli) {
        return $con;
    }

    $configPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
    $config = is_readable($configPath)
        ? parse_ini_file($configPath, true, INI_SCANNER_RAW)
        : false;

    if (
        $config === false ||
        !isset($config['database']['host'], $config['database']['user'],
            $config['database']['password'], $config['database']['name'])
    ) {
        error_log('No se pudo cargar la configuración de la base de datos.');
        throw new RuntimeException('Error de configuración de la base de datos.');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $con = new mysqli(
        $config['database']['host'],
        $config['database']['user'],
        $config['database']['password'],
        $config['database']['name']
    );
    $con->set_charset('utf8mb4');

    return $con;
}

function quitarespacios($titulo)
{
    $titulo = str_replace(' ', '', $titulo);
    $cadena = str_replace('ñ', '', $titulo);
    $cadena = str_replace('Ñ', '', $cadena);
    return $cadena;
}

function moneda_chilena($numero)
{
    $numero = (string) $numero;
    $tmp = '';
    $pos = 1;
    for ($i = strlen($numero) - 1; $i >= 0; $i--) {
        $tmp .= substr($numero, $i, 1);
        if ($pos % 3 === 0 && $pos !== strlen($numero)) {
            $tmp .= '.';
        }
        $pos++;
    }
    return '$ ' . strrev($tmp);
}
?>
