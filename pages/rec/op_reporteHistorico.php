<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                              
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 08/Junio/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte del Registro Historico de los Empleados
	  **/

	//Funcion que se encarga de desplegar los bonos en el rango de fechas
	function reporteHistorico(){
		?><div id="reporte" align="center" class="borde_seccion2" width="100%"><?php		

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 0;
				
		//Si viene definido el boton de sbt_consultarEmpleado entonces podremos obtener e RFC del empleado seleccionado
		if(isset($_POST["sbt_consultarEmpleado"])){
			//Obtener nombre Recursos Humanos
			$nombre=obtenerNombreEmpleado($_POST['txt_RFCEmpleado']);
			
			//Crear sentencia SQL
			$sql_stm = " SELECT  empleados_rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, fecha_ingreso, fecha_baja, area, puesto, observaciones, fecha_mod_puesto 
						FROM bajas_modificaciones WHERE CONCAT (nombre,' ',ape_pat,' ',ape_mat) = '$_POST[txt_nombre]' ORDER BY nombre";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg_titulo = "Reporte Histórico del Empleado  <em><u>$_POST[txt_nombre]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro del Empleado <em><u>$_POST[txt_nombre]</u></em></label>";										
		}
		
			
		else if(isset($_POST["sbt_consultarTodos"])){//Segunda Consulta para mostrar todos los empleados que hayan estado dados de baja o modificados
			//Crear sentencia SQL
			$sql_stm = "SELECT  empleados_rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, fecha_ingreso, fecha_baja, area, puesto, observaciones, fecha_mod_puesto FROM bajas_modificaciones ORDER BY nombre;";	
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg_titulo= "Historial de los Empleados Registrados";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Registros Históricos </label>";										
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$flag = 1;
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5'  width='100%'>				
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg_titulo</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>RFC EMPLEADO</td>
					<td class='nombres_columnas'>NOMBRE</td>
					<td class='nombres_columnas'>FECHA INGRESO</td>
					<td class='nombres_columnas'>FECHA MODIFICACI&Oacute;N</td>
					<td class='nombres_columnas'>FECHA BAJA</td>
					<td class='nombres_columnas'>&Aacute;REA</td>
					<td class='nombres_columnas'>PUESTO</td>
					<td class='nombres_columnas'>OBSERVACIONES</td>				
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
			
				//Revisar que tanto la fecha de baja como la fecha de modificacion no se encuentren vacias
				$fechaMod = "N/D";
				if($datos['fecha_mod_puesto']!='0000-00-00'){
					$fechaMod = modFecha($datos['fecha_mod_puesto'],1); 
				}
				
				$fechaBaja = "N/D";
				if($datos['fecha_baja']!='0000-00-00'){
					$fechaBaja = modFecha($datos['fecha_baja'],1);
				}
				echo "
					<tr>
						<td class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_ingreso'],1)."</td>
						<td class='$nom_clase'>".$fechaMod."</td>						
						<td class='$nom_clase'>".$fechaBaja."</td>						
						<td class='$nom_clase'>$datos[area]</td>
						<td class='$nom_clase'>$datos[puesto]</td>
						<td class='$nom_clase'>$datos[observaciones]</td>	
					</tr>";				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "					
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>";
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;
		 }?></div>	
		
		<div id="btns-rpt" align="center" >
		<table cellpadding="5" cellspacing="5" align="center" class="tabla_frm">
			<tr>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reportes Historicos del Personal" onclick="location.href='frm_reporteHistorico.php'" />&nbsp;&nbsp;
				</td>
				<?php if ($flag==1) { ?>              
					<td align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $sql_stm; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Consulta Historico" />                  		
							<input name="hdn_origen" type="hidden" value="reporteHistorico" />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" onmouseover="window.estatus='';return true"  />&nbsp;&nbsp;
						</form>
					</td>              
				<?php  }?>
			</tr>
		</table>
		
		</div>
		<div align="center" id="btns-rpt"></div>
		<?php 
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	?>

		