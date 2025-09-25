<?php
	/**
	  * Nombre del Módulo: Producción                                               
	  * Nombre Programador: Miguel Angel Garay Castro                           
	  * Fecha: 17/07/2011                                      			
	  * Descripción: Este archivo contiene funciones para eliminar la información relacionada con el formulario de ElimiarMaterial en la BD
	  **/
	  
	  
	//Borrar el Material seleccionado por el usuario
	function eliminarMaterial($hdn_clave){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");		
		
		//Crear la sentencia para ejecutar la eliminacion del material seleccionado por el usuario
		$stm_sql = "DELETE FROM materiales WHERE id_material='$hdn_clave' AND grupo='PLANTA'";					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
										
		//Confirmar que la operacion fue realizada con exito.
		if($rs){
			//Borrar el registro de la tabla de unidad_medida, cuando se haya eliminado correctamente el registro de material de la tabla de materiales			
			$stm_sql = "DELETE FROM unidad_medida WHERE materiales_id_material='$hdn_clave'";					
			$rs = mysql_query($stm_sql);
			if($rs){								
				//Registrar la Operacion en la Bitácora de Movimientos
				registrarOperacion("bd_almacen","$hdn_clave","EliminarMat",$_SESSION['usr_reg']);				
				//Realizar la conexion a la BD de Gerencia
				registrarOperacion("bd_gerencia","$hdn_clave","EliminarMat",$_SESSION['usr_reg']);
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			else{
				//Redireccionar a una pagina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Redireccionar a una pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
	}//Fin de la funcion eliminarMaterial($hdn_clave)
	
	 
	//Mostrar los resultados obtenidos de la busqueda de material para ser eliminado 
	function buscarMaterial($hdn_param, $cmb_datoBuscar){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		switch($hdn_param){
			case "unidad_medida":
				$stm_sql = "SELECT * FROM materiales JOIN unidad_medida ON id_material=materiales_id_material WHERE unidad_medida='$cmb_datoBuscar' AND grupo='PLANTA'";							
			break;
			default: 
				$stm_sql = "SELECT * FROM materiales WHERE $hdn_param='$cmb_datoBuscar' AND grupo='PLANTA'";				    				
			break;
		}
		
		//Mensaje a mostrar como titulo de la tabla
		$msg = "Material encontrado con el dato: <u>$cmb_datoBuscar</u>";			
		if($hdn_param == "fecha_alta")
			$msg = "Material encontrado con el dato: <u>".modFecha($cmb_datoBuscar,1)."</u>";	
			else{
				echo "
				<table width='100%' cellpadding='5'>
					<tr align='center'>
						<td>
							<div class='msje_correcto'>No se encontraron resultados para la clave <br><em><u>$cmb_datoBuscar</u></em></div>
						</td>
					</tr>
					<tr align='center'>
						<td>";
						?>
							<input type="button" name="btn_volver" value="Regresar" title="Volver al men&uacute; de búsqueda" onMouseOver="window.estatus='';return true"
							 class="botones" onclick="location.href='frm_eliminarMaterial.php'"/>
						<?php
				echo "	</td>
					</tr>
				</table>
				";
			}
		

		//Ejecutar la Consulta
		$rs = mysql_query($stm_sql);
		
		//Confirmar que la consulta de datos fue realizada con exito y desplegar los resultados obtenidos en una tabla.
		if($datos=mysql_fetch_array($rs)){
			echo "		
				<form onSubmit='return valFormEliminar2(this);' name='frm_eliminar2' method='post' action='frm_eliminarMaterial.php'>
				<table border='0' class='tabla_frm' align='center'>
					<tr>
						<td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas'><div align='center'>SELECCIONAR</div></td>
						<td class='nombres_columnas'>CLAVE</td>
        				<td class='nombres_columnas'>NOMBRE (DESCRIPCION)</td>				                				
        				<td class='nombres_columnas'>UNIDAD DE MEDIDA</td>  
						<td class='nombres_columnas'>LINEA DEL ARTICULO (CATEGORIA)</td>						  
						<td class='nombres_columnas'>GRUPO</td>
						<td class='nombres_columnas'>COSTO UNITARIO</td>        		
						<td class='nombres_columnas'>EXISTENCIA</td>								        				
						<td class='nombres_columnas'>PROVEEDOR</td>
						<td class='nombres_columnas'>UBICACION</td>
						<td class='nombres_columnas'>FECHA ALTA</td>												
      				</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Identificar que el ResultSet sea de un renglon
			$cad = "";
			if(mysql_num_rows($rs)==1)
				$cad = "checked='checked'";
				
			do{		
				$unidad_medida = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['id_material']);		
				echo "	<tr>
						<td class='nombres_filas'><div align='center'><input type='radio' name='rdb_clave' value='$datos[id_material]-$datos[existencia]' $cad/></div></td>
						<td class='$nom_clase'>$datos[id_material]</td>
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>						
						<td class='$nom_clase'>$unidad_medida</td>
						<td class='$nom_clase' align='left'>$datos[linea_articulo]</td>
						<td class='$nom_clase' align='left'>$datos[grupo]</td>
						<td class='$nom_clase'>$ ".number_format($datos['costo_unidad'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[existencia]</td>												
						<td class='$nom_clase' align='left'>$datos[proveedor]</td>
						<td class='$nom_clase'>$datos[ubicacion]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_alta'],1)."</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));?>
			</table>
			</div>
			<div id="btns-regpdf" align="center">
			<table cellpadding="5" cellspacing="5">
	  			<tr><td colspan="10">&nbsp;</td></tr>
				<tr>
					<td colspan="10" align="center">
						<input type="submit" value="Eliminar" class="botones" onMouseOver="window.estatus='';return true"  title="Eliminar Material Seleccionado"  />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Eliminar Material" 
						onClick="location.href='frm_eliminarMaterial.php'" />
					</td>
				</tr> 
			</table>
			</div>
			</form><?php

		}			
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion buscarMaterial($cmb_buscar, $txt_datoBuscar)
?>
