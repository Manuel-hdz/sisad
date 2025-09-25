<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 30/Septiembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para validar y almacenar la información relacionada con el formulario de AgregarMaterial en la BD
	  **/
	 
	//Esta funcion se encarga de guardar los cambios realizados al material seleccionado por el usuario
	function guardarCambios(){		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		
		//Obtener la Línea del Artículo proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_lineaArticulo']))
			$lineaArticulo = $_POST['cmb_lineaArticulo'];
		else
			$lineaArticulo = strtoupper($_POST['txt_lineaArticulo']);
		
		//Obtener la Unidad de Medida proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_unidadMedida']))
			$unidadMedida = $_POST['cmb_unidadMedida'];
		else
			$unidadMedida = strtoupper($_POST['txt_unidadMedida']);
			
		//Obtener la Unidad de Medida proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_grupo']))
			$grupo = $_POST['cmb_grupo'];
		else
			$grupo = strtoupper($_POST['txt_grupo']);
									
		//Recuperar los datos del POST y convertir los caracteres de los campos de texto en Mayúsculas
		$txt_clave = strtoupper($_POST['txt_clave']); 
		$txt_nombre = strtoupper($_POST['txt_nombre']); 
		//$cmb_cat = strtoupper($_POST['cmb_cat']); 
		$existencia = str_replace(",","",$_POST['txt_cantidad']);
		$txt_nivelMinimo = str_replace(",","",$_POST['txt_nivelMinimo']);
		$txt_nivelMaximo = str_replace(",","",$_POST['txt_nivelMaximo']);
		$txt_puntoReorden = str_replace(",","",$_POST['txt_puntoReorden']);
		$txt_costoUnidad = str_replace(",","",$_POST['txt_costoUnidad']);
		$moneda = $_POST['txt_moneda'];
		$cmb_relevancia = $_POST['cmb_relevancia'];
		$cmb_proveedor = strtoupper($_POST['cmb_proveedor']); 
		$txt_ubicacion = strtoupper($_POST['txt_ubicacion']); 
		$txa_comentarios = strtoupper($_POST['txa_comentarios']);
		$codigoBarras=$_POST["txt_codigoBarras"];
		$txa_aplicaciones = strtoupper($_POST['txa_aplicaciones']);
		$txt_unidadDespacho = strtoupper($_POST['txt_unidadDespacho']);		
		$txt_factor = $_POST['txt_factor'];
		$foto = $_FILES['foto'];		
		$cmb_relevancia = $_POST['cmb_relevancia'];

		//Crear la consulta con los datos procporcionados
		$stm_sql = "UPDATE materiales SET 
		id_material='$txt_clave', nom_material='$txt_nombre', existencia=$existencia, nivel_minimo=$txt_nivelMinimo, nivel_maximo=$txt_nivelMaximo, re_orden=$txt_puntoReorden, costo_unidad=$txt_costoUnidad,
		linea_articulo='$lineaArticulo', grupo='$grupo', relevancia='$cmb_relevancia', proveedor='$cmb_proveedor', ubicacion='$txt_ubicacion', comentarios='$txa_comentarios', codigo_barras='$codigoBarras',
		aplicacion='$txa_aplicaciones', moneda='$moneda'";
		if($foto["error"]==0){
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
				registrarOperacion("bd_almacen","$txt_clave","ModificarMaterial",$_SESSION['usr_reg']);
				
				actualizarTablasMaterial("bd_almacen","alertas","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","bitacora_movimientos","id_operacion",$txt_clave,"id_operacion",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","detalle_entradas","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","detalle_es","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","detalle_inventario","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","detalle_requisicion","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","detalle_salidas","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","devoluciones_es","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","equivalencias","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_almacen","equivalencias","clave_equivalente",$txt_clave,"clave_equivalente",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_kiosco","detalle_vale_kiosco","id_material",$txt_clave,"id_material",$_SESSION["id_material"]);
				actualizarTablasMaterial("bd_seguridad","vida_util_es","materiales_id_material",$txt_clave,"materiales_id_material",$_SESSION["id_material"]);
				
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
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
							
		//Confirmar que la operacion fue realizada con exito y desplegar el formulario pre-llenado al usuario
		if($datos=mysql_fetch_array($rs)){
			//Obtener los datos del material seleccionado por el usuario		
			$stm_sql = "SELECT * FROM unidad_medida WHERE materiales_id_material='$id_material'";					
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
			$datos2=mysql_fetch_array($rs);
			
			//Cerrar la conexion con la BD		
			mysql_close($conn);
								
		?>
		<form onSubmit="return valFormModificarMaterial(this);" name="frm_modificarMaterial" method="post" enctype="multipart/form-data" 
		action="frm_modificarMaterial.php">
		<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>	  
	  	  	  <td><div align="right">*Clave</div></td>		
				<td>
					<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="10" 
					value="<?php echo $datos['id_material']; ?>"  onkeypress="return permiteClavesMaterial(event,'num_car');" onchange="return validarCambioClave('bd_almacen','materiales','id_material',this,'<?php echo $datos["id_material"]?>','sbt_modificar','frm_modificarMaterial');"/><?php 
					//Registrar la clave que se esta modificando en la SESION para realizar los cambios solicitados 
					$_SESSION['id_material'] = $datos['id_material'];?>
					<input type="hidden" name="hdn_cambioValido" id="hdn_cambioValido" value="si" />
				</td>     
			  	<td>
			  	  	<div align="right">*C&oacute;digo de Barras </div>
		  	  </td>
	  	  	  	<td>
					<input name="txt_codigoBarras" id="txt_codigoBarras" type="text" class="caja_de_texto" size="20" maxlength="20" 
					onkeypress="return permite(event,'num_car');" value="<?php echo $datos['codigo_barras']; ?>" onchange="return validarCambioClave('bd_almacen','materiales','codigo_barras',this,'<?php echo $datos["codigo_barras"]?>','sbt_modificar');"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre</div></td>
				<td><input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" onkeypress="return permite(event,'num_car');" 
					value="<?php echo $datos['nom_material']; ?>" size="30" maxlength="60" /></td>
				<td><div align="right">*Relevancia</div></td>
				<td><select name="cmb_relevancia" id="cmb_relevancia" class="combo_box" onchange="definirNivelesMovModificar(this);">
                    <option <?php if($datos['relevancia']=="") echo "selected='selected'";?> value="">Relevancia</option>
                    <option <?php if($datos['relevancia']=="STOCK") echo "selected='selected'";?> value="STOCK">STOCK</option>
                    <option <?php if($datos['relevancia']=="CONSIGNACION") echo "selected='selected'";?> value="CONSIGNACION">CONSIGNACION</option>
                    <option <?php if($datos['relevancia']=="LENTO MOVIMIENTO") echo "selected='selected'";?> value="LENTO MOVIMIENTO">LENTO MOVIMIENTO</option>
                  </select></td>
			</tr>
			<tr>
				<td><div align="right">*Cantidad</div></td>
				<td>
					<?php
						//Guardamos el atributo deseado para ejecutarlo en la caja de texto
						$atributo='readonly="readonly"'; 
						//Verificamos el usuario registrado
						if($_SESSION['usr_reg']=="AdminAlmacen"){
							$atributo="";
						}
					?>
					<input name="txt_cantidad" type="text" id="txt_cantidad" size="15" maxlength="20" onkeypress="return permite(event,'num');" 
					value="<?php echo $datos['existencia']; ?>" <?php echo $atributo;?>/>
					<input type="hidden" id="hdn_administrador" name="hdn_administrador" value="<?php $_SESSION['usr_reg']=="AdminAlmacen";?>"/>
				</td>
				<td><div align="right">Comentarios</div></td>
				<td>
					<textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="2"  class="caja_de_texto" id="txa_comentarios" 
					onkeypress="return permite(event,'num_car');" /><?php echo $datos['comentarios']; ?></textarea>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nivel Minimo</div></td>
				<td>
					<input name="txt_nivelMinimo" id="txt_nivelMinimo" type="text" class="caja_de_texto" onkeypress="return permite(event,'num');" 
					value="<?php echo $datos['nivel_minimo']; ?>" size="15" maxlength="20"/>
				</td>
				<td><div align="right">*Factor de Conversi&oacute;n </div></td>
				<td>
					<input name="txt_factor" id="txt_factor" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" value="<?php echo $datos2['factor_conv']; ?>"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nivel M&aacute;ximo </div> </td>
				<td>
					<input name="txt_nivelMaximo" id="txt_nivelMaximo" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" value="<?php echo $datos['nivel_maximo']; ?>" />
				</td>
				<td><div align="right">*Unidad de Despacho </div></td>
				<td>
					<input name="txt_unidadDespacho" id="txt_unidadDespacho" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'car');" value="<?php echo $datos2['unidad_despacho']; ?>"/>
				</td>       	
			</tr>
			<tr>
				<td><div align="right">*Punto Reorden </div></td>
				<td>
					<input name="txt_puntoReorden" id="txt_puntoReorden" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" value="<?php echo $datos['re_orden']; ?>"/>
				</td>
				<td><div align="right">*Costo Unidad </div></td>
				<td>
					$
					<input name="txt_costoUnidad" type="text" class="caja_de_texto" id="txt_costoUnidad" 
					onchange="formatCurrency(value,'txt_costoUnidad');" onkeypress="return permite(event,'num');" 
					value="<?php echo number_format($datos['costo_unidad'],2,".",","); ?>" size="15" maxlength="20"/>				
				</td>
			</tr>
			<tr>
				<td>
					<!--
					<div align="right">*Categoria</div>
					-->
				</td>
				<td>
					<?php
					/*
					if(($fichero = fopen("..\..\documentos\categorias.csv","r")) !== FALSE){
						echo "
						<select name='cmb_cat' id='cmb_cat' required='required'>
							<option value=''>Seleccionar una categoria...</option>
						";
							$conn1 = conecta("bd_almacen");
							$rs_cat = mysql_query("SELECT * FROM categorias_mat WHERE habilitado='SI' ORDER BY descripcion");
							if($catMat = mysql_fetch_array($rs_cat)){
								do{
									if($datos["categoria"] == $catMat["id_categoria"]){
										echo "
										<option value='$catMat[id_categoria]' selected='selected' >".strtoupper($catMat['descripcion'])."</option>
										";
									} else {
										echo "
										<option value='$catMat[id_categoria]'>".strtoupper($catMat['descripcion'])."</option>
										";
									}
								}while($catMat = mysql_fetch_array($rs_cat));
							}
						echo "
						</select>
						";
					} else{
						echo "ERROR";
					}
					*/
					?>
				</td>
				<td><div align="right">*Moneda</div></td>
				<td>
					<select name="txt_moneda" id="txt_moneda" size="1" class="combo_box">
						<option value="">Moneda</option>
						<option <?php if($datos["moneda"] == "PESOS") echo "selected=selected" ?> value="PESOS">PESOS</option>
						<option <?php if($datos["moneda"] == "DOLARES") echo "selected=selected" ?> value="DOLARES">DOLARES</option>
						<option <?php if($datos["moneda"] == "EUROS") echo "selected=selected" ?> value="EUROS">EUROS</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Linea del Art&iacute;culo</div></td>
				<td><?php cargarComboExcluyente("cmb_lineaArticulo","linea_articulo","materiales","bd_almacen","PLANTA","grupo", "Categor&iacute;a", $datos['linea_articulo']);?></td>
				<td>
					<div align="right"><input type="checkbox" name="ckb_editarLinea" id="ckb_editarLinea" onclick="editarLinea();" />Reescribir L&iacute;nea Articulo</div>
				</td>
			  	<td><input type="text" name="txt_lineaArticulo" id="txt_lineaArticulo" size="20" maxlength="30" disabled="disabled" value="" /></td>
			</tr>
			<tr>
				<td><div align="right">*Unidad de Medida</div></td>
				<td><?php cargarCombo("cmb_unidadMedida","unidad_medida","unidad_medida","bd_almacen","Unidad",$datos2['unidad_medida']); ?></td>
				<td><div align="right">
					<input type="checkbox" name="ckb_editarUnidad" id="ckb_editarUnidad" onclick="editarUnidad();" />Reescribir Unidad de Medida</div>
				</td>
			  	<td><input type="text" name="txt_unidadMedida" id="txt_unidadMedida" size="20" maxlength="30" disabled="disabled" value="" /></td>
			</tr>
			<tr>
				<td><div align="right">*Grupo</div></td>
				<td>
					<?php 
					$conn = conecta("bd_almacen");
					$stm_grupos =  "SELECT T1.id_grupo, T1.grupo, T3.descripcion
									FROM grupos_mat AS T1
									JOIN rel_grupos_cuentas AS T2
									USING ( id_grupo ) 
									JOIN bd_recursos.cuentas AS T3
									USING ( id_cuentas ) 
									WHERE T1.habilitado =  'SI'
									ORDER BY T3.descripcion, T1.grupo";
					$rs_grupos = mysql_query($stm_grupos);
					if ($dato_grupos = mysql_fetch_array($rs_grupos)) {
						$nom_grupo = "";
						?>
						<select name="cmb_grupo" id="cmb_grupo" class="combo_box" required="required">
							<option value="">Selecciona un grupo</option>
							<?php 
							do {
								if ($dato_grupos["descripcion"] != $nom_grupo) {
									echo "
									<option value='' disabled='disabled'>_________________________________</option>
									<option value='' disabled='disabled'>$dato_grupos[descripcion]</option>
									<option value='' disabled='disabled'>_________________________________</option>
									";
								}
								if ($dato_grupos["id_grupo"] == $datos['grupo']) {
									echo "
									<option value='$dato_grupos[id_grupo]' selected='selected'>$dato_grupos[grupo]</option>
									";
								} else{
									echo "
									<option value='$dato_grupos[id_grupo]'>$dato_grupos[grupo]</option>
									";
								}
								$nom_grupo = $dato_grupos["descripcion"];
							} while ($dato_grupos = mysql_fetch_array($rs_grupos));
							?>
						</select>
						<?php
					}
					?>
				</td>
				<!--
				<td><?php cargarComboExcluyente("cmb_grupo","grupo","materiales","bd_almacen","PLANTA","grupo", "Grupo", $datos['grupo']);?></td>
				<td><div align="right"><input type="checkbox" name="ckb_editarGrupo" id="ckb_editarGrupo" onclick="editarGrupo();" />Reescribir Grupo</div></td>
			 	<td><input type="text" name="txt_grupo" id="txt_grupo" size="20" maxlength="30" disabled="disabled" value="" /></td>
			 	-->
			</tr>
			<tr>
			  	<td><div align="right">Fotograf&iacute;a</div></td>
			  	<td>
					<input name="foto" type="file" class="caja_de_texto" id="foto" size="20" title="Buscar Imagen" value="" 
				 	onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará en el Catálogo de Almacén');"/>			  
				</td>
			  	<td><div align="right">*Ubicaci&oacute;n</div></td>
				<td><input name="txt_ubicacion" type="text" class="caja_de_texto" id="txt_ubicacion"
					onkeypress="return permite(event,'num_car');" value="<?php echo $datos['ubicacion']; ?>" size="30" maxlength="30" /></td>
			</tr>
			<tr>
			  	<td><div align="right">*Proveedor</div></td>
			  	<td colspan="3"><?php cargarCombo("cmb_proveedor","razon_social","proveedores","bd_compras","Proveedor",$datos['proveedor']); ?></td>
		  	</tr>
			<tr>
				<td><div align="right">Aplicaciones</div></td>
				<td><textarea name="txa_aplicaciones" maxlength="100" cols="70" rows="2" class="caja_de_texto" id="txa_aplicaciones" style="resize:none;"><?php echo $datos['aplicacion']; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
		  	<tr>			 
			  	<td colspan="4" align="center">
					<input type="hidden" name="hdn_tamImg" id="hdn_tamImg" value="" />
					<input type="hidden" name="hdn_imgValida" id="hdn_imgValida" value="si" />
			    	<input type="submit" name="sbt_modificar" id="sbt_modificar" class="botones" value="Modificar" onmouseover="window.status='';return true" title="Modificar Material"  />
					&nbsp;&nbsp;
			      	<input type="reset" name="rst_limpiar" class="botones" value="Restablecer" title="Reestablecer Datos del Material Seleccionado"
					onclick="deshabilitarElementos();txt_clave.style.background='FFF';txt_codigoBarras.style.background='FFF';sbt_modificar.disabled=false" 
					onmouseover="window.status='';return true"/>
		          	&nbsp;&nbsp;
		            <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Seleccionar otro Material para Modificar" 
					onClick="location.href='frm_modificarMaterial.php'" />				
				</td>
		  	</tr>
		</table>
		</form><?php 
		}//Cierre if	
	}//Cierre de funcion modificarMaterial($id_material)
	
	function actualizarTablasMaterial($bd,$tabla,$campo,$cambio,$cond,$cond2){
		$conn = conecta($bd);
		$stm_sql = "UPDATE $tabla SET $campo = '$cambio' WHERE $cond = '$cond2'";
		$rs_upd = mysql_query($stm_sql);
		mysql_close($conn);
	}
?>