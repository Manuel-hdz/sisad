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
		//Este archivo contiene las operaciones para registar la salida de Material en la BD de Almacen
		include ("op_salidaMaterial.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	    <style type="text/css">
		<!--
		#equipo-seguridad {position:absolute; left:30px; top:146px; width:146px; height:23px; z-index:11;}
		#tabla {position:absolute; left:30px; top:190px; width:420px; height:200px; z-index:12;}
		#botones {position:absolute; width:924px; height:40px; z-index:13; left: 30px; top: 664px;}		
		#materiales{position:absolute; width:465px; height:444px; z-index:13; left: 494px; top: 190px; overflow:scroll;}		
		-->
        </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="equipo-seguridad">Equipo Seguridad</div>
	
	<form  name="frm_mostrarEquipoSeguridad" method="post" action="frm_consultarEquipoSeguridad.php">
	<fieldset class="borde_seccion" id="tabla" name="tabla">
	<legend class="titulo_etiqueta">Seleccionar los Datos del Trabajador</legend>
	<br>
	<table width="420" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="174" align="right">Nombre del Trabajador</td>
        	<td width="160">
				<?php 
				$nom_seleccionado='';
				if (isset($_POST["cmb_rfc"])){
					$nom_seleccionado=$cmb_rfc;
				}
				cargarComboNombres("cmb_rfc","nombre","ape_pat","ape_mat","empleados","bd_recursos","Trabajador",1,"frm_mostrarEquipoSeguridad",$nom_seleccionado);
				?>			
			</td>
		</tr>
		<tr>
			<td align="right">Categor&iacute;a</td>
			<td>
				<?php
				$categoria="";
				if (isset($_POST["cmb_rfc"])&&$_POST["cmb_rfc"]!=''){
					$categoria=obtenerDato("bd_recursos", "empleados", "area", "rfc_empleado", $cmb_rfc);
				}
				?>
				<input type="text" name="txt_categoria" id="txt_categoria" disabled="disabled" class="caja_de_texto" value="<?php echo $categoria?>"/>
			</td>
		</tr>
		<tr>
			<td align="right">Fecha de Ingreso</td>
		   	<td><?php 				
				$fecha="";
				if (isset($_POST["cmb_rfc"])&&$_POST["cmb_rfc"]!=''){
					$fecha=modFecha(obtenerDato("bd_recursos", "empleados", "fecha_ingreso", "rfc_empleado", $cmb_rfc),1);
				}?> 
				<input name="txt_fecha" type="text" disabled="disabled" class="caja_de_texto" value="<?php echo $fecha?>" size="10" maxlength="10"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" onclick="location.href='frm_salidaMaterial.php'" class="botones" value="Regresar" title="Regresar a la Página de Salida de Material"/>
			</td>
		</tr>
	</table>				
	</fieldset>
	</form>
    
	
	<?php if(isset($_POST["cmb_rfc"]) && $_POST["cmb_rfc"]!="") {?>
		<div id="materiales" align="center" class="borde_seccion2">
		<p class="titulo_etiqueta" >Registro del Material Entregado</p>
		<?php 
			//Realizar la conexion a la BD de Almacen
			$conn = conecta("bd_almacen");	
			
			//Crear la consulta para mostrar los materiales que han sido entregados al trabajador seleccionado
			//$stm_sql = "SELECT no_vale,fecha_entrega,detalle_es.nom_material,c_cambio,destino,turno FROM detalle_es JOIN materiales ON materiales_id_material=id_material WHERE empleados_rfc_empleado='$cmb_rfc' ORDER BY fecha_entrega DESC,detalle_es.nom_material";
			$stm_sql = "SELECT DISTINCT no_vale, fecha_entrega, detalle_es.nom_material, c_cambio, destino, turno
						FROM detalle_es
						JOIN materiales ON  `materiales`.`id_material` = id_material
						WHERE empleados_rfc_empleado =  '$cmb_rfc'
						ORDER BY fecha_entrega DESC , detalle_es.nom_material";
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
			
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
				//Realizar la conexion a la BD de Recursos Humanos para obtener el nombre del Trabajador
				$conn2 = conecta("bd_recursos");	
				//Obtener el Nombre completo del empleado seleccionado
				$nombre = mysql_fetch_array(mysql_query("SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE rfc_empleado='$cmb_rfc'"));
				//Cerramos la conexion a Recursos Humanos para evitar problemas con la Base de Datos
				mysql_close($conn2);
				//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5'>
				<tr>
				    <td colspan='6' align='center' class='titulo_etiqueta'>Material Entregado al Trabajador(a):<br><em><u>$nombre[nombre]</u></em></td>
  				</tr>
					<tr>
						<td class='nombres_columnas'>NO. VALE</td>
        				<td class='nombres_columnas'>FECHA ENTREGA</td>
						<td class='nombres_columnas'>MATERIAL</td>
				        <td class='nombres_columnas'>C/CAMBIO</td>
        				<td class='nombres_columnas'>DESTINO</td>
						<td class='nombres_columnas'>TURNO</td>					
      				</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					echo "	
					<tr>
						<td class='nombres_filas'>$datos[no_vale]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_entrega'],1)."</td>
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase'>$datos[c_cambio]</td>
						<td class='$nom_clase' align='left'>$datos[destino]</td>
						<td class='$nom_clase'>$datos[turno]</td>
					</tr>";
				
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs)); 
				echo"
				</table>	
				";
			}//Cierre if($datos=mysql_fetch_array($rs))
			else{
				//Realizar la conexion a la BD de Recursos Humanos para obtener el nombre del Trabajador
				$conn2 = conecta("bd_recursos");
				//Obtener el Nombre completo del empleado seleccionado
				$nombre = mysql_fetch_array(mysql_query("SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE rfc_empleado='$cmb_rfc'"));
				echo "<label class='msje_correcto'>El Trabajador(a): <em><u>$nombre[nombre]</u></em><br>No Tienen Ningun Equipo de Seguridad Registrado</label>";
				//Cerramos la conexion a Recursos Humanos para evitar problemas con la Base de Datos
				mysql_close($conn2);
			}
			
			//Cerrar la conexion con la BD		
			mysql_close($conn);?>
</div>
	<?php }?>
		
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>