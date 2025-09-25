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
	<?php
	$conn = conecta("bd_gerencia");

	$sql = $_POST["txt_consulta"]; 

	$rs = mysql_query($sql);
	?>
	<div id="main_container" class="container m-md-3">
		<div class="row">
			<img src="../../images/title-bar-bg.gif" width="100%" height="30px" />
			<div class="titulo_barra" id="barra_titulo">Actividades en Alerta</div>
		</div>

		<div class="row m-md-2 tabla_actividades">
			<table class="tabla_frm encabezado-fijo" cellpadding="5" width=100%>
				<thead class="text-center">
					<th class="columna-fija nombres_columnas">ACTIVIDAD</th>
					<th class="nombres_columnas">FECHA INICIAL</th>
					<th class="nombres_columnas">FECHA FIN</th>
					<th class="nombres_columnas">OBSERVACIONES</th>
					<th class="nombres_columnas">PROGRESO</th>
				</thead>
				<tbody>
					<?php
					if($rs) {
						$cont = 0;
						$clase_renglon = "";
						while($datos = mysql_fetch_array($rs)){
							if( $cont%2 == 0) $clase_renglon = "renglon_blanco";
							else $clase_renglon = "renglon_gris";
						?>
					<tr>
						<td class="vertical-center fila-fija <?php echo $clase_renglon; ?>">
							<?php echo $datos['actividad']; ?>
						</td>
						<td class="vertical-center <?php echo $clase_renglon; ?>">
							<?php echo modFecha($datos['fecha_ini'],7); ?>
						</td>
						<td class="vertical-center <?php echo $clase_renglon; ?>">
							<?php echo modFecha($datos['fecha_fin'],7); ?>
						</td>
						<td class="vertical-center <?php echo $clase_renglon; ?>">
							<?php echo $datos['observaciones']; ?>
						</td>
						<td class="text-center vertical-center <?php echo $clase_renglon; ?>">
							<div class="progress bg-secondary">
								<div class="progress-bar bg-success" role="progressbar"
									style="width: <?php echo $datos['porcentaje']; ?>%" aria-valuenow="10"
									aria-valuemin="0" aria-valuemax="100">
									<?php echo $datos['porcentaje']; ?>%
								</div>
							</div>
						</td>
					</tr>
					<?php
						}
					} else {
						?>
					<tr>
						<td class="vertical-center" colspan="5">
							<div class="alert alert-danger" role="alert">
								<h6 class="alert-heading"><strong>ERROR</strong></h6>
								<hr>
								<p>
									<?php echo mysql_error(); ?>
								</p>
								<!-- <p>
										<?php echo $sql; ?>
								</p> -->
							</div>
						</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</body>
<?php
    }
?>

</html>