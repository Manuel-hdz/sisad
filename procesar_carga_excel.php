<?php
session_start();
include("../seguridad.php");
include("op_salidaMaterial.php");

// Verificar archivo
if (isset($_FILES['archivo_excel']) && $_FILES['archivo_excel']['error'] == 0) {
    require_once 'includes/PHPExcel/Classes/PHPExcel.php';
    require_once 'includes/PHPExcel/Classes/PHPExcel/IOFactory.php';

    $file = $_FILES['archivo_excel']['tmp_name'];

    try {
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $sheet = $objPHPExcel->getActiveSheet();
        $rows = $sheet->toArray();

        $materiales = [];

        foreach ($rows as $i => $row) {
            if ($i == 0) continue; // saltar encabezado
            $clave = trim($row[1]);       // columna B = clave
            $cantidad = trim($row[2]);    // columna C = cantidad salida
            $idEquipo = trim($row[3]);    // columna D = id equipo

            if ($clave === '' || !is_numeric($cantidad)) continue;

            // Obtener nombre del material usando tu función
            $nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $clave);
            if (!$nombre) $nombre = "No encontrado";

            $materiales[] = [
                'clave' => $clave,
                'nombre' => $nombre,
                'cantidad' => $cantidad,
                'idEquipo' => $idEquipo
            ];
        }

        // Guardar en sesión para previsualización
        $_SESSION['preview_excel'] = $materiales;

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'count' => count($materiales), 'materiales' => $materiales]);

    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'No se recibió archivo o hubo error al subirlo.']);
}
