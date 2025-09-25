<?php
	/**
	  * Nombre del Módulo: Produccion
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 19/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de reporte de mezclas para ser consultado desde 
	  *				 Gerencia Tecnica
	**/
	
	//Funcion que se encarga de desplegar las mezclas 
	function mostrarMuestras(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM muestras WHERE tipo_prueba='$_POST[cmb_tipoPrueba]' AND id_muestra='$_POST[cmb_idMuestra]'";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Muestra con Clave <em><u>$_POST[cmb_idMuestra]</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Muestra</label>";	
		
		// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
		<input type="hidden" name="hdn_idMuestra" value="<?php echo $_POST['cmb_idMuestra'] ?>"/>
		<input type="hidden" name="hdn_tipoMuestra" value="<?php echo $_POST['cmb_tipoPrueba'] ?>"/>
		<input type="hidden" name="hdn_continuar" value="<?php echo $_POST['sbt_continuar'] ?>"/><?php

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='4' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>DETALLE</td>
					<td class='nombres_columnas' align='center'>ID MUESTRA</td>
					<td class='nombres_columnas' align='center'>MEZCLA</td>
					<td class='nombres_columnas' align='center'>FECHA COLADO</td>
					<td class='nombres_columnas' align='center'>REVENIMIENTO</td>
					<td class='nombres_columnas' align='center'>F'c</td>
					<td class='nombres_columnas' align='center'>DI&Aacute;METRO</td>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>			
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'>
							<input type='checkbox' name='ckb_idMuestra' value='$datos[id_muestra]' title='Ver Detalle de Cargas de Ruptura' 
							onClick='javascript:document.frm_detalleMuestra.submit();'/>
						</td>
						<td class='$nom_clase'>$datos[id_muestra]</td>
						<td class='$nom_clase'>$datos[mezclas_id_mezcla]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_colado'],1)."</td>
						<td class='$nom_clase'>$datos[revenimiento] CM</td>
						<td class='$nom_clase'>$datos[fprimac_proyecto] KG/CM&sup2;</td>
						<td class='$nom_clase'>$datos[diametro] CM</td>
						<td class='$nom_clase'>$datos[area] CM&sup2;</td>																							
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
		$stm_sql= "SELECT prueba_calidad_id_prueba_calidad,edad,fecha_ruptura,fprima_c,carga_ruptura,kg_cm2,porcentaje, observaciones FROM detalle_prueba_calidad 
		JOIN prueba_calidad ON prueba_calidad_id_prueba_calidad=id_prueba_calidad JOIN muestras ON muestras_id_muestra=id_muestra WHERE id_muestra='$_POST[ckb_idMuestra]'";
		
		
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>COMPONENTES REGISTRADOS A LA MUESTRA CON ID <em><u>$_POST[ckb_idMuestra]</u></em></label>								
			<br><br>								
			<table cellpadding='5' width='100%'>
				<tr>
					<td class='nombres_columnas' align='center'>EDAD</td>
					<td class='nombres_columnas' align='center'>FECHA RUPTURA</td>
					<td class='nombres_columnas' align='center'>F'c</td>
					<td class='nombres_columnas' align='center'>CARGA RUPTURA</td>
					<td class='nombres_columnas' align='center'>KG CM&sup2;</td>
					<td class='nombres_columnas' align='center'>PORCENTAJE</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{

				echo "<tr>	
						<td class='$nom_clase'>$datos[edad] D&iacute;as</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_ruptura'],1)."</td>	
						<td class='$nom_clase' align='center'>".number_format($datos['fprima_c'], 2,".",",")."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['carga_ruptura'], 2,".",",")." KG</td>
						<td class='$nom_clase' align='center'>".number_format($datos['kg_cm2'], 2,".",",")."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['porcentaje'], 2,".",",")." %</td>
						<td class='$nom_clase' align='center'>$datos[observaciones]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";						
				$id= $datos['prueba_calidad_id_prueba_calidad']; 
			}while($datos=mysql_fetch_array($rs));
			
			echo "
				</table><br><br><br>";		
				return $id; 
				
		}else{
			echo "<br><br><br><br><br><br><br><p align='center' class='msje_correcto'>NO hay Componentes Registrados en la Muestra Seleccionada</p>";
			return "";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}	
?>