<?php //Archivos que permtien desabilitar teclas especificas, así como desabilitar el clic derecho?>
<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
<script language="javascript" type="text/javascript">
	<!--
	function click() {
		if (event.button==2) {
			alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
		}
	}
	document.onmousedown=click;
	//-->
</script>
<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 07/Mayo/2012
	  * Descripción: Este archivo contiene funciones para Ver los Detalles de la Bitacora por Equipo
	  **/ 

if(isset($_GET['id_bitacora']))
	verDetalle();

function verDetalle(){
	//Extraer el ID de la Bitacora
	$id_bitacora=$_GET["id_bitacora"];
	//Arcivos que se incluyen para obtener informacion del equipo
	include_once ("../../includes/conexion.inc");
	include_once ("../../includes/op_operacionesBD.php");
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
	echo "<p align='center' class='titulo_etiqueta'><b>BIT&Aacute;CORA $id_bitacora</b></p>";
	echo "<body onunload='cerrarVentana()'>";
	//Obtener el Vale de Materiales Asociado al Servicio
	$vale=obtenerDato("bd_mantenimiento","materiales_mtto","id_vale","bitacora_mtto_id_bitacora",$id_bitacora);
	//Realizar la conexion con la BD
	$conn = conecta("bd_mantenimiento");
	//Sentencia SQL para Extraer las actividades Correctivas
	$stm_sql_Act = "SELECT sistema, aplicacion, descripcion FROM actividades_correctivas WHERE bitacora_mtto_id_bitacora = '$id_bitacora'";
	$stm_sql_Mec = "SELECT nom_mecanico FROM mecanicos WHERE bitacora_mtto_id_bitacora = '$id_bitacora'";
	//Ejecutar sentencia SQL
	$rs_Act=mysql_query($stm_sql_Act);
	//Verificar que se hayan encontrado resultados para ACTIVIDADES
	if($datos_Act=mysql_fetch_array($rs_Act)){						
		echo "								
		<table cellpadding='5' width='100%'>
			<caption class='titulo_etiqueta'>ACTIVIDADES</caption>
			<tr>
				<td class='nombres_columnas' width='1%'>NO.</td>
				<td class='nombres_columnas'>SISTEMA</td>
				<td class='nombres_columnas'>APLICACI&Oacute;N</td>
				<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
			</tr>";
			
		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{
			echo "<tr>		
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase'>$datos_Act[sistema]</td>
					<td class='$nom_clase'>$datos_Act[aplicacion]</td>
					<td class='$nom_clase' align='left'>$datos_Act[descripcion]</td>
				</tr>";									
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}while($datos_Act=mysql_fetch_array($rs_Act));

		echo "</table>";
	}
	else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<p class='msje_correcto' align='center'>No Existen Actividades</p>";
	
	//Ejecutar sentencia SQL
	$rs_Mec=mysql_query($stm_sql_Mec);
	//Verificar que se hayan encontrado resultados para MECANICOS
	if($datos_Mec=mysql_fetch_array($rs_Mec)){						
		echo "								
		<table cellpadding='5' width='100%'>
			<caption class='titulo_etiqueta'>MEC&Aacute;NICOS</caption>
			<tr>
				<td class='nombres_columnas' width='1%'>NO.</td>
				<td class='nombres_columnas'>MEC&Aacute;NICO</td>
			</tr>";
			
		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{
			echo "<tr>		
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase' align='left'>$datos_Mec[nom_mecanico]</td>
				</tr>";									
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}while($datos_Mec=mysql_fetch_array($rs_Mec));

		echo "</table>";
	}
	else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<p class='msje_correcto' align='center'>No Hay Registro de Mec&aacute;nicos</p>";
	//Cerrar la conexion
	mysql_close($conn);
	
	if($vale!=""){
		//Conectar a la BD de Almacén para extraer los materiales asociados al Vale de Salida
		$conn=conecta("bd_almacen");
		//Sentencia SQL para extraer los materiales asociados al Vale listado en Almacen
		$stm_sql_Mat = "SELECT nom_material,unidad_material,cant_salida,id_equipo_destino FROM detalle_salidas JOIN salidas ON salidas_id_salida=id_salida WHERE no_vale = '$vale'";
		//Ejecutar sentencia SQL
		$rs_Mat=mysql_query($stm_sql_Mat);
		//Verificar que se hayan encontrado resultados para MECANICOS
		if($datos_Mat=mysql_fetch_array($rs_Mat)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>MATERIALES</caption>
				<tr>
					<td class='nombres_columnas' width='1%'>NO.</td>
					<td class='nombres_columnas'>MATERIAL</td>
					<td class='nombres_columnas'>UNIDAD MEDIDA</td>
					<td class='nombres_columnas'>CANTIDAD</td>
					<td class='nombres_columnas'>EQUIPO DESTINO</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>$datos_Mat[nom_material]</td>
						<td class='$nom_clase' align='left'>$datos_Mat[unidad_material]</td>
						<td class='$nom_clase' align='left'>$datos_Mat[cant_salida]</td>
						<td class='$nom_clase' align='left'>$datos_Mat[id_equipo_destino]</td>
					</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos_Mat=mysql_fetch_array($rs_Mat));
	
			echo "</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<p class='msje_correcto' align='center'>No Hay Registro de Materiales</p>";
		//Cerrar la conexion
		mysql_close($conn);
	}
	else
		//Si no hay Vale asociado
		echo "<p class='msje_correcto' align='center'>No Hay Registro de Materiales</p>";
	?>	
	<script type="text/javascript" language="javascript">
		function cerrarVentana(){
		window.opener.document.getElementById("<?php echo $_GET["btn"]?>").disabled=false;
		window.opener.focus();
		}
	</script>
<br /><br /><br />
<p align="center">
<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();" />
</p>
</body>
<?php
}
?>