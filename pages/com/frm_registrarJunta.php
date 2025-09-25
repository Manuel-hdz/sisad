<!DOCTYPE html>
<html lang="es">
<?php
    include ("../seguridad.php"); 
    if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	} else {
        include("head_menu.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Sistema de Gestión Empresarial, Producción y Operación</title>

    <link rel="stylesheet" href="../../includes/b4/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <script src="../../includes/jquery-1.2.1.pack.js"></script>
    <script src="../../includes/ajax/busq_spider_2.js"></script>

    <style>
        #main_container {
            position: absolute;
            top: 130px;
        }

        #barra_titulo {
            position: absolute;
            top: 4px;
            left: 10px;
        }
    </style>
</head>

<body>
    <?php

    if( isset($_POST['btn_agreagarTarea']) ) { 
        agregarActividad();
    }

    $actividad = '';
    $fecha_ini = '';
    $fecha_fin = '';
    $observaciones = '';
    if ( isset($_POST['btn_agregar']) || isset($_POST['btn_eliminar']) ) {
        $actividad = $_POST['txt_actividad'];
        $fecha_ini = $_POST['txt_fecha_ini'];
        $fecha_fin = $_POST['txt_fecha_fin'];
        $observaciones = $_POST['txt_observaciones'];
    } 
    if ( !isset($_POST['btn_agregar']) && !isset($_POST['btn_eliminar']) ) {
        $_SESSION['responsables'] = array();
    }
    else if( isset($_POST['btn_eliminar']) ) {
        $key = $_POST['aux'];
        unset($_SESSION['responsables'][$key]);
    }
    else {
        $_SESSION['responsables'] = agregarResposable( $_SESSION['responsables'] );
    }
    ?>
    <div id="main_container" class="container m-md-3">
        <div class="row">
            <img src="../../images/title-bar-bg.gif" width="100%" height="30px" />
            <div class="titulo_barra" id="barra_titulo">Junta Administrativa</div>
        </div>

        <form method="POST" action="" id="frm_junta">
            <div class="row m-md-2">
                <div class="col col-md-7 border-right border-secondary">
                    <div class="alert alert-danger" style="position: fixed; visibility: hidden;" id="alertaArea">

                    </div>

                    <h5><span class="badge badge-success">Nueva Tarea</span></h5>
                    <hr>

                    <div class="form-group">
                        <label for="txt_actividad">Actividad</label>
                        <textarea class="form-control form-control-sm" maxlength="120" name="txt_actividad"
                            id="txt_actividad" rows="2" required><?php echo $actividad; ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cmb_area_resp">&Aacute;rea</label>
                            <input type="text" list="areaLista" id="cmb_area_resp" name="cmb_area_resp"
                                class="form-control form-control-sm" placeholder="Seleccionar un area"
                                onblur="validarArea()" required>
                            <datalist id="areaLista">
                                <option value="Almacen"></option>
                                <option value="Auditoria"></option>
                                <option value="Compras"></option>
                                <option value="Clinica"></option>
                                <option value="Desarrollo"></option>
                                <option value="Gomar"></option>
                                <option value="Mainmi"></option>
                                <option value="Saucito"></option>
                                <option value="Seguridad"></option>
                                <option value="Sistemas"></option>
                                <option value="Mtto. Desarrollo"></option>
                                <option value="Mtto. Zarpeo"></option>
                                <option value="Zarpeo"></option>
                            </datalist>
                        </div>

                        <div class="form-group col-md-8">
                            <label for="txt_responsable">Responsable</label>
                            <input type="text" name="txt_responsable" id="txt_responsable"
                                class="form-control form-control-sm" placeholder="Encargado de realizar actividad"
                                autocomplete="off" required
                                onkeyup="lookup(this, 'txt_noEmpl', 'bd_recursos', 'empleados', 'CONCAT(nombre,\' \',ape_pat,\' \',ape_mat)', 'id_empleados_empresa', '1')">

                            <div class="suggestionsBox" id="suggestions1"
                                style="display: none; position: absolute; z-index: 1;">
                                <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;"
                                    alt="upArrow">
                                <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                            </div>

                            <input type="hidden" name="txt_noEmpl" id="txt_noEmpl">
                        </div>
                        <button class="btn btn-sm btn-secondary" type="submit" name="btn_agregar" id="btn_agregar"
                            onclick="noRequiredAgregar()">Agregar</button>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="txt_fecha_ini">Fecha Inicial</label>
                            <input type="date" name="txt_fecha_ini" id="txt_fecha_ini"
                                class="form-control form-control-sm" value="<?php echo $fecha_ini ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txt_fecha_fin">Fecha Final</label>
                            <input type="date" name="txt_fecha_fin" id="txt_fecha_fin" onblur="validarFechas();"
                                class="form-control form-control-sm" value="<?php echo $fecha_fin ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txt_observaciones">Observaciones</label>
                        <textarea class="form-control form-control-sm" maxlength="120" name="txt_observaciones"
                            id="txt_observaciones" rows="2"><?php echo $observaciones; ?></textarea>
                    </div>

                    <?php if ( count($_SESSION['responsables']) !== 0 ) {?>
                    <div class="text-right">
                        <button type="submit" class="btn btn-light btn-sm" name="btn_agreagarTarea"
                            id="btn_agreagarTarea" onclick="noRequiredAgregarTarea()">Agregar Tarea</button>
                    </div>
                    <?php } ?>

                </div>


                <div class="col col-md-5">
                    <?php if ( count($_SESSION['responsables']) !== 0 ) {?>

                    <h5><span class="badge badge-success">Responsables</span></h5>
                    <hr>

                    <div class="form-group" style="height: 420px; overflow: auto;">

                        <?php
                        mostrarTablaResponsables( $_SESSION['responsables'] );
                        ?>

                    </div>

                    <?php } ?>
                </div>
            </div>
        </form>

    </div>
</body>

<?php
    }
?>

</html>

<script>
    function noRequiredAgregar() {
        document.getElementById('txt_actividad').required = false;
        document.getElementById('txt_fecha_ini').required = false;
        document.getElementById('cmb_area_resp').required = true;
        document.getElementById('txt_responsable').required = true;
    }

    function noRequiredAgregarTarea() {
        document.getElementById('txt_actividad').required = true;
        document.getElementById('txt_fecha_ini').required = true;
        document.getElementById('cmb_area_resp').required = false;
        document.getElementById('txt_responsable').required = false;
    }

    function validarArea() {
        var texto = $("#cmb_area_resp").val();
        var encontrado = $("#areaLista").find("option[value='" + texto + "']");

        if (encontrado != null && encontrado.length <= 0) {
            $("#cmb_area_resp").val("");
            mostrarAlerta("alertaArea", "Seleccionar un area de la lista mostrada");
        }
    }

    function mostrarAlerta(elemento, texto) {
        var divAlertaArea = document.getElementById(elemento);
        $("#alertaArea").text(texto);
        divAlertaArea.style = "position: fixed; visibility: visible";
        setTimeout(() => {
            divAlertaArea.style = "position: fixed; visibility: hidden";
        }, 2500);
    }

    function validarFechas() {
        if ($("#txt_fecha_fin").val() != "") {
            var f_ini = new Date($("#txt_fecha_ini").val());
            var f_fin = new Date($("#txt_fecha_fin").val());

            if (f_fin < f_ini) {
                $("#txt_fecha_fin").val("");
                mostrarAlerta("alertaArea", "La fecha final no puede ser menor a la fecha inicial");
            }
        }
    }
</script>