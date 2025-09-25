<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa, Daisy Adriana Martinez Fernandez
	  * Fecha: 16/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de reporte de mezclas
	**/
	
	//Funcion que se encarga de desplegar las mezclas en el rango de fechas
	function mostrarRendMezclas(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		if(isset($_POST['sbt_continuar'])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM rendimiento WHERE mezclas_id_mezcla='$_POST[cmb_idMezcla]'";
		
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Mezcla con Clave <em><u> $_POST[cmb_idMezcla]</u></em>";
		
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla </label>";	
	
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_idMezcla" value="<?php echo $_POST['cmb_idMezcla'] ?>"/>
			<input type="hidden" name="hdn_continuar" value="<?php echo $_POST['sbt_continuar'] ?>"/><?php
		}
		
		if(isset($_POST['sbt_continuar2'])){
		
			//Obterner la fecha
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);

			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM rendimiento WHERE fecha_registro BETWEEN '$f1' AND '$f2'";
		
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Mezcla  en las Fechas del <em><u> $_POST[txt_fechaIni] </u></em> al 
			<em><u> $_POST[txt_fechaFin] </u></em>";
		
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla en las Fechas del <em><u> $_POST[txt_fechaIni] </u></em> al 
			<em><u> $_POST[txt_fechaFin] </u></em></label>";	
	
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_idFechaIni" value="<?php echo $_POST['txt_fechaIni'];?>"/>
			<input type="hidden" name="hdn_idFechaFin" value="<?php echo $_POST['txt_fechaFin'];?>"/>
			<input type="hidden" name="sbt_continuar2" value="<?php echo $_POST['sbt_continuar2'] ?>"/><?php
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='8' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>DETALLE</td>
					<td class='nombres_columnas'>ID MEZCLA</td>					
					<td class='nombres_columnas'>MUESTRA</td>
					<td class='nombres_columnas'>LOCALIZACION</td>
					<td class='nombres_columnas'>REVENIMIENTO</td>					
					<td class='nombres_columnas'>TEMPERATURA</td>
					<td class='nombres_columnas'>HORA</td>
					<td class='nombres_columnas'>FECHA</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas'>
							<input type='checkbox' name='ckb_idRegRend' value='$datos[id_registro_rendimiento]/$datos[mezclas_id_mezcla]' 
							onClick='javascript:document.frm_detalleMezcla.submit();'/>
						</td>
						<td class='$nom_clase'>$datos[mezclas_id_mezcla]</td>
						<td class='$nom_clase'>$datos[num_muestra]</td>
						<td class='$nom_clase'>$datos[localizacion]</td>
						<td class='$nom_clase'>$datos[revenimiento] CM</td>
						<td class='$nom_clase'>$datos[temperatura] &deg;C</td>
						<td class='$nom_clase'>$datos[hora] HRS:MIN</td>
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
		
		$seccRegRend=split("/",$_POST['ckb_idRegRend']);
		$idMezcla = $seccRegRend[1];
		$idRegRend = $seccRegRend[0];
		
		//Realizar la consulta para obtener el detalle de la mezcla seleccionada  
		$stm_sql = "SELECT pvol_bruto,pvol_molde,pvol_unit,factor_recipiente,pvol_teorico_rend,pvol_rend,pvol_teorico_caire,pvol_caire,cb,r, caire_real 
		FROM detalle_rendimiento WHERE rendimiento_id_registro_rendimiento = '$idRegRend'";
		
		//Variable bandera para asegurar que la consulta arrojo datos
		$flag=0;
		
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$flag=1;
			echo "					
			<label class='titulo_etiqueta'>DETALLE DE RENDIMIENTO DE LA MEZCLA $idMezcla</label>								
			<br><br>								
			<table cellpadding='5' width='100%'>
				<tr>
					<td class='nombres_columnas' align='center'>PESO BRUTO</td>
					<td class='nombres_columnas' align='center'>PESO MOLDE</td>
					<td class='nombres_columnas' align='center'>PESO UNITARIO</td>
					<td class='nombres_columnas' align='center'>FACTOR RECIPIENTE</td>
					<td class='nombres_columnas' align='center'>PESO T&Eacute;ORICO RENDIMIENTO</td>
					<td class='nombres_columnas' align='center'>PESO RENDIMIENTO</td>
					<td class='nombres_columnas' align='center'>PESO T&Eacute;ORICO AIRE</td>
					<td class='nombres_columnas' align='center'>PESO AIRE</td>
					<td class='nombres_columnas' align='center'>CB</td>
					<td class='nombres_columnas' align='center'>R</td>
					<td class='nombres_columnas' align='center'>CONTENIDO REAL AIRE</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{				
				echo "<tr>	
						<td class='$nom_clase' align='center'>".round($datos['pvol_bruto'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['pvol_molde'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['pvol_unit'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['factor_recipiente'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['pvol_teorico_rend'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['pvol_rend'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['pvol_teorico_caire'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['pvol_caire'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['cb'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['r'], 5)."</td>
						<td class='$nom_clase' align='center'>".round($datos['caire_real'], 5)."</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));			
			echo "</table><br><br><br>";	
		}else{
			echo "<br><br><p align='center' class='msje_correcto'>NO hay Detalles de Rendimiento Registrados de la Mezcla</p>";
		}?>			
		</div>
		
		
		<div id="btns-regpdf" align="center"> 
		<table width="619" align="center" >
			<tr>
				<td width="14">&nbsp;</td><?php 
				if($flag==1){ //Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="295" align="center">
						<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
						<input name="hdn_nomReporte" type="hidden" value="ReporteRendimiento_<?php echo $_POST['ckb_idRegRend'];?>" />
						<input name="hdn_tipoReporte" type="hidden" value="reporteRendimiento"/>
						<input name="hdn_idRegRendimiento" type="hidden" value="<?php echo $idRegRend;?>"/>
						<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
						title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"/>
						
			 		</td><?php 
				}?>
			</tr>
		</table>			
		</div><?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion mostrarDetalleMezcla()
		
	
	//Funcion que permite mostrar el detalle de la mezcla seleccionado en un checkbox
	function mostrarDetalleMateriales(){
		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		//Verificar que la consulta tenga datos
		
		
		$seccRegRend = split("/",$_POST['ckb_idRegRend']);
		$idMezcla = $seccRegRend[1];
		$idRegRend = $seccRegRend[0];
		
		
		/********************************************OBTENER LOS DATOS DEL DISEÑO DE LA MEZCLA********************************************/
		//Verificar si el diseño original fue modificado, buscar en la tabla de Cambios Diseño Mezcla primero
		$sql_stm_mat1 = "SELECT * FROM cambios_disenio_mezcla WHERE rendimiento_id_registro_rendimiento = $idRegRend AND mezclas_id_mezcla = '$idMezcla'";
		$sql_stm_mat2 = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$idMezcla'";
		$sql_stm_materiales = "";
		
		//Verificar si la primera consulta regresa datos, para tomar el diseño de la mezcla de ahi
		if($datos=mysql_fetch_array(mysql_query($sql_stm_mat1)))
			$sql_stm_materiales = $sql_stm_mat1;
		else//Si el diseño no fue modificado, tomar los datos de la segunda consulta
			$sql_stm_materiales = $sql_stm_mat2;
																		
		
		//Ejecutar la sentencia para obtener los datos del diseño que fue modificado o en su defecto el original
		$rs = mysql_query($sql_stm_materiales);
		
		//Mostrar los datos obtenidos
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>DETALLE DE MATERIALES DE LA MEZCLA $idMezcla</label>								
			<br><br>								
			<table cellpadding='5' width='100%'>
				<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>NOMBRE DEL MATERIAL</td>
					<td class='nombres_columnas' align='center'>PESO UNITARIO</td>
					<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
					<td class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N</td>		
				</tr>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{				
				//Recuperar datos adicionales del los materiales de la mezcla seleccionada
				$nomMaterial = obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datos['catalogo_materiales_id_material']);				
				$categoriaMat = obtenerDato('bd_almacen', 'materiales', 'linea_articulo','id_material', $datos['catalogo_materiales_id_material']);
				
				echo "
				<tr>	
					<td class='$nom_clase' align='center'>$cont</td>
					<td class='$nom_clase' align='center'>$nomMaterial</td>
					<td class='$nom_clase' align='center'>".round($datos['cantidad'], 5)."</td>
					<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
					<td class='$nom_clase' align='center'>$categoriaMat</td>
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
			echo "<br><br><br><br><p align='center' class='msje_correcto'>NO hay Materiales Asociados a la Mezcla</p>";
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		
	}//Cierre de la funcion mostrarDetalleMateriales()	
	
	
?>