<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 03/Marzo/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Orden de Trabajo
	  **/
	  
	  
	function generarReporte($tipo_rpt){ 
		?><div id="resultados" align="center" class="borde_seccion2"><?php
 		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		$flag = 0;		
		
		/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/				
		$paramExtra = "";				
		if($_SESSION['depto']=="MttoConcreto")
			$paramExtra = "AND area='CONCRETO'";									
		else if($_SESSION['depto']=="MttoMina")
			$paramExtra = "AND area='MINA'";
				
		switch($tipo_rpt){
			case 1:
				//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
				$f1 = modFecha($_POST['txt_fechaIni'],3);
				$f2 = modFecha($_POST['txt_fechaFin'],3);													
				
				
				//Crear la consulta
				$stm_sql ="SELECT id_orden_trabajo,equipos_id_equipo,equipos.estado AS edo,servicio,fecha_creacion,fecha_prog,orden_trabajo.turno,orden_trabajo.horometro,
						   orden_trabajo.odometro,operador_equipo,orden_trabajo.comentarios,orden_trabajo.estado,autorizo_ot,costo_mtto
						   FROM (orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo = orden_trabajo_id_orden_trabajo) JOIN equipos ON id_equipo=equipos_id_equipo 
						   WHERE fecha_mtto>='$f1' AND fecha_mtto<='$f2' ".$paramExtra." ORDER BY id_orden_trabajo";
		
				
				$msg_titulo = "Reporte de Orden de Trabajo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";

				$msg_grafica = "Orden de Trabajo del $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";
			
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Orden de Trabajo del 
								<em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";

				//Definir datos del reporte de mantenimientos preventivos en la SESSION
				$datos = array("txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
				$_SESSION['datosRptOT'] = $datos;								
			break;
			case 2:
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
				$f3 = modFecha($_POST['txt_fechaIni'],3);
				$f4 = modFecha($_POST['txt_fechaFin'],3);
				
				//Crear la consulta
				$stm_sql ="SELECT id_orden_trabajo,equipos_id_equipo,equipos.estado AS edo,servicio,fecha_creacion,fecha_prog,orden_trabajo.turno,orden_trabajo.horometro,
						   orden_trabajo.odometro,operador_equipo,orden_trabajo.comentarios,orden_trabajo.estado,autorizo_ot,costo_mtto
						   FROM (orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo = orden_trabajo_id_orden_trabajo) JOIN equipos ON id_equipo=equipos_id_equipo						    
						   WHERE servicio='$_POST[cmb_servicio]' AND fecha_mtto>='$f3' AND fecha_mtto<='$f4' ".$paramExtra." ORDER BY id_orden_trabajo";
		
				
					//Mensaje que desplegara el titulo de la tabla
				$msg_titulo = "Reporte de Mantenimientos por Servicio <em><u>$_POST[cmb_servicio]</u></em>&nbsp;
								<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
				
				if($_POST['cmb_servicio']=="INTERNO")
					$msg_grafica = "Mantenimientos por Servicio Interno";
				else
					$msg_grafica = "Mantenimientos por Servicio Externo";
				
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Tipos de Servicio de Mantenimientos <em><u>
				$_POST[cmb_servicio]</u></em>&nbsp;
								<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";
				
				//Definir datos del reporte de mantenimientos preventivos en la SESSION
				$datos = array("cmb_servicio"=>$_POST['cmb_servicio'],"txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
				$_SESSION['datosRptOT'] = $datos;
			break;			
		}

		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "				
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>					
					<tr>
						<td class='nombres_columnas'>CLAVE ORDEN TRABAJO</td>
						<td class='nombres_columnas'>CLAVE EQUIPO</td>
						<td class='nombres_columnas'>ESTADO</td>
						<td class='nombres_columnas'>SERVICIO</td>
						<td class='nombres_columnas'>FECHA CREACION</td>						
						<td class='nombres_columnas'>FECHA PROG.</td>						
						<td class='nombres_columnas'>TURNO</td>						
						<td class='nombres_columnas'>HOROMETRO/ODOMETRO</td>
						<td class='nombres_columnas'>COSTO MTTO</td>
						<td class='nombres_columnas'>OPERADOR</td>						
						<td class='nombres_columnas'>COMENTARIOS</td>	
						<td class='nombres_columnas'>ESTADO</td>						
						<td class='nombres_columnas'>AUTORIZACION</td>											
					</tr>
					<input type='hidden' name='no_reporte' value='$tipo_rpt' />";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Obtener el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = $datos['horometro']." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = $datos['odometro']." Kms.";
					
				//Determinar el Estado de la Requisicion
				$estado = "";
				if($datos['estado']==0)
					$estado = "PROGRAMADA";
				else
					$estado = "EJECUTADA";
					
			
				echo"
					<td class='$nom_clase'>$datos[id_orden_trabajo]</td>
					<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
					<td class='$nom_clase'>$datos[edo]</td>
					<td class='$nom_clase'>$datos[servicio]</td>
					<td class='$nom_clase'>".modFecha($datos['fecha_creacion'],1)."</td>
					<td class='$nom_clase'>".modFecha($datos['fecha_prog'],1)."</td>
					<td class='$nom_clase' align='left'>$datos[turno]</td>
					<td class='$nom_clase'>$metrica</td>
					<td class='$nom_clase'>$".number_format($datos['costo_mtto'],2,".",",")."</td>
					<td class='$nom_clase' align='left'>$datos[operador_equipo]</td>
					<td class='$nom_clase' align='left'>$datos[comentarios]</td>
					<td class='$nom_clase' align='center'>$estado</td>
					<td class='$nom_clase' align='center'>$datos[autorizo_ot]</td>
				</tr>";	
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}//Cierre if($datos=mysql_fetch_array($rs))
			else//Si no se encuentra ningun resultado desplegar un mensaje					
				echo $msg_error;?></div>							
			
			
		<div id="btns-rpt" >
		  <table align="center">
			<tr>
				<td width="130" align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Orden de Trabajo" onclick="location.href='frm_reporteOrdenTrabajo.php'" />
				</td>
				<?php  if($flag==1) { ?>
					<td width="131" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Consulta de orden de Trabajo" />                  		
							<input name="hdn_origen" type="hidden" value="ordenTrabajo" />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" onmouseover="window.estatus='';return true"  />
						</form>
					</td>              
				<?php }
				if($flag==1){ ?> 
					<td width="130" align="center">                  
						<?php 
							$datosGrafica = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
							$_SESSION['datosGrafica'] = $datosGrafica;
						?>						
						<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica Orden de Trabajo" 
						onClick="javascript:window.open('verGraficas.php?graph=ordenTrabajo',
						'_blank','top=100, left=250, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
				 	</td>
				<?php } ?>
			</tr>
		</table>
</div>

<?php		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>
