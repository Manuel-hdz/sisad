<?php
	/**
	  * Nombre del Módulo: Producción                                               
	  * Nombre Programador: Miguel Angel Garay Castro                           
	  * Fecha: 17/07/2011                                      			
	  * Descripción: Este archivo contiene funciones para validar y almacenar la información relacionada con el formulario de AgregarMaterial en la BD
	  **/
	 
	//Esta funcion se encarga de guardar los cambios realizados al material seleccionado por el usuario
	function guardarCambios($txt_clave,$txt_nombre,$txt_nivelMinimo,$txt_nivelMaximo,$txt_puntoReorden,$txt_costoUnidad,$lineaArticulo,$unidadMedida,$cmb_proveedor,
	$txt_ubicacion,	$txa_comentarios,$txt_factor,$txt_unidadDespacho,$foto,$cmb_relevancia){		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");							
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_clave = strtoupper($txt_clave); $txt_nombre = strtoupper($txt_nombre); $lineaArticulo = strtoupper($lineaArticulo); $unidadMedida = strtoupper($unidadMedida);
		$cmb_proveedor = strtoupper($cmb_proveedor); $txt_ubicacion=strtoupper($txt_ubicacion); $txa_comentarios = strtoupper($txa_comentarios);
		$txt_unidadDespacho = strtoupper($txt_unidadDespacho);
		
		//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
		$txt_costoUnidad=str_replace(",","",$txt_costoUnidad);
			 		
		//Crear la consulta con los datos procporcionados
		$stm_sql = "UPDATE materiales SET 
		id_material='$txt_clave', nom_material='$txt_nombre', nivel_minimo=$txt_nivelMinimo, nivel_maximo=$txt_nivelMaximo, re_orden=$txt_puntoReorden, costo_unidad=$txt_costoUnidad,
		linea_articulo='$lineaArticulo', relevancia='$cmb_relevancia', proveedor='$cmb_proveedor', ubicacion='$txt_ubicacion', comentarios='$txa_comentarios'";
		if($foto!=""){
			//Cargar la imagen y el tipo para ser almacenados en la BD
			$foto_info = cargarImagen("foto");
			$stm_sql .= ", fotografia='$foto_info[foto]', mime='$foto_info[type]'";
		}										
		//Agregar el parametro meduante el cual se identificara el material que se esta modificando
		$stm_sql .= " WHERE id_material='".$_SESSION['id_material']."'";


		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);				
		//Confirmar que la operacion fue realizada con exito.
		if($rs){
			//Realizar una consulta para guardar los datos, si es que estos fueron Modificados por el usuario unidad_medida, factor_conv, unidad_despacho
			$stm_sql = "UPDATE unidad_medida SET materiales_id_material='$txt_clave', unidad_medida='$unidadMedida', factor_conv=$txt_factor, unidad_despacho='$txt_unidadDespacho' 
			WHERE materiales_id_material='" .$_SESSION['id_material']."'";	
			//Ejecutar las conslta para guardar los nuevos cambios
			$rs = mysql_query($stm_sql);
			if($rs){
				//Registrar la Operacion en la Bitácora de Movimientos
				registrarOperacion("bd_almacen","$txt_clave","ModMaterial",$_SESSION['usr_reg']);
				//Realizar la conexion a la BD de Gerencia
				registrarOperacion("bd_gerencia","$txt_clave","ModMaterial",$_SESSION['usr_reg']);
				//Verificar si el directorio temporal existe y en caso que si, eliminarlo.
				if(is_dir("documentos/temp")){ 					
					//Eliminar el Directorio temporal
					rmdir("documentos/temp");						
				}
				
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				
				//Eliminar la id_material de la SESION
				unset($_SESSION['id_material']);
			}
			else{
				//Redireccionar a una pagina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Redireccionar a una pagina de error en el caso de que no se haya guardado el Nuevo Material
			if(mysql_errno()=="1062")
				$error="La clave <em><u>$txt_clave</u></em> ya esta asignada a otro Material";
			else
				$error = mysql_error();				
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
	}//Cierre de la funcion guardarCambios() 
	
	
	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenado en la tabla de material en el campo fotografia que es de tipo longblob
	function cargarImagen($nomInputFile){
		// Mime types permitidos: todos los navegadores==>'image/gif', IE==>'image/x-png' y 'image/pjpeg', Mozilla Firefox==>'image/png' y 'image/jpeg'
		$mimetypes = array("image/gif", "image/x-png", "image/png", "image/pjpeg", "image/jpeg");
		//El nombre debe ser el mismo que el declarado en el formulario en el atributo 'name' de la etiqueta 'input' y el 'type' debe ser 'file' 
		//Ej. <input name="foto" type="file" class="caja_de_texto" id="foto" size="20" />
		
		$name = $_FILES[$nomInputFile]["name"];
		$type = $_FILES[$nomInputFile]["type"];
		$tmp_name = $_FILES[$nomInputFile]["tmp_name"];
		$size = $_FILES[$nomInputFile]["size"];			
		
		//Verificamos si el archivo es una imagen válida y que el tamaño de la misma no exceda los 10,000 Kb 10,240,000 Bytes
		if(in_array($type, $mimetypes) && $size<10240000){	
			//Cargar y Redimensionar la Imagen en el Directorio "../alm/documentos/temp"
			$archivoRedimensionado = cargarFotosEmp($nomInputFile);							
			//Extrae el contenido de la foto original
			$fp = fopen("documentos/temp"."/".$name, "rb");//Abrir el archivo temporal el modo lectura'r' binaria'b'
			$tfoto = fread($fp, filesize("documentos/temp"."/".$name));//Leer el archivo completo limitando la lectura al tamaño del archivo
			$tfoto = addslashes($tfoto);//Anteponer la \ a las comillas que puediera contener el archivo para evitar que sea interpretado como final de cadena
			fclose($fp);//Cerrar el puntero al archivo abierto				
		
			// Borra archivos temporales si es que existen
			@unlink("documentos/temp"."/".$name);			
			//Regresar la foto convertida para ser almacena en la BD
			return $foto_info = array("foto"=>$tfoto,"type"=>$type);
		}
		else{
			//Regresar la Info vacia de la foto en el caso de que no sea una imagen valida o exceda 1000Kb 
			return $foto_info = array("foto"=>"","type"=>"");
		}		
	}
	
	 //funcion que carga una imagen a un Directorio Temporal en la carpeta de "alm/documentos/temp"
	function cargarFotosEmp($nomInputFile){		
		//Esta variable Indica si la Imagen fue guardada en el directorio indicado
		$estado = 0;
		//Crear la variabe que sera la ruta de almacenamiento
		$Ruta="";
		//Variable que Alamcenara la Carpeta donde sera guardada la imagen redimensionada
		$carpeta="";
		
		//Abrir un Gestor de Directorios
		$dir = opendir($Ruta); 
		//verificar si el archivo ha sido almacenado en la carpeta temporal
		if(is_uploaded_file($_FILES[$nomInputFile]['tmp_name'])) { 
			//crear el nombre de la carpeta contenedora de la fotografia cargada
			$carpeta="documentos/temp";
			
			//veririfcar si el nombre de la carpeta exite de lo contrario crearla
			if (!file_exists($carpeta."/"))
				echo mkdir($carpeta."/", 0777);
						
			//Mover la fotografia de la carpteta temporal a la que le hemos indicado					
			move_uploaded_file($_FILES[$nomInputFile]['tmp_name'], $carpeta."/".$_FILES[$nomInputFile]['name']);
			//llamar la funcion que se encarga de reducir el peso de la fotografia 
			redimensionarFoto($carpeta."/".$_FILES[$nomInputFile]['name'],$_FILES[$nomInputFile]['name'],$carpeta."/",100,100);
		}
				
		return $carpeta;

	}//FIN 	function cargarImagen()
	
	 		
	//Esta funcion despliega los datos del material seleccionado por el usuario para realizarle cambios  
	function modificarMaterial($id_material){ 
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");	 	
		
		//Obtener los datos del material seleccionado por el usuario		
		$stm_sql = "SELECT * FROM materiales WHERE id_material='$id_material'";					
		
//$stm_sql ="SELECT  * FROM materiales WHERE id_material='$id_material' AND  grupo='PLANTA'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
							
		//Confirmar que la operacion fue realizada con exito y desplegar el formulario pre-llenado al usuario
		if($datos=mysql_fetch_array($rs)){
			//Obtener los datos del material seleccionado por el usuario		
			$stm_sql = "SELECT * FROM unidad_medida WHERE materiales_id_material='$id_material'";					
//$stm_sql = "SELECT * FROM unidad_medida JOIN materiales ON materiales_id_material = id_material WHERE grupo='PLANTA' AND materiales_id_material='$id_material'";
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
			$datos2=mysql_fetch_array($rs);
			
			//Cerrar la conexion con la BD		
			mysql_close($conn);
								
		?>
		<form onSubmit="return valFormModificarMaterial(this);" name="frm_modificarMaterial" method="post" enctype="multipart/form-data" 
		action="frm_modificarMaterial.php" onclick="activar();">
		<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>	  
	  	  	  <td><div align="right">*Clave</div></td>		
				<td>
					<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="10" 
					value="<?php echo $datos['id_material']; ?>"  readonly="readonly" /><?php 
					//Registrar la clave que se esta modificando en la SESION para realizar los cambios solicitados 
					$_SESSION['id_material'] = $datos['id_material'];?>				</td>     
			  	<td>
			  	  	<div align="right">*Nombre</div>		  	  </td>
	  	  	  	<td>
			  		<input name="txt_nombre" type="text" class="caja_de_texto" onkeypress="return permite(event,'num_car');" 
					value="<?php echo $datos['nom_material']; ?>" size="30" maxlength="60" />				</td>
			</tr>
			<tr>
				<td><div align="right">*Relevancia</div></td>
				<td>
					<select name="cmb_relevancia" id="cmb_relevancia" class="combo_box" onchange="definirNivelesMovModificar(this);">
                  		<option <?php if($datos['relevancia']=="") echo "selected='selected'";?> value="">Relevancia</option>
                  		<option <?php if($datos['relevancia']=="STOCK") echo "selected='selected'";?> value="STOCK">STOCK</option>
                  		<option <?php if($datos['relevancia']=="CONSIGNACION") echo "selected='selected'";?> value="CONSIGNACION">CONSIGNACION</option>
                  		<option <?php if($datos['relevancia']=="LENTO MOVIMIENTO") echo "selected='selected'";?> value="LENTO MOVIMIENTO">LENTO MOVIMIENTO</option>
                	</select>				</td>
				<td><div align="right">*Ubicaci&oacute;n</div></td>
				<td>
					<input name="txt_ubicacion" type="text" class="caja_de_texto" id="txt_ubicacion"
					onkeypress="return permite(event,'num_car');" value="<?php echo $datos['ubicacion']; ?>" size="30" maxlength="30" />				</td>
			</tr>
			<tr>
				<td><div align="right">*Cantidad</div></td>
				<td>
					<input name="txt_cantidad" type="text" id="txt_cantidad" size="15" maxlength="20" onkeypress="return permite(event,'num');" 
					value="<?php echo $datos['existencia']; ?>" disabled="disabled" />				</td>
				<td><div align="right">Comentarios</div></td>
				<td>
					<textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="2"  class="caja_de_texto" id="txa_comentarios" 
					onkeypress="return permite(event,'num_car');" /><?php echo $datos['comentarios']; ?></textarea>				</td>
			</tr>
			<tr>
				<td><div align="right">*Nivel Minimo</div></td>
				<td>
					<input name="txt_nivelMinimo" id="txt_nivelMinimo" type="text" class="caja_de_texto" onkeypress="return permite(event,'num');" 
					value="<?php echo $datos['nivel_minimo']; ?>" size="15" maxlength="20"/>				</td>
				<td><div align="right">*Factor de Conversi&oacute;n </div></td>
				<td>
					<input name="txt_factor" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" value="<?php echo $datos2['factor_conv']; ?>"/>				</td>
			</tr>
			<tr>
				<td><div align="right">*Nivel M&aacute;ximo </div> </td>
				<td>
					<input name="txt_nivelMaximo" id="txt_nivelMaximo" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" value="<?php echo $datos['nivel_maximo']; ?>" />				</td>
				<td><div align="right">*Unidad de Despacho </div></td>
				<td>
					<input name="txt_unidadDespacho" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'car');" value="<?php echo $datos2['unidad_despacho']; ?>"/>				</td>       	
			</tr>
			<tr>
				<td><div align="right">*Punto Reorden </div></td>
				<td>
					<input name="txt_puntoReorden" id="txt_puntoReorden" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" value="<?php echo $datos['re_orden']; ?>"/>				</td>
				<td><div align="right">*Costo Unidad </div></td>
				<td>
					$
					<input name="txt_costoUnidad" type="text" class="caja_de_texto" id="txt_costoUnidad" 
					onchange="formatCurrency(value,'txt_costoUnidad');" onkeypress="return permite(event,'num');" 
					value="<?php echo number_format($datos['costo_unidad'],2,".",","); ?>" size="15" maxlength="20"/>				</td>
			</tr>
			<tr>
				<td><div align="right">*Linea del Art&iacute;culo</div></td>
				<td><?php cargarComboEspecifico("cmb_lineaArticulo","linea_articulo","materiales","bd_almacen","PLANTA","grupo", "Categor&iacute;a", $datos['linea_articulo']); ?></td>
				<td><div align="right"><input type="checkbox" name="ckb_editarLinea" id="ckb_editarLinea" onclick="editarLinea();" />Reescribir L&iacute;nea Articulo</div></td>
			  	<td><input type="text" name="txt_lineaArticulo" id="txt_lineaArticulo" size="20" maxlength="30" disabled="disabled" value="" /></td>
			</tr>
			<tr>
				<td><div align="right">*Unidad de Medida</div></td>
				<td><?php  cargarCombo("cmb_unidadMedida","unidad_medida","unidad_medida","bd_almacen","Unidad",$datos2['unidad_medida']); ?>
				</td>
				<td><div align="right">
					<input type="checkbox" name="ckb_editarUnidad" id="ckb_editarUnidad" onclick="editarUnidad();" />Reescribir Unidad de Medida</div></td>
			  	<td><input type="text" name="txt_unidadMedida" id="txt_unidadMedida" size="20" maxlength="30" disabled="disabled" value="" /></td>
			</tr>
			<tr>
					<td><div align="right">Grupo</div></td>
			<td><input type="text" name="txt_grupo" id="txt_grupo" size="20" maxlength="20" readonly="readonly" value="PLANTA" style=" background-color:#666666; color:#FFFFFF"  />
		    	<input type="hidden" name="hdn_grupo" id="hdn_grupo" size="15" maxlength="20" />	
				<?php //cargarCombo("cmb_grupo","grupo","materiales","bd_almacen","Grupo",$datos['grupo']); ?>			</td>
			<td><div align="right">Fecha de Alta</div></td>
			<td><input name="fecha" type="text" disabled="disabled" class="caja_de_texto" value="<?php echo modFecha($datos['fecha_alta'],1);?>" size="10" /></td>			
			</tr>
			<tr>
			  	<td><div align="right">Fotograf&iacute;a</div></td>
			  	<td>
					<input name="foto" type="file" class="caja_de_texto" id="foto" size="20" title="Buscar Imagen" value="" 
				 	onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará en el Catálogo de Almacén');"/>				</td>
			  	<td>&nbsp;</td>
			  	<td>&nbsp;</td>
			</tr>
			<tr>
			  	<td><div align="right">*Proveedor</div></td>
			  	<td colspan="3"><?php cargarCombo("cmb_proveedor","razon_social","proveedores","bd_compras","Proveedor",$datos['proveedor']); ?></td>
		  	</tr>
			<tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
		  	<tr>			 
			  	<td colspan="4" align="center">
					<input type="hidden" name="hdn_tamImg" id="hdn_tamImg" value="" />
					<input type="hidden" name="hdn_imgValida" id="hdn_imgValida" value="si" />
			    	<input type="submit" name="sbt_modificar" id="sbt_modificar" class="botones" value="Modificar" disabled="disabled" 
					onmouseover="window.status='';return true" title="Modificar Material"  />
					&nbsp;&nbsp;
			      	<input type="reset" name="rst_limpiar" class="botones" value="Limpiar" onclick="deshabilitarElementos();" 
					onmouseover="window.status='';return true" title="Reestablecer Datos del Material Seleccionado"/>
		          	&nbsp;&nbsp;
		            <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Seleccionar otro Material para Modificar" 
					onClick="location.href='frm_modificarMaterial.php'" />				</td>
		  	</tr>
		</table>
		</form><?php 
		}//Cierre if	
	}//Cierre de funcion modificarMaterial($id_material)
	
	
?>