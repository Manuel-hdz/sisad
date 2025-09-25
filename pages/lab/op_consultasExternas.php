<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 25/Mayo/2011
	  * Descripción: Este archivo contiene funciones para Realizar consultas a Almacen
	**/
	
	//Funcion que muestra los materiales que existen en el Stock de Almacén
	function mostrarMateriales(){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");		
		//Escribimos la consulta a realizarse por Servicio o Material
		$stm_sql = "SELECT * FROM materiales JOIN unidad_medida ON id_material=materiales_id_material ORDER BY linea_articulo";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='1200' id='tabla-resultadosMateriales'>      			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>CLAVE</th>
        				<th class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</th>
				        <th class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</th>
        				<th class='nombres_columnas' align='center'>LINEA DEL ARTICULO (CATEGORIA)</th>
						<th class='nombres_columnas' align='center'>GRUPO</th>
						<th class='nombres_columnas' align='center'>EXISTENCIA</th>
        				<th class='nombres_columnas' align='center'>PROVEEDOR</th>
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>EQUIVALENCIAS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				$ctrl_imagen = "";
					if($datos['mime']=="")
						$ctrl_imagen = "disabled='disabled'";
				$unidad_medida = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['id_material']);
				echo "	<tr>
						<td class='nombres_filas' align='center'>$datos[id_material]</td>
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase' align='center'>$unidad_medida</td>
						<td class='$nom_clase' align='center'>$datos[linea_articulo]</td>
						<td class='$nom_clase' align='center'>$datos[grupo]</td>
						<td class='$nom_clase' align='center'>$datos[existencia]</td>
						<td class='$nom_clase' align='center'>$datos[proveedor]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true" title="Ver Foto del Material <?php echo $datos['nom_material'];?>" 
							onClick="javascript:window.open('verImagenMaterial.php?id_material=<?php echo $datos['id_material']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?> />							
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verEquivalencias" class="botones" value="Equivalencias" onMouseOver="window.estatus='';return true" 
							title="Ver Equivalencias del Material <?php echo $datos['nom_material'];?>" 
							onClick="javascript:window.open('verEquivalencias.php?id_material=<?php echo $datos['id_material']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>				
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";			
		}
		else{
			//Si no hay provedores registrados por Compras, se indica esto al usuario
			echo "<label class='msje_correcto'>No existen Materiales Registrados en el Sistema</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
	}//Fin de la Funcion de mostrarMateriales

?>