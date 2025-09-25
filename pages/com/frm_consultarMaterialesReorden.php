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
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11;}
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:440px; z-index:12; overflow:scroll;}
		#btn-cancelar { position:absolute; left:450px; top:680px; width:97px; height:37px; z-index:13;}
		-->
    </style>
</head>
<body>

	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Material con Existencia Menor o Igual al Punto de Reorden</div>

	<div id="form-datos-alertas" class="borde_seccion2">
		<p align="center" class="titulo_etiqueta">Lista de Materiales</p>
		<table border="0" align="center" cellpadding="5" class="tabla_frm" width="100%">
		<tr>
			<td align="center" class="nombres_columnas">CLAVE MATERIAL</td>
			<td align="center" class="nombres_columnas">NOMBRE MATERIAL</td>
			<td align="center" class="nombres_columnas">UNIDAD DE MEDIDA</td>
			<td align="center" class="nombres_columnas">CANTIDAD ACTUAL</td>
			<td align="center" class="nombres_columnas">CANTIDAD M&Iacute;NIMA</td>
			<td align="center" class="nombres_columnas">PUNTO REORDEN</td>
			<td align="center" class="nombres_columnas">CANTIDAD M&Aacute;XIMA</td>
			<td align="center" class="nombres_columnas">CATEGOR&Iacute;A</td>
			<td align="center" class="nombres_columnas">UBICACI&Oacute;N</td>
			<td align="center" class="nombres_columnas">PROVEEDOR</td>
		</tr>
			<?php 
				//Conectarse con la BD de Almacen y mantener la conexion para utilizar las funciones de monitorearMateriales() y mostrarAlertas($id_material)
				$conn = conecta("bd_almacen");
				//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Requisiciones
				$stm_sql = "SELECT id_material, nom_material, unidad_medida, existencia, nivel_minimo, nivel_maximo, re_orden, linea_articulo, ubicacion, proveedor FROM materiales 
							JOIN unidad_medida ON unidad_medida.materiales_id_material=id_material 
							JOIN alertas ON alertas.materiales_id_material=id_material 
							WHERE existencia<=re_orden AND relevancia='STOCK' AND grupo!='PLANTA' AND estado=1";
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta de datos fue realizada con exito.
				if($datos=mysql_fetch_array($rs)){
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						echo "<tr>
								<td class='$nom_clase' align='center'>$datos[id_material]</td>
								<td class='$nom_clase' align='center'>$datos[nom_material]</td>
								<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
								<td class='$nom_clase' align='center'><label class='msje_incorrecto'>$datos[existencia]</label></td>
								<td class='$nom_clase' align='center'>$datos[nivel_minimo]</td>
								<td class='$nom_clase' align='center'><label class='msje_correcto'>$datos[re_orden]</label></td>
								<td class='$nom_clase' align='center'>$datos[nivel_maximo]</td>
								<td class='$nom_clase' align='center'>$datos[linea_articulo]</td>
								<td class='$nom_clase' align='center'>$datos[ubicacion]</td>
								<td class='$nom_clase' align='center'>$datos[proveedor]</td>
								";
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";			
					}while($datos=mysql_fetch_array($rs));
				}
			mysql_close($conn);
			?>
		</table>
	</div>
	
	<div id="btn-cancelar" align="center">
		<input type="button"  onclick="location.href='inicio_compras.php'" class="botones" value="Regresar" title="Regresar al Inicio de Compras"/>
	</div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>