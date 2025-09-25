<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 25/Mayo/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Eliminar estimación en la BD
	**/
	
	//Funcion que se encarga de desplegar las estimaciones en el rango de fechas
	function mostrarEstimaciones(){

		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Si viene sbt_consultarObra la buqueda de los traspaleos proviene de seleccionar una obra
		if(isset($_POST["sbt_consultarObra"])){ 
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion FROM estimaciones JOIN obras ON id_obra=obras_id_obra 
			WHERE tipo_obra='$_POST[cmb_obra]' AND nombre_obra='$_POST[cmb_nombreObra]'";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones de <em><u>  $_POST[cmb_obra]    </u></em> de la Obra <em><u>	$_POST[cmb_nombreObra]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimaci&oacute;n de <em><u>  $_POST[cmb_obra]    
			</u></em> de la Obra <em><u>	$_POST[cmb_nombreObra]  </u></em>";	
		}
		
		//Si viene sbt_consultarMes la buqueda de los traspaleos proviene de seleccionar un mes y año
		if(isset($_POST["sbt_consultarMes"])){ 
		
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion FROM estimaciones JOIN obras ON id_obra=obras_id_obra 
			WHERE no_quincena LIKE'% $_POST[cmb_mes] $_POST[cmb_anios]'";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones del mes de <em><u>  $_POST[cmb_mes]    </u></em> del a&ntilde;o<em><u>	$_POST[cmb_anios]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimaci&oacute;n del mes de <em><u>  $_POST[cmb_mes]    
			</u></em> del a&ntilde;o<em><u>	$_POST[cmb_anios]  </u></em>";
		}

		//Si viene sbt_consultarQuincena la buqueda de los traspaleos proviene de seleccionar una quincena de una obra específica
		if(isset($_POST["sbt_consultarQuincena"])){ 
					
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion FROM estimaciones JOIN obras ON id_obra=obras_id_obra  
			WHERE tipo_obra='$_POST[cmb_tipoObra]' AND id_obra='$_POST[cmb_nomObra]' AND no_quincena='$_POST[cmb_numQuincena]'";		
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones de <em><u>  $_POST[cmb_tipoObra]    </u></em> de la Obra <em><u>	$_POST[cmb_nomObra]  </u></em> de la Quincena 
			<em><u>	$_POST[cmb_numQuincena]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimacione de <em><u>  $_POST[cmb_tipoObra]    
			</u></em> de la Obra <em><u> $_POST[cmb_nomObra]  </u></em> de la Quincena <em><u>	$_POST[cmb_numQuincena]  </u></em>";
		}
				
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='1500'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>ELIMINAR</td>
					<td class='nombres_columnas' align='center'>TIPO OBRA</td>
					<td class='nombres_columnas' align='center'>NOMBRE OBRA</td>
					<td class='nombres_columnas' align='center'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO/U M.N</td>
					<td class='nombres_columnas' align='center'>PRECIO/U USD</td>
					<td class='nombres_columnas' align='center'>TASA CAMBIO</td>
					<td class='nombres_columnas' align='center'>TOTAL MN</td>
					<td class='nombres_columnas' align='center'>TOTAL USD</td>
					<td class='nombres_columnas' align='center'>IMPORTE</td>
					<td class='nombres_columnas' align='center'>FECHA ELABORACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NO QUINCENA</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb' value= '$datos[id_estimacion]'></td>
						<td class='$nom_clase'>$datos[tipo_obra]</td>
						<td class='$nom_clase'>$datos[nombre_obra]</td>
						<td class='$nom_clase'>$datos[seccion]</td>
						<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['t_cambio'],4,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_mn'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_usd'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['importe'],2,".",",")."</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_estimacion'],1)."</td>
						<td class='$nom_clase'>$datos[no_quincena]</td>
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
	
	//Funcion que se encarga de eliminar la estimacion seleccionada
	function eliminarEstimacionSeleccionada(){
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_topografia");
		
		//Creamos la sentencia SQL para borrar de estimaciones
		$stm_sql="DELETE FROM estimaciones  WHERE id_estimacion = '$_POST[rdb]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$_POST['rdb'],"EliminarEstimacion",$_SESSION['usr_reg']);																			
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de Topografia
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion eliminarEstimacionSeleccionada
	
	function cargarAniosDisponible(){
	
		//conectar a bd_topografia
		$conn = conecta('bd_topografia');
		
		$rs_quincenas = mysql_query("SELECT DISTINCT no_quincena FROM estimaciones");
		$anios = array();
		
		while($datos_quincenas=mysql_fetch_array($rs_quincenas)){
			$quincena = $datos_quincenas['no_quincena'];
			$anios[] = substr($quincena, -4); 
		}
		
		$anioUnico = array_unique($anios);?>
		
		<select name="cmb_anios" id="cmb_anios" class="combo_box">  
            <option value="">Seleccione A&ntilde;o</option> <?php
            foreach($anioUnico as $ind => $anio){ ?>
                <option value="<?php echo $anio;?>"><?php echo $anio;?></option><?php
            }?>
		</select><?php
		
		//cerrar conexion
		mysql_close($conn);	
	} //Fin function cargarAniosDisponible()
?>