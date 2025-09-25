<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 13/Agosto/2012
	  * Descripción: Este archivo contiene funciones para consultar la información de las obras registradas en periodos seleccionados
	**/
	
	//Funcion que se encarga de desplegar las obras en el rango de fechas
	function mostrarObrasEq(){
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
			$sql_stm ="SELECT id_registro, fam_equipo, concepto, unidad, pumn_estimacion, puusd_estimacion, fecha_registro
					   FROM equipo_pesado WHERE fecha_registro>='$f1' AND fecha_registro<='$f2' ORDER BY id_registro";
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Obras de Equipo Pesado en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			//Variable para almacenar el nombre del reporte
			$msg_reporte = "ReporteObras_de_$_POST[txt_fechaIni]_a_$_POST[txt_fechaFin]";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Registro de Obra de Equipo Pesado en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		else if(isset($_POST["sbt_seleccionarObra"])){//Segunda Consulta para mostrar ls Obras de acuerdo al tipo seleccionado
		//Crear sentencia SQL
			$sql_stm = "SELECT id_registro, fam_equipo, concepto, unidad, pumn_estimacion, puusd_estimacion, fecha_registro
						FROM equipo_pesado WHERE fam_equipo='$_POST[cmb_tipoObraEqP]' AND concepto='$_POST[cmb_nomObraEq]' ORDER BY id_registro";	
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Obras con <em><u>$_POST[cmb_tipoObraEqP]</u></em> con el  Nombre de <em><u>$_POST[cmb_nomObraEq]</u></em>";
			//Variable para almacenar el nombre del reporte
			$msg_reporte = "Reporte_$_POST[cmb_tipoObraEqP]";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Obras Registrados </label>";										
		}
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
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
					<td class='nombres_columnas' align='center'>CLAVE OBRA EQUIPO</td>
					<td class='nombres_columnas' align='center'>TIPO FAMILIA</td>					
					<td class='nombres_columnas' align='center'>NOMBRE (CONCEPTO)</td>
					<td class='nombres_columnas' align='center'>UNIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO/U M.N. ESTIMACI&Oacute;N </td>
					<td class='nombres_columnas' align='center'>PRECIO/U USD ESTIMACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$datos[id_registro]</td>
						<td class='$nom_clase'>$datos[fam_equipo]</td>																	
						<td class='$nom_clase'>$datos[concepto]</td>
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
				  	onclick="location.href='frm_consultarEqPesado.php'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
					<?php
					/*
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
				<?php }*/?>
			</tr>
		</table>			
		</div><?php 
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>