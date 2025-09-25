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

    <script src="../../includes/b4/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../includes/b4/popper.js/popper.min.js"></script>
    <script src="../../includes/b4/jquery/jquery.slim.min.js"></script>

    <style>
        #parrilla_menu {
            position: absolute;
            left: 100px;
            top: 160px;
            width: 540px;
            height: 270px;
            z-index: 1;
        }
    </style>
</head>

<body>
    <div id="parrilla_menu" class="container">
        <table class="table table-borderless">
            <tbody>
                <tr class="row">
                    <td class="col col-md-6 text-center">
                        <input type="image" onclick="location.href='frm_registrarJunta.php'" src="images/junta_admin.png" width="180px"
                            height="180px" title="Registrar Pendientes Junta Administrativa">
                        <br>
                        <a class="btn btn-success btn-sm" href="frm_registrarJunta.php" role="button">REGISTRAR</a>
                    </td>
                    <td class="col col-md-6 text-center">
                        <input type="image" onclick="location.href='frm_consultarJunta.php'" src="images/junta_pendientes.png" width="180px"
                            height="180px" title="Consultar Pendientes Junta Administrativa">
                        <br>
                        <a class="btn btn-success btn-sm" href="frm_consultarJunta.php" role="button">CONSULTAR PENDIENTES</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

<?php
    }
?>

</html>