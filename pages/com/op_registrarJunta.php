<?php

/* Creacion de arreglo de responsables */
function agregarResposable($responsables){
    $area = $_POST['cmb_area_resp'];
    $resp = $_POST['txt_responsable'];
    $id_resp = $_POST['txt_noEmpl'];
    array_push($responsables, array('area'=>$area, 'responsable'=>$resp, 'id_resp'=>$id_resp) );
    return $responsables;
}

/* Agregar actividad a la base de datos */
function agregarActividad() {
    $band = true;
    $id_actividad = obtenerID() + 1;

    $actividad = strtoupper($_POST['txt_actividad']);
    $fecha_ini = $_POST['txt_fecha_ini'];
    $fecha_fin = $_POST['txt_fecha_fin'];
    $observaciones = strtoupper($_POST['txt_observaciones']);
    $estado = "PENDIENTE";
    $porcentaje = 0;

    $conn = conecta("bd_gerencia");

    $sentencia_sql = 
    "INSERT INTO actividades_junta (
        actividad,
        fecha_ini,
        fecha_fin,
        observaciones,
        estado,
        porcentaje
    ) VALUES (
        '$actividad',
        '$fecha_ini',
        '$fecha_fin',
        '$observaciones',
        '$estado',
        $porcentaje
    )";

    $rs = mysql_query($sentencia_sql);

    if ($rs) {
        $band = agregarResponsables($id_actividad);
    } else {
        $band = false;
    }

    if ($band) {
        mysql_close($conn);
        registrarOperacion("bd_gerencia",$id_actividad,"RegistrarActJunta",$_SESSION['usr_reg']);
        ?>
<script>
    setTimeout(() => {
        alert("Registro de la actividad realizado con exito");
    }, 1000);
</script>
<?php
    } else {
        mysql_close($conn);
        eliminar($id_actividad);
        ?>
<script>
    setTimeout(() => {
        alert("Hubo un error al momento de realizar el registro");
    }, 1000);
</script>
<?php
    }
    
}

/* Agregar responsables de la actividad a la base de datos */
function agregarResponsables($id) {
    $band = true;

    $conn = conecta("bd_gerencia");

    foreach ( $_SESSION['responsables'] as $resp) {
        $area = strtoupper($resp['area']);
        $responsable = strtoupper($resp['responsable']);
        $sentencia_sql = 
        "INSERT INTO responsables_junta (
            id_actividad,
            area,
            responsable
        ) VALUES (
            $id,
            '$area',
            '$responsable'
        )";

        $rs = mysql_query($sentencia_sql);

        if(!$rs) {
            echo mysql_error();
            $band = false;
        }
    }

    return $band;
}

/* Obtener id para eliminar registros en caso de error */
function obtenerID() {
    $conn = conecta("bd_gerencia");

    $sentencia_sql = "SELECT id_actividad FROM actividades_junta ORDER BY id_actividad DESC LIMIT 1";

    $rs = mysql_query($sentencia_sql);

    if ( $dato = mysql_fetch_array($rs) ) {
        return $dato['id_actividad'];
    }

    mysql_close($conn);
}

/* Eliminar registros de la base de datos */
function eliminar($id) {
    $conn = conecta("bd_gerencia");

    $sentencia_sql = "DELETE FROM responsables_junta WHERE id_actividad = $id";
    mysql_query($sentencia_sql);

    $sentencia_sql = "DELETE FROM actividades_junta WHERE id_actividad = $id";
    mysql_query($sentencia_sql);

    mysql_close($conn);
}

/* Obtener titulo para la tabla de actividades junta */
function obtenerTituloTabla() {
    $titulo = "ACTIVIDADES";
    
    if($_POST['cmb_estado'] != '')
        $titulo .= " ".strtoupper($_POST['cmb_estado'])."S";
    if($_POST['txt_fecha'] != '')
        $titulo .= " DEL ".modFecha($_POST['txt_fecha'],7);
    if($_POST['cmb_area'] != '')
        $titulo .= " CORRESPONDIENTES A ".strtoupper($_POST['cmb_area']);
    
    return $titulo;
}

