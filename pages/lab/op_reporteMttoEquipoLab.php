<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Nadia Madahí López Hernández                        
	  * Fecha: 30/Junio/2011                                      			
	  * Descripción: Este archivo permite generar las funciones para generar el Reporte de Mtto a los Equipos de Laboratorio
	  **/
	
	  			
	//Funcion que se encarga de consultar los equipos de laboratorio al que se le ha brindado un servicio de mantenimiento
	function consultarServicioMttoLab(){

		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");

		//Recuperamos las fechas del POST y las convertimos al formato de la Base de datos
		$fechaIni = modFecha($_POST["txt_fechaIni"],3);
		$fechaFin = modFecha($_POST["txt_fechaFin"],3);
	
		//Si viene sbt_generarRepFecha se mostraran los equipos de acuerdo al nombre seleccionado
		$stm_sql = "";
		if(isset($_POST['cmb_nombreEquipoLab'])){
			//Creamos la sentencia SQL que mostrara los servicios que se han realizado en un rango de fechas determinado
			$stm_sql ="SELECT DISTINCT id_servicio, tipo_servicio, no_interno, nombre, marca, no_serie, encargado, fecha_mtto, fecha_registro
						FROM (equipo_lab JOIN cronograma_servicios ON no_interno=equipo_lab_no_interno) JOIN bitacora_mtto ON id_servicio=cronograma_servicios_id_servicio
						WHERE nombre='$_POST[cmb_nombreEquipoLab]' AND cronograma_servicios.estado = 1 AND fecha_registro>='$fechaIni' AND fecha_registro<='$fechaFin'";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Servicios de Mantenimiento al Equipo <u><em>".$_POST['cmb_nombreEquipoLab']."</u></em> del ";
			$titulo.="<u><em>".$_POST['txt_fechaIni']."</u></em> al <u><em>".$_POST['txt_fechaFin']."</u></em>";

			//Variable que almacena el msj de error
			$error="No existen Mantenimientos Registrados <u><em>".$_POST['cmb_nombreEquipoLab']."</u></em> del <u><em>".$_POST['txt_fechaIni']."</u></em> al <u><em>".$_POST['txt_fechaFin']."</u></em>";
						
		}
		//Si viene sbt_generarRepFecha se mostraran los equipos de acuerdo al rango de fechas seleccionado por el usuario
		else{ 

			//Creamos la sentencia SQL
			$stm_sql ="SELECT DISTINCT id_servicio, tipo_servicio, no_interno, nombre, marca, no_serie, encargado, fecha_mtto, fecha_registro
						FROM (equipo_lab JOIN cronograma_servicios ON no_interno=equipo_lab_no_interno) JOIN bitacora_mtto ON id_servicio=cronograma_servicios_id_servicio
						WHERE cronograma_servicios.estado = 1 AND fecha_registro>='$fechaIni' AND fecha_registro<='$fechaFin' ORDER BY no_interno";
	
			//Variable que guarda el titulo de la tabla
			$titulo="Servicios de Mantenimiento a los Equipos de Laboratorio Registrados del <u><em>".$_POST['txt_fechaIni']."</u></em> al"; 
			$titulo.= "<u><em>".$_POST['txt_fechaFin']."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Servicios de Mantenimientos Registrados en las Fechas del"; 
			$error.="<u><em>".$_POST['txt_fechaIni']."</u></em> al <u><em>".$_POST['txt_fechaFin']."</u></em>";
	
		}
		
		
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption>					
				<tr>
					<td class='nombres_columnas'>SELECCIONAR</td>
					<td class='nombres_columnas'>TIPO SERVICIO</td>
					<td class='nombres_columnas'>N° INTERNO</td>
					<td class='nombres_columnas'>NOMBRE</td>
					<td class='nombres_columnas'>MARCA</td>
					<td class='nombres_columnas'>N° SERIE</td>
					<td class='nombres_columnas'>ENCARGADO</td>															
					<td class='nombres_columnas'>FECHA MTTO</td>
					<td class='nombres_columnas'>FECHA REGISTRO</td>																				
			</tr>";
			echo "<form name='frm_detalleNombreEquipo' method='post' action='frm_reporteMttoEquipoLab.php'>
					<input type='hidden' name='verDetalle' value='si' />					
					<input type='hidden' name='txt_fechaIni' value='$_POST[txt_fechaIni]' />
					<input type='hidden' name='txt_fechaFin' value='$_POST[txt_fechaFin]' />
					";
					
			//Enviar el Combo en el caso que se consulte el Detalle del Registro de la Bitacora de Mtto
			if(isset($_POST['cmb_nombreEquipoLab']))							
				echo "<input type='hidden' name='cmb_nombreEquipoLab' value='$_POST[cmb_nombreEquipoLab]' />";
					
					
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_noServicio' value='$datos[id_servicio]'
							onClick='javascript:document.frm_detalleNombreEquipo.submit();'/>							
						</td>		
						<td align='center' class='$nom_clase'>$datos[tipo_servicio]</td>
						<td align='center' class='$nom_clase'>$datos[no_interno]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[marca]</td>
						<td align='center' class='$nom_clase'>$datos[no_serie]</td>
						<td align='center' class='$nom_clase'>$datos[encargado]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_mtto'],1)."</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>										
				</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>
				<input type='hidden' name='hdn_titulo' value='$titulo' />
			</form>";
			return 1;
		}//Cierre if($datos=mysql_fetch_array($rs)
		else{//Si no se encuentra ningun resultado desplegar un mensaje								
			echo "<label class='msje_correcto'>$error</label>";
			return 0;
		}
		
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	
	//Funcion que permite mostrar el detalle de los prestanmos  de acuerdo al Empleado
	function detalleServicioMttoNomEquipo(){	
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Recuperar los datos del POST
		$idServicio = $_POST['ckb_noServicio'];		
		$fechaIni = $_POST['txt_fechaIni'];
		$fechaFin = $_POST['txt_fechaFin'];
		
		
		//Obtener el nombre y numero interno del Equipo Asociado al Servicio Seleccionado
		$datos_equipo = mysql_fetch_array(mysql_query("SELECT no_interno,nombre FROM equipo_lab JOIN cronograma_servicios ON no_interno=equipo_lab_no_interno WHERE id_servicio = '$idServicio'"));
		$noInterno = $datos_equipo['no_interno'];
		$nombreEquipo = $datos_equipo['nombre'];
		
		
		
		
		//Esta variable indica si hay datos registros para el equipo seleccionado en la fecha indicada
		$flag = 0;
		
		$error = "No existe Detalle del Servicio de Mantenimiento <u><em>".$nombreEquipo."</u></em>";
		
		//Realizar la consulta para obtener  
		$stm_sql ="SELECT * FROM bitacora_mtto WHERE cronograma_servicios_id_servicio = '$idServicio'";

		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle de la tabla Deducciones
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){			
			
			$flag=1;
			
			echo "
			<table cellpadding='5' width='80%'>
				<caption class='titulo_etiqueta'>Detalle del Servicio de Mantenimiento para el Equipo <em><u>$nombreEquipo</u></em></caption>
				<tr>					
					<td class='nombres_columnas' align='center'>SERVICIO</td>
					<td class='nombres_columnas' align='center'>DETALLE DEL SERVICIO</td>
					<td class='nombres_columnas' align='center'>ENCARGADO MTTO</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>									
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='$nom_clase' align='center'>$datos[servicio]</td>
						<td class='$nom_clase' align='center'>$datos[detalle_servicio]</td>
						<td class='$nom_clase' align='center'>$datos[encargado_mtto]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_registro'],1)."</td>
					</tr>";	 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				//$idPBM=$datos['id_pruebas_agregados'];
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>$error</label>";
		}?>
		</div>			
	

		<div id="btns-regpdf" align="center">
		<table align="center" >
			<tr>			
				<td align="center">
					<form name="frm_regresarConsulta" method="post" action="frm_reporteMttoEquipoLab.php">
						<input type="hidden" name="txt_fechaIni" value="<?php echo $fechaIni; ?>" />
						<input type="hidden" name="txt_fechaFin" value="<?php echo $fechaFin; ?>" /><?php
						//Enviar el Combo en el caso que se consulte el Detalle del Registro de la Bitacora de Mtto
						if(isset($_POST['cmb_nombreEquipoLab'])){?>
							<input type="hidden" name="cmb_nombreEquipoLab" value="<?php echo $nombreEquipo; ?>" /><?php
						}?>
						<input type="hidden" name="sbt_generarRepNombre" value="" />
						
						
						<input type="submit" name="sbt_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Agregados" 
						onMouseOver="window.estatus='';return true" />
					</form>
				</td><?php 
				if($flag==1){ //Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td align="center">
						<form onsubmit="return complementarRptMtto();" method="post" action="guardar_reporte.php">
							<input name="hdn_origen" type="hidden" value="reporteMttoEquipoLab"/>	
							
							<input name="hdn_idServicio" type="hidden" value="<?php echo $idServicio; ?>"/>
							<input name="hdn_fechaIni" type="hidden" value="<?php echo $fechaIni; ?>"/>
							<input name="hdn_fechaFin" type="hidden" value="<?php echo $fechaFin; ?>"/>
							
							<input name="hdn_nombreElaboro" id="hdn_nombreElaboro" type="hidden" value=""/>
							
							<input name="hdn_nomReporte" type="hidden" value="Reporte_EquipoLab_<?php echo $noInterno;?>" />
							<input name="hdn_msg" type="hidden" value="<?php echo $_POST['hdn_titulo']; ?>"/>							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"/>
						</form>
					</td><?php 
				}?>
			</tr>
		</table>			
		</div><?php 
		//Cerrar la conexion con la BD
		mysql_close($conn); 
	}
	
	
?>