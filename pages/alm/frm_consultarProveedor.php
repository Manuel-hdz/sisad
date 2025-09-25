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
		include ("head_menu.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#tabla-proveedores {position:absolute; left:30px; top:190px; width:940px; height:450px; z-index:12; overflow:scroll; }
		#titulo-consultar {position:absolute; left:30px; top:146px; width:191px; height:19px; z-index:11; }
		#btns-regpdf { position: absolute; left:30px; top:680px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Proveedores</div>
	
	<div id="tabla-proveedores" class="borde_seccion2" align="center">
	<?php
	//Realizar la conexion a la BD de Compras
	$conn = conecta("bd_compras");
	
	//Crear la Sentencia para mostar la Información de los Proveedores registrados
	$stm_sql = "SELECT * FROM proveedores ORDER BY razon_social";
	
	//Ejecutar la sentencia previamente creada
	$rs = mysql_query($stm_sql);
										
	//Confirmar que la consulta de datos fue realizada con exito.
	if($datos=mysql_fetch_array($rs)){
		//Desplegar los resultados de la consulta en una tabla
		echo "				
			<table cellpadding='5' width='1650'>      			
			<tr>
			    <td colspan='16' align='center' class='titulo_etiqueta'>Proveedores Registrados a la fecha <em><u>".verFecha(4)."</u></em></td>
  			</tr>
				<tr>
					<td class='nombres_columnas'>RFC</td>
        			<td class='nombres_columnas'>RAZON SOCIAL</td>
			        <td class='nombres_columnas'>CALLE</td>
        			<td class='nombres_columnas'>NUM. EXT.</td>
					<td class='nombres_columnas'>NUM. INT.</td>
        			<td class='nombres_columnas'>COLONIA</td>
					<td class='nombres_columnas'>CP</td>
        			<td class='nombres_columnas'>CIUDAD</td>
					<td class='nombres_columnas'>ESTADO</td>
					<td class='nombres_columnas'>TELEFONO</td>
					<td class='nombres_columnas'>TELEFONO 2</td>
					<td class='nombres_columnas'>FAX</td>
        			<td class='nombres_columnas'>CORREO</td>
					<td class='nombres_columnas'>CORREO 2</td>
        			<td class='nombres_columnas'>CONTACTO</td>
					<td class='nombres_columnas'>MATERIAL Y/O SERVICIO</td>				
      			</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		do{	
			echo "	
				<tr>
					<td class='nombres_filas'>$datos[rfc]</td>
					<td class='$nom_clase' align='left'>$datos[razon_social]</td>
					<td class='$nom_clase' align='left'>$datos[calle]</td>
					<td class='$nom_clase'>$datos[numero_ext]</td>
					<td class='$nom_clase'>$datos[numero_int]</td>
					<td class='$nom_clase' align='left'>$datos[colonia]</td>
					<td class='$nom_clase'>$datos[cp]</td>
					<td class='$nom_clase' align='left'>$datos[ciudad]</td>
					<td class='$nom_clase' align='left'>$datos[estado]</td>
					<td class='$nom_clase'>$datos[telefono]</td>
					<td class='$nom_clase'>$datos[telefono2]</td>
					<td class='$nom_clase'>$datos[fax]</td>
					<td class='$nom_clase' align='left'>$datos[correo]</td>
					<td class='$nom_clase' align='left'>$datos[correo2]</td>
					<td class='$nom_clase' align='left'>$datos[contacto]</td>
					<td class='$nom_clase' align='left'>$datos[mat_servicio]</td>
				</tr>";	
										
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
				
		}while($datos=mysql_fetch_array($rs));
		echo "	
			</table>"; ?>
		</div>
		<div id="btns-regpdf" align="center">
			<table width="30%" cellpadding="12">		
				<tr>
					<td colspan="16" align="center">
						<form action="inicio_almacen.php" method="post">
							<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Regresar al Inicio de Almac&eacute;n" onMouseOver="window.estatus='';return true"  />
						</form>	
					</td>
				</tr>
			</table>
			</div>
		<?php
	}
	
	//Cerrar la conexion con la BD		
	mysql_close($conn);?>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>