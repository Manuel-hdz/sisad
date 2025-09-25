<!DOCTYPE html>
<html lang="es">
<?php
    include ("../seguridad.php"); 
    if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	} else {
        include("head_menu.php");
        actividadesAlerta();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Sistema de Gestión Empresarial, Producción y Operación</title>

    <link rel="stylesheet" href="../../includes/b4/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

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

        .tabla_actividades {
            height: 430px;
            overflow: auto;
        }
    </style>
</head>

<body>
    <div id="main_container" class="container m-md-3">
        <div class="row">
            <img src="../../images/title-bar-bg.gif" width="100%" height="30px" />
            <div class="titulo_barra" id="barra_titulo">Consultar Actividades</div>
        </div>
        <?php
        if ( !isset($_POST['btn_consultar']) ){
        ?>
        <form action="" method="post">
            <div class="row m-md-2">
                <div class="col col-md-6 border border-secondary">
                    <h6><span class="badge badge-dark">Seleccionar filtros:</span></h6>
                    <hr>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="txt_fecha">Fecha:</label>
                            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cmb_area">Area:</label>
                            <select name="cmb_area" id="cmb_area" class="form-control form-control-sm">
                                <option value="">Seleccionar un area</option>
                                <option value="Almacen">Almacen</option>
                                <option value="Auditoria">Auditoria</option>
                                <option value="Compras">Compras</option>
                                <option value="Clinica">Clinica</option>
                                <option value="Desarrollo">Desarrollo</option>
                                <option value="Gomar">Gomar</option>
                                <option value="Mainmi">Mainmi</option>
                                <option value="Saucito">Saucito</option>
                                <option value="Seguridad">Seguridad</option>
                                <option value="Sistemas">Sistemas</option>
                                <option value="Mtto. Desarrollo">Mtto. Desarrollo</option>
                                <option value="Mtto. Zarpeo">Mtto. Zarpeo</option>
                                <option value="Zarpeo">Zarpeo</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cmb_estado">Estado:</label>
                            <select name="cmb_estado" id="cmb_estado" class="form-control form-control-sm">
                                <option value="">Seleccionar un estado</option>
                                <option value="Terminada">Terminada</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Alerta">Alerta</option>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="txt_actividad">Actividad:</label>
                            <input type="text" name="txt_actividad" id="txt_actividad" maxlength="100"
                                class="form-control form-control-sm" autocomplete="off">
                        </div>
                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-light btn-sm" name="btn_consultar"
                                id="btn_consultar">CONSULTAR</button>
                            <button type="reset" class="btn btn-light btn-sm">LIMPIAR</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
        <?php
        } else {
        ?>
        <div class="row m-md-2">
            <h5><span class="badge badge-light"><?php echo obtenerTituloTabla(); ?></span></h5>
        </div>

        <div class="row tabla_actividades">
            <?php
            $error = mostrarTablaActividades();
            if($error[0]){
            ?>
            <div class="alert alert-danger" role="alert">
                <h6 class="alert-heading">Error</h6>
                <p><?php echo $error[1]; ?></p>
            </div>
            <?php
            }
            ?>
        </div>

        <form method="POST" action="guardar_reporte.php">
            <div class="text-center m-md-2">
                <input type="hidden" name="hdn_consulta" value="<?php echo crearConsultaJunta(); ?>">
                <input type="hidden" name="hdn_nomReporte" value="Actividades_Junta">
                <input type="hidden" name="hdn_origen" value="juntaActividades">
                <input type="hidden" name="hdn_msg" value="<?php echo obtenerTituloTabla(); ?>">

                <button class="btn btn-light btn-sm">EXPORTAR A EXCEL</button>
                <button class="btn btn-light btn-sm" type="button"
                    onclick="location.href='frm_consultarJunta.php'">REGRESAR</button>
            </div>
        </form>

        <?php
        }
        ?>
    </div>
</body>

<?php
    }
?>