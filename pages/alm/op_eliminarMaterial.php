<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 30/Septiembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para eliminar la información relacionada con el formulario de ElimiarMaterial en la BD
	  **/
	  
	  
	//Borrar el Material seleccionado por el usuario
	function eliminarMaterial($hdn_clave){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");		
		
		//Crear la sentencia para ejecutar la eliminacion del material seleccionado por el usuario
		$stm_sql = "DELETE FROM materiales WHERE id_material='$hdn_clave'";					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
		//Si el Material seleccionado tiene que ver con el equipo de seguridad entonces eliminaremos tambien de la tabla de vida util en la BD de seguridad
		//Para mantener los datos lo mas actuales posible
		/*$claveSeg = substr($hdn_clave,0,3);//Verificamos los tres primeros caracteres para saber que el material a eliminar es equipo de seguridad (SEG)
		//Si la claveSeg contiene SEG entonces procedemos a eliminar de la tabla vida_util de la BD de seguridad
		if($claveSeg =="SEG"){
			//Realizar la conexion a la BD de Seguridad
			$conn = conecta("bd_seguridad");	
			//Sentencia para eliminar de la tabla de vida util
			$stm_sqlSeg = "DELETE FROM vida_util_es WHERE materiales_id_material = '$hdn_clave'";
			//Ejecutamos la sentencia previamente creada
			$rsSeg = mysql_query($stm_sqlSeg);
			//Cerramos BD de Seguridad
			mysql_close($conn);						
			//Realizar nuevamente la conexion a la BD de Almacen; ya que en este proceso se cambia la conexión a la BD de seguridad
			$conn = conecta("bd_almacen");	
		}*/								
		//Confirmar que la operacion fue realizada con exito.
		if($rs){
			//Borrar el registro de la tabla de unidad_medida, cuando se haya eliminado correctamente el registro de material de la tabla de materiales			
			$stm_sql = "DELETE FROM unidad_medida WHERE materiales_id_material='$hdn_clave'";					
			$rs = mysql_query($stm_sql);
			if($rs){				

				//Dar de baja el material de la tabla de alertas, en el caso de que se encuentre registrado
				actualizarAlertas($hdn_clave);				
				//Registrar la Operacion en la Bitácora de Movimientos
				registrarOperacion("bd_almacen","$hdn_clave","EliminarMaterial",$_SESSION['usr_reg']);				
								
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
		
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen","$hdn_clave","eliminar",$_SESSION['usr_reg']);
	}//Fin de la funcion eliminarMaterial($hdn_clave)
	
	
	function actualizarAlertas($clave){
		//Crear la sentencia SQL
		$sql_stm = "DELETE FROM alertas WHERE materiales_id_material = '$clave'";
		//Ejecutar la Sentencia SQL
		mysql_query($sql_stm);
	}
	 
	 
	//Mostrar los resultados obtenidos de la busqueda de material para ser eliminado 
	function buscarMaterial($hdn_param, $cmb_datoBuscar){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		switch($hdn_param){
			case "unidad_medida":
				$stm_sql = "SELECT * FROM materiales JOIN unidad_medida ON id_material=materiales_id_material WHERE unidad_medida='$cmb_datoBuscar' 
				AND grupo!='PLANTA'";							
			break;
			default: 
				$stm_sql = "SELECT * FROM materiales WHERE $hdn_param='$cmb_datoBuscar' AND grupo!='PLANTA'";				    				
			break;
		}
		
		//Mensaje a mostrar como titulo de la tabla
		$msg = "Material encontrado con el dato: <u>$cmb_datoBuscar</u>";			
		if($hdn_param == "fecha_alta")
			$msg = "Material encontrado con el dato: <u>".modFecha($cmb_datoBuscar,1)."</u>";
	
		
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
			<table width="323" height="102" cellpadding="5" cellspacing="5">
	  			<tr><td colspan="10">&nbsp;</td></tr>
				<tr>
				  <td colspan="10" align="center">
						<input type="submit" value="Eliminar" class="botones" onMouseOver="window.estatus='';return true"  title="Eliminar Material Seleccionado"  />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Eliminar Material" onClick="location.href='frm_eliminarMaterial.php'"/></td>
				</tr> 
			<?php
		}			
		else{
			echo "<label class='msje_correcto'>No existen Materiales Registrados con la Unidad de Medida</label>";?>
			<br /><br /><br /><br /><br />
			<tr>
				<td><input name="btn_regresar2" type="button" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Eliminar Material" onclick="location.href='frm_eliminarMaterial.php'"/></td>
			</tr>	
		<?php }?>
		</table></div>
		</form>
	<?php
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la funcion buscarMaterial($cmb_buscar, $txt_datoBuscar)
?>
