<?php
session_start();

if(isset($_FILES['archivo_excel'])){
    $file = $_FILES['archivo_excel']['tmp_name'];
    $materiales = array();

    if (($handle = fopen($file, "r")) !== FALSE) {
        $fila = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $fila++;
            if($fila == 1) continue; // saltamos encabezados
            if(count($data) < 3) continue; // fila incompleta
            if(!empty($data[0])) { 
                $materiales[] = array(
                    'clave' => $data[0],
                    'nombre' => $data[1],
                    'cantidad' => floatval($data[2]),
                    'idEquipo' => isset($data[3]) ? $data[3] : 'N/A'
                );
            }
        }
        fclose($handle);
        echo json_encode(array('success'=>true, 'materiales'=>$materiales));
    } else {
        echo json_encode(array('success'=>false, 'error'=>'No se pudo abrir el archivo.'));
    }
} else {
    echo json_encode(array('success'=>false, 'error'=>'No se recibió archivo.'));
}
