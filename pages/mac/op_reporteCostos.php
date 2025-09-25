<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 30/Abril/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Mantenimientos Correctivos
	  **/
	function generarReporte(){
		?><div id="reporte" align="center" class="borde_seccion2"><?php
		
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 0;		
		
		if(isset($_POST['cmb_equipo'])){				
			//Reporte por Equipo
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			$idEquipo = $_POST['cmb_equipo'];
			
			//Crear la consulta
			$stm_sql ="SELECT DISTINCT equipos.id_equipo, equipos.nom_equipo FROM equipos WHERE id_equipo = '$idEquipo' ORDER BY id_equipo";
			
			//Mensaje que desplegara el titulo de la tabla
			$msg_titulo = "Reporte de Costo de Mantenimientos Para el Equipo <u><em>$_POST[cmb_equipo]</u></em>	<br>En las Fechas del <em><u>$_POST[txt_fechaIni]";
			$msg_titulo .="</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";					
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mantenimientos Para el Equipo <em><u>";
			$msg_error.= "$_POST[cmb_equipo]</u></em>&nbsp;	<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al";			
			$msg_error.= "<em><u>$_POST[txt_fechaFin]</u></em></label>";
		}
		else{//Reporte por Familia
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			$familia = $_POST['cmb_familia'];
			
			//Crear la consulta
			$stm_sql ="SELECT DISTINCT equipos.id_equipo, equipos.nom_equipo FROM equipos WHERE familia = '$familia'  AND area='CONCRETO' ORDER BY id_equipo";
			
			//Mensaje que desplegara el titulo de la tabla				
			$msg_titulo = "	Reporte de Mantenimientos Correctivos Correspondiente a la Familia: <em><u>$_POST[cmb_familia]</u></em> 
								<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			$msg_grafica = "Mantenimientos Correctivos a la Familia: $_POST[cmb_familia]";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mantenimientos Correctivos de la Familia <em>						
				<u>$_POST[cmb_familia]</u></em>&nbsp;
				<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";			
		}		
																			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "				
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$msg_titulo</caption>					
				<tr>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE EQUIPO</td>
					<td class='nombres_columnas'>NOMBRE EQUIPO</td>
					<td class='nombres_columnas'>COSTO MATERIALES PEDIDOS</td>
					<td class='nombres_columnas'>COSTO MANO DE OBRA</td>
					<td class='nombres_columnas'>COSTO DE ORDEN DE TRABAJO EXTERNA</td>
					<td class='nombres_columnas'>COSTO TOTAL EQUIPO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$cant_total = 0;
			do{
				$conn=conecta('bd_mantenimiento');
				//Creamos la consulta que nos permite obtener el costo del mantenimiento
				$stm_sqlCM ="SELECT SUM(costo_mtto) AS costo_mtto, id_bitacora FROM (equipos JOIN bitacora_mtto ON equipos.id_equipo=bitacora_mtto.equipos_id_equipo)
				WHERE id_equipo='$datos[id_equipo]' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2'";
				//Ejecutamos la consulta previamente creada
				$datosCM=mysql_fetch_array(mysql_query($stm_sqlCM));
				//Sentencia que nos permite obtener el costo de los materiales; si este valor viene como cero (0) el costo de la mano de obra sera el costo total 
				//del Mantenimiento
				$stm_sqlCMat ="SELECT SUM(costo_vale) AS costo_vale FROM (materiales_mtto JOIN bitacora_mtto ON id_bitacora=bitacora_mtto_id_bitacora)  
				WHERE equipos_id_equipo='$datos[id_equipo]' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2'";
				//Ejecutamos la cionsulta previamente creada
				$datosCMat=mysql_fetch_array(mysql_query($stm_sqlCMat));
				//Consulta 	que nos permite obtener el costo de la orden de trabajo
				$stm_sqlCOT ="SELECT SUM(costo_actividad) AS costo_actividad FROM (actividades_realizadas JOIN orden_servicios_externos ON
				id_orden=orden_servicios_externos_id_orden) WHERE equipo='$datos[id_equipo]' AND fecha_entrega>='$f1' AND fecha_entrega<='$f2' ";
				//Ejecutamos la sentencia previamente creada
				$datosCOT=mysql_fetch_array(mysql_query($stm_sqlCOT));
				//Conectamos a la BAse de datos de compra para obtener el costo de los materiales
				$conn2=conecta('bd_compras');
				//Sentencia que nos permite obtener el costo de los materiales pedidos en compras
				$stm_sqlImpC = "SELECT SUM(importe) AS importe FROM (detalles_pedido JOIN pedido ON pedido_id_pedido=pedido_id_pedido)WHERE 
				equipo='$datos[id_equipo]' AND fecha_entrega>='$f1' AND fecha_entrega<='$f2'";
				//Ejecutamos la sentencia previamente creada
				$datosImpC=mysql_fetch_array(mysql_query($stm_sqlImpC));
				//Verificamos si el valor del costo del vale es  vacio si es asi el costo de la mano de obra es el costo del vale
				if($datosCMat['costo_vale']!=""){
					$costoManoObra = $datosCM['costo_mtto'];
				}
				else{
					//De lo contrario el costo de la Mano de Obra sera igual al costo de los materiales del mantenimiento menos el costo del vale
					$costoManoObra = $datosCM['costo_mtto'] -$datosCMat['costo_vale'];
				}
				//Obtenemos el costo total del mantenimiento
				$costoMtto =$costoManoObra+$datosImpC['importe']+$datosCOT['costo_actividad'];
				echo "	
				<tr>";
					echo"	
					<td class='nombres_filas'>$cont</td>							
					<td class='nombres_filas'>$datos[id_equipo]</td>
					<td class='$nom_clase'>$datos[nom_equipo]</td>
					<td class='$nom_clase'>$".number_format($datosImpC['importe'],2,".",",")."</td>
					<td class='$nom_clase'>$".number_format($costoManoObra,2,".",",")."</td>
					<td class='$nom_clase'>$".number_format($datosCOT['costo_actividad'],2,".",",")."</td>
					<td class='$nom_clase'>$".number_format($costoMtto,2,".",",")."</td>
				</tr>";	
				//Acumulamos la cantidad total del mantenimiento					
				$cant_total += $costoMtto;	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "
					<tr>
						<td colspan='5'>&nbsp;</td>
						<td align='right'><strong>COSTO TOTAL</strong></td>
						<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>
					</tr>";
			echo "</table>";
		}//Cierre if($datos=mysql_fetch_array($rs))
	 	else
			echo $msg_error;?>			
		</div><!--Cierre del Layer "reporte" -->	
			
		
		<div id="btns-rpt">
		<table width="100%">
			<tr>
				<td width="51%" align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Costos" 
					onclick="location.href='frm_reporteCostos.php'" />
			  </td>
				<?php if($flag==1) { ?>              
					<td width="49%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_consultaCM" type="hidden" value="<?php echo $stm_sqlCM; ?>" />
							<input name="hdn_consultaCMat" type="hidden" value="<?php echo $stm_sqlCMat; ?>" />
							<input name="hdn_consultaCOT" type="hidden" value="<?php echo $stm_sqlCOT; ?>" />
							<input name="hdn_consultaImpC" type="hidden" value="<?php echo $stm_sqlImpC; ?>" />							
							<input name="hdn_nomReporte" type="hidden" value="Consulta Mtto Costos  " />                  		
							<input name="hdn_origen" type="hidden" value="mttoCostos" />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" onmouseover="window.estatus='';return true"/>
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