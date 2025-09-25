<?php
header('Content-Type: application/json');
include("../seguridad.php");
include("op_salidaMaterial.php"); // Para obtenerDato()
include("conexion.inc"); // Para conecta()

// Leer JSON enviado
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['claves']) || !is_array($input['claves'])) {
    echo json_encode([]);
    exit;
}

$claves = $input['claves'];
$resultado = array();

foreach ($claves as $clave) {
    $nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $clave);
    $resultado[$clave] = $nombre ? $nombre : "No encontrado";
}

echo json_encode($resultado);
exit;
?>
