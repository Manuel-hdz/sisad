<?php

	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Nadia Madahi Lopez Hernandez                            
	  * Fecha: 08/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para eliminar los materiales equivalentes de la tabla equivalencias
	  **/

	
	function eliminarEquivalencia($clave_equivalente){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");		

		//Borrar el registro de la tabla de equivalencias, cuando se haya eliminado correctamente el registro de material de la tabla de equivalencias			
		$stm_sql = "DELETE FROM equivalencias WHERE clave_equivalente='$clave_equivalente'";					
		$rs = mysql_query($stm_sql);
		if($rs){
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_almacen",$clave_equivalente,"EliminarEquivalencia",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Redireccionar a una pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion
	}//Fin de la funcion eliminarMaterial($hdn_clave) registrarOperacion("bd_almacen",$clave_equivalente,"equiveliminar",$_SESSION['usr_reg']);
	
	
	
	function mostrarEquivalencias($id_material){
		//Conectarse a la BD de Almacen
		$conn = conecta("bd_almacen");

		$sql = "SELECT * FROM equivalencias WHERE materiales_id_material='$id_material'";
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $id_material);
		if($datos = mysql_fetch_array($rs)){		
			echo "	
			<form onSubmit='return valFormEliminarEquiv(this);' name='frm_eliminarEquiv' method='post' action='frm_eliminarEquivalencias.php'>			
			<table cellpadding='5' width='100%'> 
				<caption class='titulo_etiqueta'>Equivalencias Disponibles para el material <em><u>$nombre</u></em> con clave <em><u>$id_material</u></em></caption>			
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
        			<td class='nombres_columnas' align='center'>CLAVE MATERIAL EQUIVALENTE</td>
			        <td class='nombres_columnas' align='center'>MATERIAL EQUIVALENTE</td>
    	    		<td class='nombres_columnas' align='center'>PROVEEDOR</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{								
				echo "	
				<tr>
					<td class='nombres_filas'><div align='center'><input type='radio' name='rdb_clave' value='$datos[clave_equivalente]'/></div></td>
					<td class='$nom_clase' align='center'>$datos[clave_equivalente]</td>
					<td class='$nom_clase' align='center'>$datos[nombre]</td>
					<td class='$nom_clase' align='center'>$datos[proveedor]</td>
				</tr>";			
		
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "
				<tr>";
					?><td><input type="submit" value="Eliminar" class="botones" onMouseOver="window.estatus='';return true" title="Eliminar Equivalencia Seleccionada"/></td><?php
			echo "
				</tr>
			</table>	
			</form>";
		}		
		//Cerrar la conexion
		mysql_close($conn);
	}

?>