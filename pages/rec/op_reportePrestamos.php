<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Prestamos de los empleados 
	**/
	
	//Función que permite mostrar el reporte de Prestamos
	function reportePrestamos(){
		//Creamos el DIV para mostrar los empleados?>
		<div id="tabla-empleados" align="center" class="borde_seccion2" width="100%"><?php		

		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
		
		if(isset($_POST["txt_fechaIni"])){
			//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
			$fechaIni=modFecha($_POST["txt_fechaIni"],3);
			$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		}
		else{
			//Tomamos la fecha de los hidden cuando se regrea a la consulta despues de ver el detalle
			$fechaIni=modFecha($_POST["hdn_fechaIni"],3);
			$fechaFin=modFecha($_POST["hdn_fechaFin"],3);
		}
		
		//Crear la consulta
		$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, id_deduccion, nom_deduccion, descripcion, total,
					autorizo, fecha_alta FROM deducciones JOIN empleados on (empleados_rfc_empleado=rfc_empleado)	WHERE fecha_alta>='$fechaIni'
					AND fecha_alta<='$fechaFin' ORDER BY nombre";	
					
		//Mensaje para desplegar en el titulo de la tabla
		$msg_titulo = "De: <em><u><alingn='center'>".modFecha($fechaIni,2)."</u></em>  Al:  <em><u>".modFecha($fechaFin,2)."</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún result ado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados de Prestamos del: <em><u>".modFecha($fechaIni,2)."</u></em> Al: <em><u>".modFecha($fechaFin,2)."</u></em>";			
			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table align='center' class='tabla-frm' cellpadding='5'>
					<caption class='titulo_etiqueta'>Reporte de Pr&eacute;stamos $msg_titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>VER DETALLE</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>NOMBRE DEDUCCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>TOTAL</td>
						<td align='center' class='nombres_columnas'>AUTORIZO</td>
						<td align='center' class='nombres_columnas'>FECHA ALTA</td>
					</tr>
					
				<form name='frm_detallePrestamosEmpleados' method='post' action='frm_reportePrestamos.php'>
				<input type='hidden' name='verDetalle' value='si' />";
				if(isset($_POST["txt_fechaIni"])){//Tomamos las fechas del post cuando es la primera vez que se consulta
					echo "<input type='hidden' name='hdn_fechaIni1' value='$_POST[txt_fechaIni]'/>
						<input type='hidden' name='hdn_fechaFin1' value='$_POST[txt_fechaFin]'/>";
				}
				else{//Verificamos si vienen definidos los hidden para retomar las fechas de la consulta 
					echo "<input type='hidden' name='hdn_fechaIni1' value='$_POST[hdn_fechaIni]'/>
						<input type='hidden' name='hdn_fechaFin1' value='$_POST[hdn_fechaFin]'/>";
				}
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				//Ejecutamos la consulta para obtener el numero la asistencia del empleado
				echo "<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_detallePrestamos' value='$datos[id_deduccion]'
							onClick='javascript:document.frm_detallePrestamosEmpleados.submit();'/>
						</td>					
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[nom_deduccion]</td>						
						<td align='center' class='$nom_clase'>$datos[descripcion]</td>				
						<td align='center' class='$nom_clase'>$".number_format($datos["total"],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$datos[autorizo]</td>						
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_alta'],1)."</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "	</table></form>";
		}//Cierre if($datos = mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>
		</div>			
		<div id="btns-regpdf" align="center">
		<table align="center" >
			<tr>			
				<td align="center">
				  	<input type="button" name="btn_regresar"  value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Pr&eacute;stamos" 
					onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reportePrestamos.php'" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
					<?php 
					if($flag==1){ //Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
						<td align="center">
							<form action="guardar_reporte.php" method="post">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Prestamos_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
								<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>"/>
								<input name="hdn_origen" type="hidden" value="reportePrestamos"/>	
								<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
								title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"/>
							</form>
						</td>
				<?php }?>
			</tr>
		</table>			
		</div>
		<?php							
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
	
	
	//Funcion que permite mostrar el detalle de los prestanmos  de acuerdo al Empleado
	function detallePrestamos($idDeduccion){
	
		//Realizar la conexion a la BD de Recuros Humanos
		$conn = conecta("bd_recursos");
		
		//Realizar la consulta para obtener el detalle de los prestamos del Empleado 
		$stm_sql ="SELECT nom_deduccion, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, saldo_inicial, abono, saldo_final, fecha_abono 
					FROM (detalle_abonos JOIN deducciones ON id_deduccion=deducciones_id_deduccion) JOIN empleados ON rfc_empleado = empleados_rfc_empleado 
					WHERE deducciones_id_deduccion = '$idDeduccion'";

		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle de la tabla Deducciones
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "
			<input type='hidden' name='hdn_fechaIni' value='$_POST[hdn_fechaIni1]'/>
			<input type='hidden' name='hdn_fechaFin' value='$_POST[hdn_fechaFin1]'/>
			<table cellpadding='5' width='80%'>
				<caption class='titulo_etiqueta'>Detalle de la Deducci&oacute;n <em><u>$datos[nom_deduccion]</u></em> Asignada al Empleado <em><u>$datos[nombre]</u></em></caption>
				<tr>					
					<td class='nombres_columnas' align='center'>FECHA ABONO</td>
					<td class='nombres_columnas' align='center'>SALDO INICIAL</td>
					<td class='nombres_columnas' align='center'>ABONO</td>
					<td class='nombres_columnas' align='center'>SALDO FINAL</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_abono'],1)."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["saldo_inicial"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["abono"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["saldo_final"],2,".",",")."</td>						
					</tr>";	 
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn); 
	}
?>