/* Creacion de la consulta para realizar la busqueda de actividades */
function crearConsultaJunta() {
    $condiciones = "";
    $sentencia_mysql = 
    "SELECT T1.* 
    FROM  `actividades_junta` AS T1
    JOIN responsables_junta AS T2
    USING ( id_actividad )";
    
    if($_POST['cmb_estado'] != '') {
        $condiciones .= " T1.estado LIKE '".$_POST['cmb_estado']."'";
    }
    if($_POST['txt_fecha'] != '') {
        if($condiciones == '')
            $condiciones .= " T1.fecha_ini LIKE '".$_POST['txt_fecha']."'";
        else
            $condiciones .= " AND T1.fecha_ini LIKE '".$_POST['txt_fecha']."'";
    }
    if($_POST['cmb_area'] != '') {
        if($condiciones == '')
            $condiciones .= " T2.area LIKE '".$_POST['cmb_area']."'";
        else
            $condiciones .= " AND T2.area LIKE '".$_POST['cmb_area']."'";
    }
    if($_POST['txt_actividad'] != '') {
        if($condiciones == '')
            $condiciones .= " T1.actividad LIKE '%".$_POST['txt_actividad']."%'";
        else
            $condiciones .= " AND T1.actividad LIKE '%".$_POST['txt_actividad']."%'";
    }

    if ($condiciones != '') {
        $sentencia_mysql .= " WHERE".$condiciones;
    }
    $sentencia_mysql .= " GROUP BY T1.id_actividad";

    return $sentencia_mysql;
}

/* Muestra las actividades de la junta */
function mostrarTablaActividades() {
    $conn = conecta("bd_gerencia");

    $rs = mysql_query( crearConsultaJunta() );

    if ( $rs ){
        ?>
<table class="tabla_frm encabezado-fijo" cellpadding="5" width=100%>
    <thead class="text-center">
        <th class="columna-fija nombres_columnas">ACTIVIDAD</th>
        <th class="nombres_columnas">AREA</th>
        <th class="nombres_columnas">FECHA INICIAL</th>
        <th class="nombres_columnas">FECHA FIN</th>
        <th class="nombres_columnas">ESTADO</th>
        <th class="nombres_columnas">OBSERVACIONES</th>
        <th class="nombres_columnas">PROGRESO</th>
        <th class="nombres_columnas">EDITAR</th>
    </thead>
    <tbody>
        <?php
        $cont = 0;
        $clase_renglon = "";
        while ($datos = mysql_fetch_array($rs)) {
            $estado = "";
            $clase = "";
            if ($datos['estado'] == 'TERMINADA'){
                $estado = "<i class='far fa-smile fa-lg'></i>";
                $clase = "face-terminado";
            }
            if ($datos['estado'] == 'PENDIENTE'){
                $estado = "<i class='far fa-meh fa-lg'></i>";
                $clase = "face-pendiente";
            }
            if ($datos['estado'] == 'ALERTA'){
                $estado = "<i class='far fa-angry fa-lg'></i>";
                $clase = "face-alerta";
            }
            if( $cont%2 == 0) $clase_renglon = "renglon_blanco";
            else $clase_renglon = "renglon_gris";
        ?>
        <tr>
            <td class="vertical-center fila-fija <?php echo $clase_renglon; ?>"><?php echo $datos['actividad']; ?></td>
            <td class="vertical-center <?php echo $clase_renglon; ?>">
                <?php echo consultarResponsablesAct($datos['id_actividad'], 1); ?></td>
            <td class="vertical-center <?php echo $clase_renglon; ?>"><?php echo modFecha($datos['fecha_ini'],7); ?>
            </td>
            <td class="vertical-center <?php echo $clase_renglon; ?>"><?php echo modFecha($datos['fecha_fin'],7); ?>
            </td>
            <td class="text-center vertical-center <?php echo $clase; ?>"><?php echo $estado; ?></td>
            <td class="vertical-center <?php echo $clase_renglon; ?>"><?php echo $datos['observaciones']; ?></td>
            <td class="text-center vertical-center <?php echo $clase_renglon; ?>">
                <div class="progress bg-secondary">
                    <div class="progress-bar bg-success" role="progressbar"
                        style="width: <?php echo $datos['porcentaje']; ?>%" aria-valuenow="10" aria-valuemin="0"
                        aria-valuemax="100">
                        <?php echo $datos['porcentaje']; ?>%
                    </div>
                </div>
            </td>
            <td class="text-center vertical-center puntero <?php echo $clase_renglon; ?>">
                <form action="frm_modificarJunta.php" method="POST">
                    <input type="hidden" name="txt_idActividad" id="txt_idActividad"
                        value="<?php echo $datos['id_actividad']; ?>">
                    <button class="btn btn-sm"><i class="far fa-edit fa-lg" style="color: green"></i></button>
                </form>
            </td>
        </tr>
        <?php
            $cont++;
        }
        ?>
    </tbody>
</table>
<?php
        mysql_close($conn);
        return array(false,"");
    } else {
        return  array(true,mysql_error());
    }
}

