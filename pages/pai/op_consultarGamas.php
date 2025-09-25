<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 07/Marzo/2011
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con las Gamas de los Equipos
	**/
	
	
	/*Esta función muestra la gama seleccionada por el usuario*/
	function mostrarGamaSeleccionada(){
		//Conectarse con la Base de Datos
		$conn = conecta("bd_paileria");
		
		//Crear la Sentencia SQL para obtener los datos de la Gama Seleccionada
		$stm_sql_gama = "SELECT * FROM gama WHERE id_gama = '$_POST[cmb_claveGama]'";
		//Ejecutar la Sentencia
		$rs_gama = mysql_query($stm_sql_gama);
		//Procesar los resultados
		if($datos_gama=mysql_fetch_array($rs_gama)){
		
			//Estos datos se utilizaran para mostrar las Gamas relacionadas con la Actual?>
			<input type="hidden" name="hdn_area" value="<?php echo $datos_gama['area_aplicacion']; ?>" />
			<input type="hidden" name="hdn_familia" value="<?php echo $datos_gama['familia_aplicacion'];?>" /><?php
						
			echo "
			<table cellpadding='5' width='100%'>				
				<caption class='titulo_etiqueta'>Datos de la Gama Seleccionada</caption>
				<tr>
					<td class='nombres_columnas_gomar' width='10%'>ID</td>
					<td class='nombres_columnas_gomar' width='20%'>NOMBRE GAMA</td>
					<td class='nombres_columnas_gomar' width='40%'>DESCRIPCION</td>
					<td class='nombres_columnas_gomar' width='10%'>AREA</td>
					<td class='nombres_columnas_gomar' width='10%'>FAMILIA</td>
					<td class='nombres_columnas_gomar' width='10%'>CICLO SERVICIO</td>
				</tr>			
				<tr>
					<td class='nombres_filas_gomar'>$datos_gama[id_gama]</td>
					<td class='renglon_gris'>$datos_gama[nom_gama]</td>
					<td class='renglon_gris'>$datos_gama[descripcion]</td>
					<td class='renglon_gris'>$datos_gama[area_aplicacion]</td>
					<td class='renglon_gris'>$datos_gama[familia_aplicacion]</td>
					<td class='renglon_gris'>".number_format($datos_gama['ciclo_servicio'])."</td>
				</tr>			
			</table>
			<br><br>
			<table cellpadding='5' width='100%'>				
				<caption class='titulo_etiqueta'>Detalle de la Gama <u><em>$datos_gama[nom_gama]<u><em></caption>
				<tr>
					<td class='nombres_columnas_gomar' width='10%'>NO</td>
					<td class='nombres_columnas_gomar' width='90%'>SISTEMAS, APLICACIONES Y ACTIVIDADES</td>
				<tr>";
			
			//Crear la Sentencia SQL para obtener el Detalle de la Gama Seleccionada			
			$stm_sql = "SELECT id_actividad, sistema, aplicacion, descripcion FROM actividades JOIN gama_actividades ON actividades_id_actividad=id_actividad WHERE gama_id_gama = '$_POST[cmb_claveGama]'";
			//Ejecutar la Consulta
			$rs = mysql_query($stm_sql);
			//Desplegar datos			
			if($datos=mysql_fetch_array($rs)){
				//Manipular el color de los renglones
				$nom_clase = "renglon_gris";
				$cont = 1;
				
				//Cuando $band vale 0 indicara que no debe imprimir nada, solo las actividades
				//Cuando sea 1 indicara que hay que colocar el nombre del Sistema y la Aplicacion
				//Cuando sea 2 indicará que se deben imprimir la Aplicacion sin el Sistema
				$band = 1;
				//Obtener el nombre del Sistema y la Aplicacion
				$sistema = $datos['sistema'];
				$aplicacion = $datos['aplicacion'];
				do{
					//Verificar cambio de Aplicacion o Sistema
					if($sistema!=$datos['sistema']){
						$sistema = $datos['sistema'];
						$aplicacion = $datos['aplicacion'];
						$band = 1;
					}
					else if ($aplicacion!=$datos['aplicacion']){
						$aplicacion = $datos['aplicacion'];
						$band = 2;
					}
					
					
					//Cuando $band vale 1, mostrar el nombre del Sistema y de la Aplicacion										
					if($band==1){
						//Determinar el color del siguiente renglon a dibujar
						if($nom_clase=="renglon_gris") 
							$nom_clase = "renglon_blanco";
						else 
							$nom_clase = "renglon_gris";
						
						//Imprimir el renglon del Sistema	
						echo "
						<tr>					
							<td class='nombres_filas_gomar'>SISTEMA</td>
							<td class='$nom_clase' align='left'><strong>$sistema</strong></td>
						<tr>";
						
						//Determinar el color del siguiente renglon a dibujar
						if($nom_clase=="renglon_gris") 
							$nom_clase = "renglon_blanco";
						else 
							$nom_clase = "renglon_gris";
						
						//Imprimir el renglon de la Aplicacion
						echo "
						<tr>					
							<td class='nombres_filas_gomar'>APLICACION</td>
							<td class='$nom_clase' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em><u>$aplicacion</em></u></td>
						<tr>";	
						$band = 0;
					}					
					
					
					//Cuando $band vale 2, indica que solo debe imprimirse la aplicacion en turno
					if($band==2){
						//Determinar el color del siguiente renglon a dibujar
						if($nom_clase=="renglon_gris") 
							$nom_clase = "renglon_blanco";
						else 
							$nom_clase = "renglon_gris";
						
						//Imprimir el renglon de la Aplicacion	
						echo "
						<tr>					
							<td class='nombres_filas_gomar'>APLICACION</td>
							<td class='$nom_clase' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em><u>$aplicacion</em></u></td>
						<tr>";	
						$band = 0;
					}
					
					
					//Determinar el color del siguiente renglon a dibujar
					if($nom_clase=="renglon_gris") 
						$nom_clase = "renglon_blanco";
					else 
						$nom_clase = "renglon_gris";
					
										
					//Imprimir la Actividad
					echo "
					<tr>					
						<td class='nombres_filas_gomar'>$datos[id_actividad]</td>
						<td class='$nom_clase' align='left'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$datos[descripcion]</td>
					<tr>";										
					
																					
				}while($datos=mysql_fetch_array($rs));				
				echo "</table>";
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
				
		//Cerrar la conexion con la Base de Datos
		mysql_close($conn);
	}
	
	
	/*Esta funcion mustra las Gamas relacionadas con la Gama que fue consultada previamente*/
	function mostrarGamaRelacionadas($area,$familia){
		//Conectarse con la Base de Datos
		$conn = conecta("bd_paileria");
		
		//Crear la Sentencia SQL para obtener las Gamas relacionadas
		$stm_sql = "SELECT * FROM gama WHERE area_aplicacion='$area' AND familia_aplicacion='$familia'";
		//Ejecutar la Sentecia
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados
		if($datos_gama=mysql_fetch_array($rs)){
			echo "
				<table cellpadding='5' width='100%'>				
					<caption class='titulo_etiqueta'>Datos de la Gama Seleccionada</caption>
					<tr>						
						<td class='nombres_columnas_gomar' width='10%'>VER DETALLE</td>
						<td class='nombres_columnas_gomar' width='10%'>ID</td>
						<td class='nombres_columnas_gomar' width='20%'>NOMBRE GAMA</td>
						<td class='nombres_columnas_gomar' width='40%'>DESCRIPCION</td>
						<td class='nombres_columnas_gomar' width='10%'>AREA</td>
						<td class='nombres_columnas_gomar' width='10%'>FAMILIA</td>
					</tr>";					
			do{			
				echo"
					<tr>
						<td class='nombres_filas_gomar'><input type='checkbox' name='cmb_claveGama' value='$datos_gama[id_gama]' onclick='document.frm_gamasRelacionadas.submit();' /></td>						
						<td class='renglon_gris'>$datos_gama[id_gama]</td>
						<td class='renglon_gris'>$datos_gama[nom_gama]</td>
						<td class='renglon_gris'>$datos_gama[descripcion]</td>
						<td class='renglon_gris'>$datos_gama[area_aplicacion]</td>
						<td class='renglon_gris'>$datos_gama[familia_aplicacion]</td>
					</tr>";
			}while($datos_gama=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			echo "<label class='msje_correcto'>No Existen Gamas Relacionadas</label>";
		}
		//Cerrar la conexion con la Base de Datos
		mysql_close($conn);
	}
			
?>