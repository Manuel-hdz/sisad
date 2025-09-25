<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 02/Junio/2011
	  * Descripción: Este archivo contiene funciones para consultar la información de las obras registradas en periodos seleccionados
	**/
	
	//Funcion que se encarga de desplegar las obras en el rango de fechas
	function mostrarObras(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Variable Bandera para conocer si la consulta arrojo algun resultado
		$flag=0;
		
		//Si viene sbt_buscarObraFecha la buqueda de las Obras proviene de un rango de fechas
		if(isset($_POST["sbt_buscarObraFecha"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT id_obra, categoria, tipo_obra, nombre_obra, seccion ,area, unidad, pumn_estimacion, puusd_estimacion, fecha_registro, subcategorias_id, precios_traspaleo_id_precios
					   FROM obras WHERE  fecha_registro>='$f1' AND fecha_registro<='$f2' ORDER BY id_obra";
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Obras en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Variable para almacenar el nombre del reporte
			$msg_reporte = "ReporteObras_de_$_POST[txt_fechaIni]_a_$_POST[txt_fechaFin]";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Registro de Obra en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		else if(isset($_POST["sbt_buscarObraTipo"])){//Segunda Consulta para mostrar ls Obras de acuerdo al tipo seleccionado
		//Crear sentencia SQL
			$sql_stm = "SELECT id_obra, categoria, tipo_obra, nombre_obra, seccion ,area, unidad, pumn_estimacion, puusd_estimacion, fecha_registro, subcategorias_id, precios_traspaleo_id_precios
					   FROM obras WHERE tipo_obra='$_POST[cmb_obra]' AND nombre_obra='$_POST[cmb_nomObra]' ORDER BY id_obra";	
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Tipos de Obra  <em><u>$_POST[cmb_obra]</u></em>  con  el  Nombre  de  <em><u>$_POST[cmb_nomObra]</u></em>";
			
			//Variable para almacenar el nombre del reporte
			$msg_reporte = "Reporte_$_POST[cmb_obra]_$_POST[cmb_nomObra]";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Obras Registrados </label>";										
		}
		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		echo mysql_error();
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='1500'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>CLAVE OBRA</td>
					<td class='nombres_columnas' align='center'>TIPO OBRA</td>					
					<td class='nombres_columnas' align='center'>NOMBRE OBRA</td>
					<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A DE PRECIOS</td>
					<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
					<td class='nombres_columnas' align='center'>SUBCATEGOR&Iacute;A</td>
					<td class='nombres_columnas' align='center'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>UNIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO/U M.N. ESTIMACI&Oacute;N </td>
					<td class='nombres_columnas' align='center'>PRECIO/U USD ESTIMACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				if($datos["subcategorias_id"]==0)
					$idSubcategoria="N/R";
				else
					$idSubcategoria=obtenerDato("bd_topografia","subcategorias","subcategoria","id",$datos["subcategorias_id"]);
					
				if($datos["precios_traspaleo_id_precios"]!="N/A")
					$listaPrecios=obtenerDato("bd_topografia","precios_traspaleo","tipo","id_precios",$datos["precios_traspaleo_id_precios"]);
				else
					$listaPrecios="N/A";
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$datos[id_obra]</td>
						<td class='$nom_clase'>$datos[tipo_obra]</td>																	
						<td class='$nom_clase'>$datos[nombre_obra]</td>
						<td class='$nom_clase'>$listaPrecios</td>
						<td class='$nom_clase'>$datos[categoria]</td>
						<td class='$nom_clase'>$idSubcategoria</td>
						<td class='$nom_clase'>$datos[seccion]</td>
						<td class='$nom_clase'>$datos[area]</td>						
						<td class='$nom_clase'>$datos[unidad]</td>						
						<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;			
		}?>
		<?php //Este div se cierra aqui con el objetivo de sacar los botones del div que contiene la consulta y que no aparezcan dentro del mismo?>
		</div>
		<div id="btns-regpdf" align="center">
		<table align="center" >
			<tr>			
				<td align="center">
				  	<input type="button" name="btn_regresar"  value="Regresar" class="botones" title="Regresar a la P&aacute;gina Consulta de Obra" 
					onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_consultarObra.php'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
					<?php 
					if($flag==1){ //Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
						<td align="center">
							<form action="guardar_reporte.php" method="post">
								<input name="hdn_consulta" type="hidden" value="<?php echo $sql_stm; ?>"/>
								<input name="hdn_nomReporte" type="hidden" 
								value="<?php echo $msg_reporte;?>" />
								<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"/>
								<input name="hdn_origen" type="hidden" value="obras"/>	
								<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
								title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"/>
							</form>
						</td>
				<?php }?>
			</tr>
		</table>			
		</div><?php 
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
?>