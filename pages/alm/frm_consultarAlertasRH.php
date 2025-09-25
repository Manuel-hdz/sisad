<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
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
	<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>	
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:210px; z-index:12; }
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Empleado de Nuevo Ingreso</div>
	
	<div id="form-datos-alertas"><?php
	
		//Buscar en la Tabla de alerta para mostrar los empleados de nuevo ingreso que aun no han recibido su Equipo de Sguridad
		$conn = conecta("bd_almacen");
		
		//Crear la Sentencia SQL para obtener los RFC de los Empleados de Nuevo Ingreso
		$sql_stm = "SELECT rfc_empleado FROM alertas WHERE origen = 'RH' AND estado = 1";
		//EJecutar la Consulta
		$rs = mysql_query($sql_stm);
		//Si la Sentencia arroja resultados, desplegarlos al Usuario	
		if($datos=mysql_fetch_array($rs)){ ?>			
			<p align="center" class="titulo_etiqueta">Seleccionar un Empleado para Registrar Equipo de Seguridad</p>
			<form onSubmit="return valFormVerEmpleados(this);" name="frm_verEmpleados" action="frm_equipoSeguridad.php" method="post">
			<table border="0" align="center" cellpadding="5" class="tabla_frm" width="80%"> 
				<tr>
					<td align="center" class="nombres_columnas">Seleccionar</td>
					<td align="center" class="nombres_columnas">RFC</td>
					<td align="center" class="nombres_columnas">Nombre Empleado</td>
					<td align="center" class="nombres_columnas">&Aacute;rea</td>
					<td align="center" class="nombres_columnas">Puesto</td>
				</tr><?php
				//Manejar el color de los renglones de la tabla de resultados
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{
					//Obtener los Datos del Empleado
					$nombre = obtenerNombreEmpleado($datos['rfc_empleado']);
					$area = obtenerDato("bd_recursos", "empleados", "area", "rfc_empleado", $datos['rfc_empleado']);
					$puesto = obtenerDato("bd_recursos", "empleados", "puesto", "rfc_empleado", $datos['rfc_empleado']);
					$id_empl = obtenerDato("bd_recursos", "empleados", "id_empleados_empresa", "rfc_empleado", $datos['rfc_empleado']);
				
					echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='txt_codigo' id='txt_codigo' value='$id_empl' /></td>
						<td class='$nom_clase'>$datos[rfc_empleado]</td>
						<td class='$nom_clase'>$nombre</td>
						<td class='$nom_clase'>$area</td>
						<td class='$nom_clase'>$puesto</td>
					</tr>";
																				
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs));?>				
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td colspan="5" align="center">
						<input type="submit" name="sbt_registrar" value="Registrar" title="Registrar Equipo de Seguridad para el Empleado" class="botones" />
						&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_cancelar" value="Cancelar" title="Regresar al Inicio de Almac&eacute;n" class="botones" onclick="location.href='inicio_almacen.php'" />
					</td>
				</tr>
				
			</table>
			</form><?php
		}
		else {?>
			<p align="center" class="titulo_etiqueta">
				No Hay Empleado de Nuevo Ingreso Registrados
				<br /><br /><br />
				<input type="button" name="btn_regresar" value="Regresar" title="Regresar al Inicio de Almac&eacute;n" class="botones" onclick="location.href='inicio_almacen.php'" /></p><?php			
		}?>
			
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>