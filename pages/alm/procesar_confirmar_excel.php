<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE); // evita warnings que rompan JSON

if (!isset($_POST['materiales_excel'])) {
    echo json_encode(array('success' => false, 'error' => 'No se recibieron materiales'));
    exit;
}

$materiales = json_decode($_POST['materiales_excel'], true);
if (!$materiales || !is_array($materiales)) {
    echo json_encode(array('success' => false, 'error' => 'Datos inválidos'));
    exit;
}

// Inicializar arreglo en sesión si no existe
if (!isset($_SESSION['datosSalida'])) {
    $_SESSION['datosSalida'] = array();
}

foreach ($materiales as $mat) {
    $clave      = isset($mat['clave']) ? $mat['clave'] : '';
    $nombre     = isset($mat['nombre']) ? $mat['nombre'] : '';
    $cantSalida = isset($mat['cantidad']) ? floatval($mat['cantidad']) : 0;
    $idEquipo   = isset($mat['idEquipo']) ? $mat['idEquipo'] : 'N/A';

    // Buscar si ya existe la combinación clave + idEquipo en la sesión
    $existe = false;
    foreach ($_SESSION['datosSalida'] as $idx => $reg) {
        if ($reg['clave'] == $clave && $reg['idEquipo'] == $idEquipo) {
            // Si ya existe, sumamos cantidades y recalculamos totales
            $_SESSION['datosSalida'][$idx]['cantSalida'] += $cantSalida;
            $_SESSION['datosSalida'][$idx]['costoTotal'] =
                number_format($_SESSION['datosSalida'][$idx]['cantSalida'] * $_SESSION['datosSalida'][$idx]['costoUnidad'], 2);
            $existe = true;
            break;
        }
    }

    // Si no existe, lo agregamos como nuevo registro
    if (!$existe) {
        $_SESSION['datosSalida'][] = array(
            'clave'         => $clave,
            'nombre'        => $nombre,
            'existencia'    => 0,  // igual que en btn_agregarOtro
            'cantSalida'    => $cantSalida,
            'costoUnidad'   => number_format(0, 2), // si no traes costo, pon 0 o cálculalo después
            'costoTotal'    => number_format($cantSalida * 0, 2),
            'idEquipo'      => $idEquipo,
            'catMaterial'   => '',
            'tipoMoneda'    => '',
            'cantRestante'  => 0,
            'idEntradas'    => '',
            'cantidadEntradas' => ''
        );
    }
}

echo json_encode(array('success' => true));
exit;
?>
