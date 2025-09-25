<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 18/Octubre/2010                                      			
	  * Descripción: Este archivo contiene funciones para Mostrar la información seleccionada en el formulario de Reporte de Orden de Compra
	  **/
	  	  	
	
	function mostrarOrdenesCompra($txt_fechaInicio,$txt_fechaCierre){
		//Realizar la conexion a la BD de Almacen			
		$conn = conecta('bd_almacen');
		
		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($txt_fechaInicio,3);
		$f2 = modFecha($txt_fechaCierre,3);

		//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
		$stm_sql = "SELECT * FROM orden_compra WHERE fecha_oc>='$f1' AND fecha_oc<='$f2'";
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);		
		$msg = "Reporte de Ordenes de Compra generadas del <strong><u>$txt_fechaInicio</u></strong> al <strong><u>$txt_fechaCierre</u></strong>";

		if($row = mysql_fetch_array($rs)){			
			echo "
			<table class='tabla_frm' cellpadding='5' width='700'>
			<tr>
				<td colspan='5' align='center' class='titulo_etiqueta'>$msg</td>
  			</tr>
			<tr>
				<td class='nombres_columnas'>Ver Detalle</td>
				<td class='nombres_columnas'>Clave</td>
				<td class='nombres_columnas'>Fecha</td>
				<td class='nombres_columnas'>&Aacute;rea Solicitante</td>
				<td class='nombres_columnas'>Solicit&oacute;</td>
			</tr>";			
			//Mostramos los registros
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<form name='frm_mostrarDetalleOC' method='post' action='frm_reporteOrdenCompra.php'>
					<input type='hidden' name='fecha_ini' value='$txt_fechaInicio' />
					<input type='hidden' name='fecha_end' value='$txt_fechaCierre' />";			
			do{								
				echo "
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='OC$cont' value='$row[id_orden_compra]' onClick='javascript:document.frm_mostrarDetalleOC.submit();'/></td>
						<td class='$nom_clase'>$row[id_orden_compra]</td>
						<td class='$nom_clase'>".modFecha($row['fecha_oc'],1)."</td>
						<td class='$nom_clase' align='left'>$row[a_solicitante_oc]</td>
						<td class='$nom_clase' align='left'>$row[solicitante_oc]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";						
			}while($row = mysql_fetch_array($rs));
			echo "
				<tr>
					<td class='nombres_filas'><input type='checkbox' name='OC' value='todos' onClick='javascript:document.frm_mostrarDetalleOC.submit();'/></td>
					<td class='$nom_clase' colspan='4' align='left'>Ver Detalle de Todo</td>					
				</tr>
			</form>
			</table>";?>
			</div>
			</br>
			<div id="btns-regpdf" align="center">
			<table width="30%" cellpadding="12">
				<tr><td colspan="11"><input name="btn_regresar" type="button" value="Regresar" class="botones" title="Seleccionar Otro Rango de Fechas" onclick="location.href='frm_reporteOrdenCompra.php'" /></td></tr>
			</table>
			</div><?php			
		}
		else
			echo "<meta http-equiv='refresh' content='0;url=advertencia.php'>";
	}//Cierre de la funcion mostrarOrdenesCompra($theDate,$theDate2)
	
	
	
	function mostrarDetalleOC($clave,$fecha_ini,$fecha_end){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");	 									
		
		if($clave=="todos"){
			$fecha_i = modFecha($fecha_ini,3); $fecha_e = modFecha($fecha_end,3); 
			//Crear la consulta para mostrar el detalle de la Orden de Compra a mostrar
			$stm_sql = "SELECT * FROM detalle_oc JOIN orden_compra ON orden_compra_id_orden_compra=id_orden_compra WHERE fecha_oc>='$fecha_i' AND fecha_oc<='$fecha_e'";
			$msg = "Detalle de las Ordenes de Compra del <strong><u>$fecha_ini</u></strong> al <strong><u>$fecha_end</u></strong>";
		}
		else{
			//Crear la consulta para mostrar el detalle de la Orden de Compra a mostrar
			$stm_sql = "SELECT * FROM detalle_oc JOIN orden_compra ON orden_compra_id_orden_compra=id_orden_compra WHERE orden_compra_id_orden_compra='$clave'";
			$msg = "Detalle de la Orden de Compra No. $clave";			
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);														
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla			
			echo "				
				<table cellpadding='5' width='800'>      			
					<tr>
						<td colspan='7' align='center' class='titulo_etiqueta'>$msg</td>
  					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE</td>
        				<td class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</td>
				        <td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>ID ORDEN COMPRA</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>AREA SOLICITANTE</td>
						<td class='nombres_columnas' align='center'>SOLICITO</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>$datos[catalogo_mf_codigo_mf]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$datos[cant_oc]</td>
						<td class='$nom_clase' align='center'>$datos[id_orden_compra]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_oc'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[a_solicitante_oc]</td>
						<td class='$nom_clase' align='left'>$datos[solicitante_oc]</td>
					</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); ?>
	  		</table>
			</div>
			</br>
			<div id="btns-regpdf" align="center">
			<table width="30%" cellpadding="12">
				<tr>
					<td>
						<form action="frm_reporteOrdenCompra.php" method="post">
							<input type="hidden" name="txt_fechaInicio" value="<?php echo $fecha_ini; ?>" />
							<input type="hidden" name="txt_fechaCierre" value="<?php echo $fecha_end; ?>" />
							<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Seleccionar Otra Orden de Compra" onmouseover="window.status='';return true" />
						</form>	
					</td>				
					<td>
					<?php if($clave=="todos"){ ?>
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"  />
							<input name="hdn_nomReporte" type="hidden" value="Orden de Compra"  />	
							<input name="hdn_tipoReporte" type="hidden" value="orden_compra"  />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"  />						
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar Datos a Excel de las Ordenes de Compra Seleccionadas"  onMouseOver="window.status='';return true"  />
						</form>																												
					<?php }else{ ?>						
						<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Orden de Compra Seleccionada" onmouseover="window.status='';return true" 
						onclick="window.open('../../includes/generadorPDF/orden_compra.php?id=<?php echo $clave; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"  />	
					<?php } ?>					
					</td>
				</tr>
			</table>
			</div>								
			<?php
		}		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	
?>