/* Consulta los responsables de una actividad */
function consultarResponsablesAct($id, $opc) {
    $con_resp = conecta("bd_gerencia");
    $areas = "";

    $sentencia_resp = 
    "SELECT * 
    FROM  `responsables_junta` 
    WHERE  `id_actividad` = $id";

    $rs_resp = mysql_query($sentencia_resp);

    if ( $rs_resp ) {
        while ( $datos_resp = mysql_fetch_array($rs_resp) ) {
            if ($opc == 1) {
                $areas .= $datos_resp['area']."<br>";
            } else {
                $areas .= $datos_resp['area']." / ";
            }
        }
    }

    $areas = substr($areas,0,-2);

    return $areas;
}

/* Actualizar el estado de los registros que sobrepasron la fecha final */
function actividadesAlerta() {
    $conn = conecta("bd_gerencia");

    $sentencia_mysql = 
    "UPDATE actividades_junta SET estado =  'ALERTA' WHERE (
        DATE(  `fecha_fin` ) < CURDATE( ) AND fecha_fin NOT LIKE  '0000-00-00'
    ) AND estado NOT LIKE  'TERMINADA'";

    $rs = mysql_query($sentencia_mysql);

    mysql_close($conn);
}

/* Obtener los responsables de una actividad */
function obtenerResponsables($id_actividad) {
    $arreglo = array();

    $sql_areasResp = 
    "SELECT * 
    FROM  `responsables_junta` 
    WHERE  `id_actividad` = $id_actividad";

    $rs_areasResp = mysql_query( $sql_areasResp );
    
    while ( $areas = mysql_fetch_array( $rs_areasResp ) ) {
        array_push($arreglo, array('area'=>$areas['area'], 'responsable'=>$areas['responsable']));
    }

    return $arreglo;
}

function mostrarTablaResponsables( $arr_responsables ) {

    ?>
    <table class="tabla_frm encabezado-fijo" cellpadding="5" width=100%>
        <thead class="text-center">
            <tr>
                <th class="nombres_columnas">Area</th>
                <th class="nombres_columnas">Responsable</th>
                <th class="nombres_columnas">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
    $cont = 1;

    foreach ( $arr_responsables as $key=>$resp) {
    ?>
            <tr <?php if( $cont%2 == 0) { ?> class='renglon_blanco' <?php } else { ?> class='renglon_gris' <?php } ?>>
                <td><?php echo strtoupper($resp['area']) ?></td>
                <td><?php echo strtoupper($resp['responsable']) ?></td>
                <td class="text-center">
                    <input type="hidden" value="<?php echo $key; ?>" name="txt_eliminar<?php echo $key; ?>" id="txt_eliminar<?php echo $key; ?>">
                    <button class="btn btn-sm" name="btn_eliminar" onmouseover="$('#aux').val($('#txt_eliminar<?php echo $key; ?>').val())"
                        onclick="document.getElementById('frm_junta').setAttribute('novalidate','true');">
                        <i class="far fa-times-circle fa-lg" style="color: red"></i>
                        <?php  ?>
                    </button>
                </td>
            </tr>
            <?php
    $cont++;
    }
    ?>
        </tbody>
    </table>
    <input type="hidden" name="aux" id="aux">
    <?php
}

function eliminarResponsables($id) {
    $conn = conecta("bd_gerencia");

    $sentencia_mysql = "DELETE FROM responsables_junta WHERE id_actividad = $id";
    mysql_query($sentencia_mysql);

    mysql_close($conn);
}

function actualizarActividad($id) {
    $conn = conecta("bd_gerencia");

    $actividad = strtoupper($_POST['txt_actividad']);
    $fecha_ini = $_POST['txt_fecha_ini'];
    $fecha_fin = $_POST['txt_fecha_fin'];
    $estado = "PENDIENTE";
    $observaciones = strtoupper($_POST['txt_observaciones']);
    $porcentaje = $_POST['txt_porcentaje'];

    if ($porcentaje == 100) {
        $estado = "TERMINADA";
    }

    $sentencia_mysql = 
    "UPDATE actividades_junta SET 
    actividad = '$actividad',
    fecha_ini = '$fecha_ini',
    fecha_fin = '$fecha_fin',
    observaciones = '$observaciones',
    estado = '$estado',
    porcentaje = '$porcentaje' 
    WHERE id_actividad = $id";

    $rs = mysql_query($sentencia_mysql);

    if ($rs) {
        registrarOperacion("bd_gerencia",$id,"ModificarActJunta",$_SESSION['usr_reg']);
        ?>
        <script>
            setTimeout( () => {
                alert("Modificacion Realizada con exito");
            },1000);
        </script>
        <?php
    }
    else {
        ?>
        <script>
            setTimeout( () => {
                alert("Ocurrio un error al intentar modificar los datos");
            }, 1000);
        </script>
        <?php
    }
}

function actividadesExportarExcel() {
    
}

?>