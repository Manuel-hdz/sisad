<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 05/Octubre/2010                                      			
	  * Descripción: Este archivo contiene funciones para Mostrar la información seleccionada en el formulario de ConsultarMaterial
	  **/	 		  	  	
	 
	 
	 //Mostrar el detalle de los materiales de acuerdo a los parametros seleccionados
	 function dibujarDetalle($campo,$valBuscar){
	 	//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");	 					
		
		//Identificar cuando el parametro de busqueda es la Unidad de Medida				
		switch($campo){
			case "todo": 
				//Crear la sentencia para mostrar el catalogo de Materiales
				$stm_sql = "SELECT *, equivalencias.proveedor AS equi_prov FROM materiales JOIN unidad_medida ON id_material=materiales_id_material 
							LEFT JOIN equivalencias ON id_material = equivalencias.materiales_id_material ORDER BY linea_articulo";
				$msg="Material existente en Almac&eacute;n al <u>".verFecha(4)."</u>";
			break;
			case "unidad_medida":			
				$stm_sql = "SELECT *, equivalencias.proveedor AS equi_prov  FROM materiales JOIN unidad_medida ON id_material=materiales_id_material 
							LEFT JOIN equivalencias ON id_material = equivalencias.materiales_id_material WHERE unidad_medida='$valBuscar' ORDER BY linea_articulo";
				$msg="Material existente en Almac&eacute;n acorde a la Unidad de Medida: <u>".$valBuscar."</u>";
			break;
			case "clave":			
				$stm_sql = "SELECT *, equivalencias.proveedor AS equi_prov  FROM materiales JOIN unidad_medida ON id_material=materiales_id_material 
							LEFT JOIN equivalencias ON id_material = equivalencias.materiales_id_material WHERE id_material='$valBuscar' ORDER BY id_material";
				$msg="Material existente en Almac&eacute;n acorde a la Clave: <u>".$valBuscar."</u>";
			break;
			default:
				if($campo=="fecha_alta")
					$msg = "Material dado de Alta en la fecha: <u>".modFecha($valBuscar,1)."</u>";
				else
					$msg="Material existente en Almac&eacute;n: <u>".$valBuscar."</u>";
				//Crear la sentencia para mostrar los Materiales seleccionados
				$stm_sql = "SELECT *, equivalencias.proveedor AS equi_prov  FROM materiales JOIN unidad_medida ON id_material=materiales_id_material 
							LEFT JOIN equivalencias ON id_material = equivalencias.materiales_id_material WHERE $campo='$valBuscar' ORDER BY linea_articulo";
			break;
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='1650'>      			
				<tr>
				    <td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
  				</tr>
					<tr>
						<td class='nombres_columnas' align='center' rowspan='2'>CLAVE</td>
        				<td class='nombres_columnas' align='center' rowspan='2'>NOMBRE (DESCRIPCION)</td>
						<td class='nombres_columnas' align='center' rowspan='2'>RELEVANCIA</td>
				        <td class='nombres_columnas' align='center' rowspan='2'>UNIDAD DE MEDIDA</td>
        				<td class='nombres_columnas' align='center' rowspan='2'>LINEA DEL ARTICULO (CATEGORIA)</td>
						<td class='nombres_columnas' align='center' rowspan='2'>GRUPO</td>
        				<td class='nombres_columnas' align='center' rowspan='2'>COSTO UNITARIO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>EXISTENCIA</td>
						<td class='nombres_columnas' align='center' rowspan='2'>COSTO TOTAL</td>
        				<td class='nombres_columnas' align='center' rowspan='2'>NIVEL MINIMO </td>
						<td class='nombres_columnas' align='center' rowspan='2'>NIVEL M&Aacute;XIMO </td>
						<td class='nombres_columnas' align='center' rowspan='2'>PUNTO REORDEN </td>
        				<td class='nombres_columnas' align='center' rowspan='2'>PROVEEDOR</td>
        				<td class='nombres_columnas' align='center' rowspan='2'>UBICACI&Oacute;N</td>
        				<td class='nombres_columnas' align='center' rowspan='2'>COMENTARIOS</td>
						<td class='nombres_columnas' align='center' rowspan='2'>FECHA DE ALTA</td>
						<td class='nombres_columnas' align='center' rowspan='2'>FACTOR DE CONVERSIÓN</td>
						<td class='nombres_columnas' align='center' rowspan='2'>UNIDAD DE DESPACHO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>APLICACION</td>
						<td class='nombres_columnas' align='center' rowspan='2'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center' colspan='4'>EQUIVALENCIAS</td>
						<td class='nombres_columnas' align='center' rowspan='2'>C&Oacute;DIGO DE BARRAS</td>	
      				</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE</td>
						<td class='nombres_columnas' align='center'>MATERIAL</td>
						<td class='nombres_columnas' align='center'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center'>CONSULTAR</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";
			
				$unidad_medida = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['id_material']);
				echo "	<tr>
						<td class='nombres_filas' align='center'>$datos[id_material]</td>
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase' align='left'>$datos[relevancia]</td>
						<td class='$nom_clase' align='center'>$unidad_medida</td>
						<td class='$nom_clase' align='center'>$datos[linea_articulo]</td>
						<td class='$nom_clase' align='center'>$datos[grupo]</td>
						<td class='$nom_clase' align='center'>$ ".number_format($datos['costo_unidad'],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[existencia]</td>
						<td class='$nom_clase' align='center'>$ ".number_format($datos['costo_unidad']*$datos['existencia'],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[nivel_minimo]</td>
						<td class='$nom_clase' align='center'>$datos[nivel_maximo]</td>
						<td class='$nom_clase' align='center'>$datos[re_orden]</td>
						<td class='$nom_clase' align='center'>$datos[proveedor]</td>
						<td class='$nom_clase' align='center'>$datos[ubicacion]</td>
						<td class='$nom_clase' align='center'>$datos[comentarios]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_alta'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[factor_conv]</td>
						<td class='$nom_clase' align='center'>$datos[unidad_despacho]</td>
						<td class='$nom_clase' align='center'>$datos[aplicacion]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" title='Ver Foto del Material <?php echo $datos['nom_material'];?>' 
							onClick="javascript:window.open('verImagen.php?id_material=<?php echo $datos['id_material']; ?>',
							'_blank','top=0, left=0, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?> />							
						</td>
				<?php
				echo "	<td class='$nom_clase' align='center'>$datos[clave_equivalente]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						<td class='$nom_clase' align='center'>$datos[equi_prov]</td>";
				?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verEquivalencias" class="botones" value="Equivalencias" 
							title='Ver Equivalencias del Material <?php echo $datos['nom_material'];?>' 
							onClick="javascript:window.open('verEquivalencias.php?id_material=<?php echo $datos['id_material']; ?>',
							'_blank','top=0, left=0, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php
							if($datos['codigo_barras']!=""){
							?>
								<input type="button" name="btn_verCodigoBarras" class="botones" value="C&oacute;digo Barras" 
								title='Ver C&oacute;digo de Barras del Material <?php echo $datos['nom_material'];?>' 
								onClick="javascript:window.open('vercodigoBarras.php?id_material=<?php echo $datos['codigo_barras']; ?>',
								'_blank','top=0, left=0, width=500, height=200, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>
							<?php }
							else{?>
								<input type="button" name="btn_verCodigoBarras" class="botones" value="C&oacute;digo Barras" 
								title='El Material <?php echo $datos['nom_material'];?> NO Tiene C&oacute;digo de Barras' disabled="disabled"/>
							<?php 
							}
							?>
						</td>	
					</tr>
					
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "	
			</table>"; ?>
			</div>
<div id="btns-regpdf" align="center">
			<table width="30%" cellpadding="5">
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="9" align="right">
						<form action="frm_consultarMaterial.php" method="post">
							<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Consulta de Material" onMouseOver="window.estatus='';return true"  />
						</form>	
					</td>
					<td colspan="8" align="left">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"  />
							<input name="hdn_nomReporte" type="hidden" value="Consulta Material"  />
							<input name="hdn_tipoReporte" type="hidden" value="consulta"  />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"  />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"  />
						</form>
					</td>
				</tr>
		</table>	
		</div>						
		<?php
		}
		else{
			echo "
			<table width='100%' cellpadding='5'>
				<tr align='center'>
					<td>
						<div class='msje_correcto'>No se encontraron resultados para la clave <br><em><u>$valBuscar</u></em></div>
					</td>
				</tr>
				<tr align='center'>
					<td>";
					?>
						<input type="button" name="btn_volver" value="Regresar" title="Volver al men&uacute; de búsqueda" onMouseOver="window.estatus='';return true" class="botones" onclick="location.href='frm_consultarMaterial.php'"/>
					<?php
			echo "	</td>
				</tr>
			</table>
			";
		} 
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	 }//Fin de la funcion dibujarDetalle($campo,$valBuscar)
	 
	 
?>
 