<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Gerencia T�ncica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

		<style type="text/css">
			#parrilla-menu1 {
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
		<div id="parrilla-menu1">
			<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
				<tr>
					<!-- <td align="center">
						<input type="image" onclick="location.href='frm_reporteRezagado.php'"
							src="images/add-reporte-rezagado.png" width="115" height="160" border="0"
							title="Reporte de Rezagado con Scoop Tram" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn1" id="btn1" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn1','',1); location.href='frm_reporteRezagado.php'"
							onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de Rezagado con Scoop Tram" />
					</td> -->
					<td align="center">
						<input type="image" onclick="location.href='frm_reporteUtilitario.php'"
							src="images/add-reporte-utilitario.png" width="115" height="160" border="0"
							title="Reporte de Equipo Utilitario" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn2" id="btn2" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn2','',1); location.href='frm_reporteUtilitario.php'"
							onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de Equipo Utilitario" />
					</td>
					<!-- <td align="center">
						<input type="image" onclick="location.href='frm_reporteBarrenacion.php'"
							src="images/add-reporte-barrenacion.png" width="115" height="160" border="0"
							title="Reporte de Barrenaci&oacute;n con Jumbo o M&aacute;quina de Pierna" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn3" id="btn3" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn3','',1); location.href='frm_reporteBarrenacion.php'"
							onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')"
							title="Reporte de Barrenaci&oacute;n con Jumbo o M&aacute;quina de Pierna" />
					</td> -->
					<td align="center">
						<form action="frm_consultarKardexChecador.php">
							<input type="image" src="images/add-rep-kardex.png" width="115" height="160" border="0"
								title="Generar Reporte Kardex" onmouseover="window.status='';return true" />
							<input type="image" src="../../images/btn-gen.png" name="btn10" id="btn10" width="118"
								height="46" border="0" title="Generar Reporte Kardex"
								onclick="MM_nbGroup('down','group1','btn10','',1)"
								onmouseover="MM_nbGroup('over','btn10','../../images/btn-gen-over.png','',1);window.status='';return true"
								onmouseout="MM_nbGroup('out')" />
						</form>
					</td>
					<td align="center">
						<form action="frm_reporteRequisiciones.php">
							<input type="image" src="images/add-requisicion.png" width="115" height="160" border="0"
								title="Generar Reporte Requisiciones" onmouseover="window.status='';return true" />
							<input type="image" src="../../images/btn-gen.png" name="btn12" id="btn12" width="118"
								height="46" border="0" title="Generar Reporte Requisiciones"
								onclick="MM_nbGroup('down','group1','btn12','',1)"
								onmouseover="MM_nbGroup('over','btn12','../../images/btn-gen-over.png','',1);window.status='';return true"
								onmouseout="MM_nbGroup('out')" />
						</form>
					</td>
				</tr>
			</table>
			<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
				<tr>
					<!-- <td align="center">
						<input type="image" onclick="location.href='frm_reporteVoladuras.php'"
							src="images/add-reporte-voladuras.png" width="115" height="160" border="0"
							title="Reporte de Voladuras" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn4" id="btn4" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn4','',1); location.href='frm_reporteVoladuras.php'"
							onmouseover="MM_nbGroup('over','btn4','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de Voladuras" />
					</td> -->
					<!-- <td align="center">
						<input type="image" onclick="location.href='frm_reporteAvance.php'"
							src="images/add-reporte-avance.png" width="115" height="160" border="0"
							title="Reporte de Avance" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn5" id="btn5" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn5','',1); location.href='frm_reporteAvance.php'"
							onmouseover="MM_nbGroup('over','btn5','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de Avance" />
					</td> -->
					<td align="center">
						<input type="image" onclick="location.href='frm_reporteServicios.php'"
							src="images/add-reporte-servicios.png" width="115" height="160" border="0"
							title="Reporte de Servicios con Minera Fresnillo" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn6" id="btn6" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn6','',1); location.href='frm_reporteServicios.php'"
							onmouseover="MM_nbGroup('over','btn6','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de Servicios con Minera Fresnillo" />
					</td>
					<!-- <td align="center">
						<input type="image" onclick="location.href='frm_reporteNomina.php'"
							src="images/add-reporte-nomina.png" width="115" height="160" border="0"
							title="Reporte de N&oacute;mina de Desarrollo" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn7" id="btn7" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn7','',1); location.href='frm_reporteNomina.php'"
							onmouseover="MM_nbGroup('over','btn7','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de N&oacute;mina de Desarrollo" />
					</td> -->
					<td align="center">
						<input type="image" onclick="location.href='frm_reporteSalidas.php'"
							src="images/add-reportesalidas.png" width="115" height="160" border="0"
							title="Reporte de Salidas Almacen" /><br />
						<input type="image" src="../../images/btn-gen.png" name="btn11" id="btn11" width="118"
							height="46" border="0"
							onclick="MM_nbGroup('down','group1','btn11','',1); location.href='frm_reporteSalidas.php'"
							onmouseover="MM_nbGroup('over','btn11','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Reporte de Salidas Almacen" />
					</td>
				</tr>
			</table>
		</div>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>