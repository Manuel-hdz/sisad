<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 22/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Consultar Mezcla en la BD
	**/
	
	//Funcion que se encarga de desplegar las mezclas en el rango de fechas
	function mostrarMezclas(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultar la buqueda de las mezclas proviene de un rango de fechas
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM mezclas WHERE fecha_registro BETWEEN '$f1' AND '$f2' AND estado='1' ORDER BY id_mezcla";
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Mezclas Registradas en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_fechaIni" value="<?php echo $_POST['txt_fechaIni'] ?>"/>
			<input type="hidden" name="hdn_fechaFin" value="<?php echo $_POST['txt_fechaFin'] ?>"/>
			<input type="hidden" name="hdn_consultar" value="<?php echo $_POST['sbt_consultar'] ?>"/><?php
		}
		
		//Si viene sbt_consultar2 la buqueda de la mezcla proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM mezclas WHERE id_mezcla = '$_POST[cmb_claveMezcla]' AND estado='1'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Mezcla <em><u> $_POST[cmb_claveMezcla]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla </label>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_idMezcla" value="<?php echo $_POST['cmb_claveMezcla'] ?>"/>
			<input type="hidden" name="hdn_consultar2" value="<?php echo $_POST['sbt_consultar2'] ?>"/><?php
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>
				<tr>
					<td colspan='6' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='10%'>DETALLE</td>
					<td class='nombres_columnas' align='center' width='15%'>ID MEZCLA</td>
					<td class='nombres_columnas' align='center' width='25%'>NOMBRE MEZCLA</td>
					<td class='nombres_columnas' align='center' width='10$'>EXPEDIENTE</td>
					<td class='nombres_columnas' align='center' width='25%'>EQUIPO MEZCLADO</td>
					<td class='nombres_columnas' align='center' width='15%'>FECHA REGISTRO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' width='6%' align='center'>
							<input type='checkbox' name='ckb_idMezcla' value='$datos[id_mezcla]' 
							onClick='javascript:document.frm_consultarDetMezcla.submit();'/>
						</td>
						<td class='$nom_clase'>$datos[id_mezcla]</td>
						<td class='$nom_clase'>$datos[nombre]</td>
						<td class='$nom_clase'>$datos[expediente]</td>
						<td class='$nom_clase'>$datos[equipo_mezclado]</td>
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
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	//Funcion que permite mostrar el detalle de la mezcla seleccionado en un checkbox
	function mostrarDetalleMezcla(){
		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Realizar la consulta para obtener el detalle de la mezcla seleccionada  
		$stm_sql = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla= '$_POST[ckb_idMezcla]'";
		
		//Variable bandera para asegurar que la consulta arrojo datos
		$flag=0;
		
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$flag=1;
			echo "					
			<label class='titulo_etiqueta'>MATERIALES REGISTRADOS A LA MEZCLA CON ID <em><u>$_POST[ckb_idMezcla]</u></em></label>								
			<br><br>								
			<table width='90%' cellpadding='5'>
				<tr>
					<td class='nombres_columnas' align='center'>CLAVE MATERIAL</td>
					<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
					<td class='nombres_columnas' align='center'>NOMBRE</td>					
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
					<td class='nombres_columnas' align='center'>VOLUMEN</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Obtener el nombre del material de la bd de almacen
				$nomMaterial=obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datos['catalogo_materiales_id_material']);
				//Obtener la categoria del material de la bd de almacen
				$categoriaMat=obtenerDato('bd_almacen', 'materiales', 'linea_articulo','id_material', $datos['catalogo_materiales_id_material']);
				
				//Obtener el numero de decimales de la cantidad del material
				$cantDecimales = contarDecimales($datos['cantidad']);
				echo "<tr>	
						<td class='$nom_clase'>$datos[catalogo_materiales_id_material]</td>
						<td class='$nom_clase' align='center'>$categoriaMat</td>
						<td class='$nom_clase' align='center'>$nomMaterial</td>
						<td class='$nom_clase' align='center'>".number_format($datos['cantidad'], $cantDecimales,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
						<td class='$nom_clase' align='center'>".number_format($datos['volumen'], 2,".",",")." m&sup3;</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "
				</table><br><br><br>";
		}else{
			echo "<br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No Hay Materiales Registrados de la Mezcla</p>";
		}?>
		</div>			
		<div id="btns-regpdf" align="center"> 
		<table align="center" >
			<tr>
				<td width="86">&nbsp;</td>
				<?php 
					if($flag==1){ //Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
						<td width="223" align="center">
							<form method="post" action="guardar_reporte.php">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
								<input name="hdn_nomReporte" type="hidden" 
								value="ReporteMezcla_<?php echo $_POST['ckb_idMezcla'];?>" />
								<input name="hdn_msg" type="hidden" value="<?php echo $_POST['ckb_idMezcla'];?>"/>
								<input name="hdn_origen" type="hidden" value="reporteMezclas"/>	
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