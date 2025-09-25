<?php
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas                            
	  * Fecha: 27/Octubre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para Mostrar la informaci�n seleccionada en el formulario de ReporteInventario
	  **/	 		  	  	
	 
	 function obtenerIdInv(){	
		//Definir las dos letras en la Id de la Orden de Compra
		$id_cadena = "INV";
	
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener los Reportes de Inventario del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de INV registradas en la BD
		$stm_sql = "SELECT COUNT(id_inventario) AS cant FROM inventario WHERE id_inventario LIKE 'INV$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		return $id_cadena;
	}//Fin de la Funcion obtenerIdInv() del Inventario
	 
	 //Mostrar el detalle de los materiales de acuerdo a los parametros seleccionados
	 function dibujarDetalle($cmb_categoria){
	 	//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");	
		//$fecha= modFecha($txt_fechaCierre,3);
		//Crear la sentencia para mostrar el catalogo de Materiales
		if($_POST["hdn_reporte"] == "Inventario"){
			if($cmb_categoria != "TODAS")
				$stm_sql = 
				"SELECT * 
				FROM materiales
				JOIN grupos_mat ON materiales.grupo = grupos_mat.id_grupo
				WHERE linea_articulo='".$cmb_categoria."'
				ORDER BY nom_material";
			else
				$stm_sql = "SELECT * FROM materiales JOIN grupos_mat ON materiales.grupo = grupos_mat.id_grupo ORDER BY nom_material";
			$msg = "Inventario del Almac&eacute;n de Familia: <strong><u>".$cmb_categoria."</u></strong>";
		}
		if($_POST["hdn_reporte"] == "MaxMin"){
			if($_POST["cmb_filtro"] == "minimo"){
				$stm_sql = "SELECT * FROM materiales JOIN grupos_mat ON materiales.grupo = grupos_mat.id_grupo WHERE existencia=nivel_minimo";
				$msg = "Inventario del Almac&eacute;n de Material al <strong><u>M&Iacute;NIMO</u></strong>";
			}
			else{ 
			if($_POST["cmb_filtro"] == "maximo")
				$stm_sql = "SELECT * FROM materiales JOIN grupos_mat ON materiales.grupo = grupos_mat.id_grupo WHERE existencia=nivel_maximo";
				$msg = "Inventario del Almac&eacute;n de Material al <strong><u>M&Aacute;XIMO</u></strong>";
			}
		}
		//$msg = "Inventario del Almac&eacute;n al <strong><u>".$txt_fechaCierre."</u></strong>";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>
				<tr>
				    <td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
  				</tr>      			
					<tr>
						<td width='80' class='nombres_columnas' align='center'>CLAVE</td>
        				<td width='120' class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</td>
				        <td width='120' class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
        				<td width='140' class='nombres_columnas' align='center'>LINEA DEL ARTICULO (CATEGORIA)</td>
						<th width='70' class='nombres_columnas' align='center'>GRUPO</th>
        				<td width='75' class='nombres_columnas' align='center'>EXISTENCIA</td>
        				<td width='90' class='nombres_columnas' align='center'>NIVEL MINIMO </td>
						<td width='90' class='nombres_columnas' align='center'>NIVEL MAXIMO </td>
						<td width='75' class='nombres_columnas' align='center'>COSTO UNITARIO</td>
						<td width='75' class='nombres_columnas' align='center'>COSTO&nbsp;TOTAL</td>
						<td width='75' class='nombres_columnas' align='center'>MONEDA</td>
        				<td width='120' class='nombres_columnas' align='center'>PROVEEDOR</td>
        				<td width='70' class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
        				<td width='120' class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Decalara arreglos que almacenen datos a agregarse a tabla de detalle
			$arrMaterial=array();
			$arrExistencia=array();
			$cantTotal=0;
			$pesosTotal=0; $dolaresTotal=0; $eurosTotal=0; $naTotal=0;
			do{	
				$unidad_medida=obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['id_material']);
				$costoPiezas=$datos["existencia"]*$datos["costo_unidad"];
				$cantTotal+=$costoPiezas;
				echo "	<tr>
						<td class='nombres_filas' align='center'>$datos[id_material]</td>
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase' align='center'>$unidad_medida</td>
						<td class='$nom_clase' align='center'>$datos[linea_articulo]</td>
						<td class='$nom_clase' align='center'>$datos[grupo]</td>
						<td class='$nom_clase' align='center'>$datos[existencia]</td>
						<td class='$nom_clase' align='center'>$datos[nivel_minimo]</td>
						<td class='$nom_clase' align='center'>$datos[nivel_maximo]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["costo_unidad"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($costoPiezas,2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[moneda]</td>
						<td class='$nom_clase' align='center'>$datos[proveedor]</td>
						<td class='$nom_clase' align='center'>$datos[ubicacion]</td>
						<td class='$nom_clase' align='center'>$datos[comentarios]</td>
					</tr>";
					
				//Guardar el contenido de ciertas columnas en los arreglos declarados previamente
				$arrMaterial[]=$datos['id_material'];
				$arrExistencia[]=$datos['existencia'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris"; 
				
				if($datos["moneda"] == "PESOS")
					$pesosTotal += $costoPiezas;
				
				else if($datos["moneda"] == "DOLARES")
					$dolaresTotal += $costoPiezas;
				
				else if($datos["moneda"] == "EUROS")
					$eurosTotal += $costoPiezas;
				
				else
					$naTotal += $costoPiezas;
					
			}while($datos=mysql_fetch_array($rs));
			if($pesosTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($pesosTotal,2,".",",")?></td>
				<td class="nombres_columnas">PESOS</td>
			</tr>
			<?php }
			if($dolaresTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($dolaresTotal,2,".",",")?></td>
				<td class="nombres_columnas">DOLARES</td>
			</tr>
			<?php }
			if($eurosTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">&euro;&nbsp;<?php echo number_format($eurosTotal,2,".",",")?></td>
				<td class="nombres_columnas">EUROS</td>
			</tr>
			<?php }
			if($naTotal > 0){ ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($naTotal,2,".",",")?></td>
				<td class="nombres_columnas">N/A</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="9">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($cantTotal,2,".",",")?></td>
				<td class="nombres_columnas">TOTAL</td>
			</tr>
	  		</table>
			</div>
			<div id="btns-regpdf" align="center">
			<table>
				<tr>
					<td align="right">
						<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Seleccionar Otro Rango de Fechas" 
						onclick="location.href='frm_reporteInventario.php'" />
						&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td align="left">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"  />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Inventario"  />
							<input name="hdn_tipoReporte" type="hidden" value="inventario"  />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"  />	
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar Datos del Inventario a Excel" 
							onmouseover="window.status='';return true"  />
						</form>
					</td>
				</tr>
			</table>
			</div>							
			<?php
			if($_POST["hdn_reporte"] == "Inventario"){
				$clave=obtenerIdInv();
				obtenerFecha($clave,$fecha,$arrMaterial,$arrExistencia);
			}
		}
		else{
			//La ventana se redirecciona a la ventana de advertencia indicando que la consulta no gener� resultados
			echo "<meta http-equiv='refresh' content='0;url=advertencia.php'>";
		}
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen",$clave,"inventario",$_SESSION['usr_reg']); implementada en la funcion guardarReporte($clave,$fecha,$arrMaterial,$arrExistencia)
	 }//Fin de la funcion dibujarDetalle($campo,$valBuscar)
	 
	 	

	 
	 function obtenerFecha($clave,$fecha,$arrMaterial,$arrExistencia){
	 //Obtener las fechas que estan registradas en el inventario
		$stm_sql_fecha = "SELECT fecha_inv FROM inventario";
		$rs_fecha=mysql_query($stm_sql_fecha);
		$fecha_inv=mysql_fetch_array($rs_fecha);
		do{
			if ($fecha!=$fecha_inv['fecha_inv'])
				$flag=1;
			else
				$flag=0;
		}while($fecha_inv=mysql_fetch_array($rs_fecha));
		
		//Para registrar los reportes de inventarios
		if ($flag==1){
			guardarReporte($clave,$fecha,$arrMaterial,$arrExistencia);
		}
	 }
	 
	 
	 function guardarReporte($clave,$fecha,$arrMaterial,$arrExistencia){
	 	//Crear la sentencia para insertar los datos
		$stm_sql_inv = "INSERT INTO inventario VALUES('".$clave."','".$fecha."')";
		//Ejecutar las sentencia previamente creadas
		$rs = mysql_query($stm_sql_inv);	
		$i=0;
		do{
			$stm_sql_detinv = "INSERT INTO detalle_inventario VALUES('".$arrMaterial[$i]."','".$clave."','".$arrExistencia[$i]."');";
			//Ejecutar la sentencia para insertar el detalle de inventario
			$rs = mysql_query($stm_sql_detinv);	
			$i++;
		}while ($i<count($arrMaterial));
		
		//Registrar la Operacion en la Bit�cora de Movimientos
		registrarOperacion("bd_almacen",$clave,"ReporteInventario",$_SESSION['usr_reg']);
	 }

?>
 