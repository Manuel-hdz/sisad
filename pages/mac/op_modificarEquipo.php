<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Marzo/2010                                      			
	  * Descripción: Este archivo contiene funciones para modificar informacion relacionada con el formulario de Modificar Equipo en la BD
	  **/		 
	  
	//Esta funcion se encarga de guardar los cambios realizados al Equipo seleccionado por el usuario
	function guardarCambios(){	
		//Recuperamos los valores y los modificamos para poder trabajar con ellos
		$clave=strtoupper($_POST["txt_clave"]);
		$fechaFabricacion=modFecha($_POST["txt_fechaFabricacionEquipo"],3);
		$fechaAlta=modFecha($_POST["txt_fecha"],3);
		$placa=strtoupper($_POST["txt_placa"]);
		$nombre=strtoupper($_POST["txt_nombre"]);
		$tenencia=strtoupper($_POST["txt_tenencia"]);
		$marca_modelo=strtoupper($_POST["txt_marcaModelo"]);
		$modelo=strtoupper($_POST["txt_modelo"]);
		$tarCirculacion=strtoupper($_POST["txt_tarjetaCirculacion"]);
		$serie=strtoupper($_POST["txt_serie"]);
		$poliza=strtoupper($_POST["txt_poliza"]);
		$serieOlla=strtoupper($_POST["txt_serieOlla"]);
		$asignado=strtoupper($_POST["txt_asignado"]);
		$motor=strtoupper($_POST["txt_motor"]);
		$proveedor=strtoupper($_POST["txt_proveedor"]);
		$area=strtoupper($_POST["cmb_area"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$metrica=($_POST["cmb_metrica"]);
		$claveAnterior=strtoupper($_POST["hdn_clave"]);
		//Control de costos
		$cmb_con_cos=$_POST["cmb_con_cos"];
		$cmb_cuenta=$_POST["cmb_cuenta"];
		//Verificamos si viene el combo Activo para preparar la Sentencia SQL
		if (isset($_POST["cmb_familia"]))
			$familia=$_POST["cmb_familia"];
		else
			$familia=strtoupper($_POST["txt_nuevaFamilia"]);
		
		//Crear la sentencia para modificar los Proveedores en la BD de comprasen la tabla de Proveedores
		$stm_sql = "UPDATE equipos SET id_equipo='$clave',nom_equipo='$nombre',fecha_alta='$fechaFabricacion',marca_modelo='$marca_modelo',
					modelo='$modelo',poliza='$poliza',num_serie='$serie',num_serie_olla='$serieOlla',placas='$placa',tenencia='$tenencia',
					tar_circulacion='$tarCirculacion',tipo_motor='$motor',area='$area',familia='$familia',fecha_fabrica='$fechaFabricacion',
					asignado='$asignado',proveedor='$proveedor',descripcion='$descripcion',metrica='$metrica',
					id_control_costos='$cmb_con_cos',id_cuentas='$cmb_cuenta'";
		
		//Cargar la imagen en el caso de que se haya cargado una y el tipo para ser almacenados en la BD
		if($_FILES["foto"]["error"]==0){
			$foto_info = cargarImagen("foto");
			$stm_sql.=",fotografia='$foto_info[foto]',mime='$foto_info[type]'";
		}
		else 
			$foto_info = array("foto"=>"", "type"=>"");
		//Complementar la sentencia SQL de Actualizacion
		$stm_sql.=" WHERE id_equipo='$claveAnterior'";
		
		//Conectar a la BD de Mantenimiento
		$conn=conecta("bd_mantenimiento");
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar si la insercion se llevo a cabo con exito
		if($rs){
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_mantenimiento","$claveAnterior","ModificarInformacionEquipo",$_SESSION['usr_reg']);
			if($foto_info["foto"]!=""){ 
			//Abrimos el directorio en el cual se encuentra almacenada la foro
				$fp = opendir("documentos/");		
				rmdir("documentos/temp");
				closedir($fp);//Cerrar el puntero al archivo abierto	
			}
			//Si la clave cambio, se debe actualizar en la tabla de expediente_equipos
			if ($clave!=$claveAnterior){
				//Modificar la relacion de beneficiarios del Empleado
				modificarDocumentos($clave,$claveAnterior);
				//Verificar si existe la carpeta que contenga archivos relacionados al equipo
				//De ser asi, asignarle el nuevo nombre correspondiente
				if (file_exists("documentos/".$claveAnterior."/") && !file_exists("documentos/".$clave."/"))
					rename("documentos/".$claveAnterior,"documentos/".$clave);
			}
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Redireccionar a una pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD
		//mysql_close($conn);
	}
	
	//Esta funcion se encarga de desplegar el formulario con los datos del Equipo a modificarse
	function mostrarEquipo($clave){
		//Conectar a la BD de Mantenimiento
		$conn=conecta("bd_mantenimiento");
		//Crear sentencia SQL
		$stm_sql="SELECT * FROM equipos WHERE id_equipo='$clave' AND estado='ACTIVO'";
		//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
		if (isset($_POST["hdn_area"]))
			//De estar definida el area, concatenamos a la sentencia SQL la condicion que restringe el area del vehiculo
			$stm_sql.=" AND area='$_POST[hdn_area]'";
		//Ejecutar la consulta
		$rs=mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Script que permite usar los combos recargables con los datos que llevan de inicio
		?>
			<script type="text/javascript" language="javascript">
				setTimeout("cargarCombo('<?php echo $datos['area'];?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','<?php echo $datos["familia"];?>');",500);
			</script>			
			<form name="frm_modificarEquipo" onsubmit="return valFormModificarEquipoDatos(this);" method="post" action="frm_modificarEquipo.php" enctype="multipart/form-data">
			<table width="923" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="137"><div align="right">*Clave del Equipo </div></td>
				<td width="298">
					<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);" value="<?php echo $datos["id_equipo"];?>"/>
					<input type="hidden" id="hdn_clave" name="hdn_clave" value="<?php echo $datos["id_equipo"];?>"/>
				</td>
				<td width="168"><div align="right">Fecha de Fabricaci&oacute;n del Equipo </div></td>
				<td width="253"><input type="text" name="txt_fechaFabricacionEquipo" id="txt_fechaFabricacionEquipo" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo modFecha($datos["fecha_fabrica"],1);?>"/></td>
			</tr>
			<tr>
				<td><div align="right">Fecha</div></td>
				<td><input type="text" name="txt_fecha" id="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo modFecha($datos["fecha_alta"],1);?>"/></td>
				<td><div align="right">No. Placas </div></td>
				<td><input type="text" name="txt_placa" id="txt_placa" size="10" maxlength="10" onkeypress="return permite(event,'num_car',1);" class="caja_de_texto" value="<?php echo $datos["placas"];?>"/></td>
			</tr>
			<tr>
				<td><div align="right">*Nombre del Equipo </div></td>
				<td><input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="50" maxlength="60" onkeypress="return permite(event,'num_car',3);" value="<?php echo $datos["nom_equipo"];?>"/></td>
				<td><div align="right">Tenencia</div></td>
				<td><input name="txt_tenencia" id="txt_tenencia" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);" value="<?php echo $datos["tenencia"];?>"/></td>
			</tr>
			<tr>
				<td><div align="right">*Marca/Modelo </div></td>
				<td>
					<input name="txt_marcaModelo" type="text" class="caja_de_texto" id="txt_marcaModelo" size="20" maxlength="60" 
					onkeypress="return permite(event,'num_car', 1);" value="<?php echo $datos["marca_modelo"];?>"/> 
					*Modelo  
					<input name="txt_modelo" type="text" class="caja_de_texto" id="txt_modelo" size="15" maxlength="30"
					onkeypress="return permite(event,'num_car',1);" value="<?php echo $datos["modelo"];?>"/>            </td>
				<td><div align="right">No. Tarjeta Circulaci&oacute;n </div></td>
				<td>
					<input name="txt_tarjetaCirculacion" id="txt_tarjetaCirculacion" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);" value="<?php echo $datos["tar_circulacion"];?>"/></td>
			</tr>
			<tr>
				<td><div align="right">*No. de Serie </div></td>
				<td><input name="txt_serie" id="txt_serie" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',0);" value="<?php echo $datos["num_serie"];?>"/></td>
				<td><div align="right">*No. P&oacute;liza  </div></td>
				<td>
					<input name="txt_poliza" id="txt_poliza" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car', 3);" value="<?php echo $datos["poliza"];?>"/></td>
			</tr>
			<tr>
				<td><div align="right">No. de Serie Equipo Adicional</div></td>
				<td><input name="txt_serieOlla" id="txt_serieOlla" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);" value="<?php echo $datos["num_serie_olla"];?>"/></td>
				<td><div align="right">*Asignado a </div></td>
				<td>
					<input name="txt_asignado" type="text" class="caja_de_texto" id="txt_asignado" tabindex="1" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'1');" size="40" maxlength="75" value="<?php echo $datos["asignado"];?>"/>
					<div id="res-spider1">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>       	
			<tr>
				<td><div align="right">*Tipo de Motor </div></td>
				<td><input name="txt_motor" id="txt_motor" type="text" class="caja_de_texto" size="15" maxlength="15" onkeypress="return permite(event,'num_car',3);" value="<?php echo $datos["tipo_motor"];?>"/></td>
				<td><div align="right">*Proveedor</div></td>
				<td>
					<input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="40" maxlength="80" onkeypress="return permite(event,'num_car',1);"
					onkeyup="lookupProv(this,'2');" value="<?php echo $datos["proveedor"];?>"/>
					<div id="res-spider2">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td align="right"><div align="right">*&Aacute;rea</div></td>
				<td>
					<select name="cmb_area" id="cmb_area" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
						<option selected="selected" value="">&Aacute;rea</option>
						<option value="CONCRETO" <?php if ($datos["area"]=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
						<option value="MINA" <?php if ($datos["area"]=="MINA") echo "selected='selected'"; ?>>MINA</option>
					</select>
				</td>
				<td align="right">Fotograf&iacute;a</td>
				<td><input type="file" id="foto" name="foto" class="caja_de_texto" size="20" title="Buscar Imagen" value=""
					onclick="<?php if($datos["fotografia"]!=""){ echo "alert('La Foto Actual se Perderá');";}?>alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará en el Catálogo de Equipos');" onchange="return validarImagen(this,'hdn_foto');" />
					<input type="hidden" id="hdn_foto" name="hdn_foto" value=""/>
				</td>
			</tr>
			<tr>
				<td align="right"><div align="right">*Familia</div></td>
				<td>
					<?php $result = cargarComboBicondicional("cmb_familia","familia","equipos","bd_mantenimiento", $datos['area'],"area","ACTIVO","estado","Familia","","");
					if($result==0){ ?>
					<select name="cmb_familia" id="cmb_familia" class="combo_box">
                      <option value="">Familia</option>
                    </select>
					<?php }?>
				</td>
				<td align="right"><input type="checkbox" name="ckb_nuevaFamilia" id="ckb_nuevaFamilia" onclick="agregarNuevaFamilia();"/>Agregar Nueva Familia </td>
				<td><input name="txt_nuevaFamilia" id="txt_nuevaFamilia" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30" onkeypress="return permite(event,'num_car', 3);" /></td>
			</tr>
			<tr>
				<td><div align="right">*Control de Costos</div></td>
				<td>
					<?php 
					$conn_rec = conecta("bd_recursos");		
					$stm_sql_rec = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
					$rs_rec = mysql_query($stm_sql_rec);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos_rec = mysql_fetch_array($rs_rec)){?>
						<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')">
						<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
						echo "<option value=''>Control de Costos</option>";
						do{
							if ($datos_rec['id_control_costos'] == $datos['id_control_costos']){
								echo "<option value='$datos_rec[id_control_costos]' selected='selected'>$datos_rec[descripcion]</option>";
							}else{
								echo "<option value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
							}
						}while($datos_rec = mysql_fetch_array($rs_rec));
						echo "<script type='text/javascript'>
								cargarCuentas(cmb_con_cos.value,'cmb_cuenta');
							</script>";
						?>
						<script type="text/javascript">
							setTimeout("document.getElementById('cmb_cuenta').value='<?php echo $datos['id_cuentas'] ?>'",500);
						</script>
						</select>
						<?php
					}
					else{
						echo "<label class='msje_correcto'> No actualmente control de costos</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn_rec);
					?>
				</td>
				<td width="15%"><div align="right">*Cuenta</div></td>
				<td width="40%">
					<span id="datosCuenta">
						<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box">
							<option value="">Cuentas</option>
						</select>
					</span>
				</td>
			</tr>
			<tr>
				<td><div align="right">Descripci&oacute;n</div></td>
				<td><textarea name="txa_descripcion" id="txa_observaciones" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
					onkeypress="return permite(event,'num_car', 0);"><?php echo $datos["descripcion"];?></textarea></td>
				<td><div align="right">Hor&oacute;metro/Od&oacute;metro</div></td>
				<td>
					<select name="cmb_metrica" id="cmb_metrica">
						<option selected="selected" value="">M&eacute;trica</option>
						<option <?php if ($datos["metrica"]=="HOROMETRO") echo "selected='selected'"; ?> value="HOROMETRO">HOR&Oacute;METRO</option>
						<option <?php if ($datos["metrica"]=="ODOMETRO") echo "selected='selected'"; ?> value="ODOMETRO">OD&Oacute;METRO</option>
					</select>
				</td>
			</tr>
			<tr>
			   <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="4">	
				<div align="center">
					<input type="hidden" name="hdn_validar" id="hdn_validar" value="si"/>
					<input name="btn_modificar" type="submit" class="botones"  value="Modificar" title="Modificar los Datos del Equipo" 
					onMouseOver="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_verDocumentos" class="botones_largos" value="Modificar Documentos" onMouseOver="window.estatus='';return true" 
                    title="Ver Documentos del Equipo <?php echo $clave;?>" 
					onClick="location.href='frm_modificarEquipoDoc.php?id_equipo=<?php echo $clave; ?>';"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_verRefacciones" class="botones_largos" value="Modificar Refacciones" 
                    onMouseOver="window.estatus='';return true" title="Ver Refacciones del Equipo <?php echo $clave;?>" 
					onClick="location.href='frm_modificarRefacciones.php?id_equipo=<?php echo $clave; ?>';"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Reestablecer" title="Reestablecer Formulario" onclick="cmb_familia.disabled=false;" onMouseOver="window.status='';return true"/> 
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Modificar Equipo" 
					onMouseOver="window.status='';return true" onclick="location.href='frm_modificarEquipo.php'" />
				</div>
				</td>
			</tr>
			</table>
  			</form>			
		<?php 
			//Regresar 1 en caso que encuentre datos
			return 1;
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			//Verificar que el area este definida, de no ser asi, el usuario es AuxMtto
			if (!isset($_POST["hdn_area"])){
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				echo  "<p class='msje_correcto' align='center'><br/><br/><br/><br/><br/><br/><br/><br/>No se Encontr&oacute; el Equipo: <em><u> $clave</u></em>";
				echo  "<br/>El Equipo Puede Que Ya Haya Sido Dado de Baja</p>";?>
					<table width="100%">	
						<tr>
							<td align ="center">							
								<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Modificar Equipo"
								onclick="location.href='frm_modificarEquipo.php'" />                              
							</td>
						</tr>
					</table>						
			<?php
			}
			else{
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				echo  "<p class='msje_correcto' align='center'><br/><br/><br/><br/><br/><br/><br/><br/>No se Encontr&oacute; el Equipo: <em><u> $clave</u></em> en el &Aacute;rea $_POST[hdn_area]</p>";?>
					<table width="100%">	
						<tr>
							<td align ="center">							
								<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Modificar Equipo"
								onclick="location.href='frm_modificarEquipo.php'" />                              
							</td>
						</tr>
					</table>
			<?php
			}
			//Regresar 0 en caso que no haya encontrado el Equipo
			return 0;
		}
		//Cerrar la conexión con la BD
		mysql_close($conn);
	}		
	
	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenado en la tabla de Equipo en el campo fotografia que es de tipo longblob
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
			//Cargar y Redimensionar la Imagen en el Directorio "../man/documentos/temp"
			$archivoRedimensionado = cargarFotos($nomInputFile);										
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
			//Regresar la Info vacia de la foto en el caso de que no sea una imagen valida o exceda 10Mb 
			return $foto_info = array("foto"=>"","type"=>"");
		}
	}
	
	//funcion que carga una imagen a un Directorio Temporal en la carpeta de "man/documentos/temp"
	function cargarFotos($nomInputFile){		
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
	
	//Funcion que muestra los documentos que tiene asignados un Equipo
	function mostrarDocumentos(){
		//Realizar la conexion a la BD
		$conn=conecta("bd_mantenimiento");
		//Obtener el nombre del Equipo
		$nombre = obtenerDato("bd_mantenimiento", "equipos", "nom_equipo", "id_equipo", $_GET["id_equipo"]);
		//Revisar si se han agregado documentos a la Base de Datos
		$stm_sql="SELECT * FROM expediente_equipos WHERE equipos_id_equipo='$_GET[id_equipo]'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "
				<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Documentaci&oacute;n Registrada de ".$_GET["id_equipo"]."</caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>DOCUMENTO</td>
					<td class='nombres_columnas' align='center'>ESTATUS</td>
					<td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ARCHIVO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	
					<tr>
						<td class='$nom_clase' align='center'><input type='radio' name='rdb_documentos' id='rdb_documentos$cont' value='$datos[nom_docto]'/></td>			
						<td class='$nom_clase' align='center'>$datos[nom_docto]</td>					
						<td class='$nom_clase' align='center'>$datos[estatus]</td>
						<td class='$nom_clase' align='center'>$datos[ubicacion]</td>
						<td class='$nom_clase' align='center'>$datos[nom_archivo]</td>
					</tr>";			
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			//Regresamos 1, indicando que si existen documentos disponibles
			return 1;
		}
		else{
			echo "<p align='center' class='msje_correcto'><b>No Existen Documentos del Equipo $nombre</b></p>";
			//REgresamos 0 en caso que no existan documentos disponibles
			return 0;
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	//Funcion que permite eliminar documentos seleccionados
	function eliminarDocumentos(){
		//Recuperamos el ID del Equipo
		$id_equipo=strtoupper($_POST["id_equipo"]);
		//Recuperamos el nombre del documento a eliminar
		$documento=$_POST["rdb_documentos"];
		//REalizamos la conexion con la BD de manteniminiento
		$conn=conecta("bd_mantenimiento");
		//Obtenemos el nombre del Archivo Fisico
		$archivo=obtenerDato("bd_mantenimiento","expediente_equipos","nom_archivo","nom_docto",$documento);
		//Crear sentencia SQL
		$stm_sql="DELETE FROM expediente_equipos WHERE equipos_id_equipo='$id_equipo' AND nom_docto='$documento'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se haya ejecutado la connsulta
		if ($rs){
			//Eliminamos el archivo del sistema que esta cargado y adicionado al documento de la BD
			@unlink("documentos/".$id_equipo."/".$archivo);
			//Redireccionamos a la misma pagina de donde llegamos aqui
			echo "<meta http-equiv='refresh' content='0;url=frm_modificarEquipoDoc.php?id_equipo=$id_equipo'>";
		}
		else
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//
	}//Fin de la funcion que elimina los documentos seleccionados
	
	//Funcion que Redirecciona a la pagina de frm_agregarDocumentacionEquipo con el fin de agregar documentos nuevos
	function agregarDocumentos(){
		//Recuperamos el ID del Equipo
		$id_equipo=strtoupper($_POST["id_equipo"]);
		echo "<form name='frm_temporal' method='post'>";
		echo "<input type='text' name='txt_clave' value='$id_equipo'/>";
		echo "<input type='text' name='txt_nombre' value='$id_equipo'/>";
		echo "</form>";
		?>
		<script>
			setTimeout("document.frm_temporal.action='frm_agregarDocumentacionEquipo.php?mod=si';document.frm_temporal.submit();",50);
		</script>
		<?php
	}//Fin de la funcion que agrega documentos nuevos
	
	//Funcion que modifica el ID de Equipos en la tabla de expediente_equipos, esto es por si el ID_Equipo cambio
	function modificarDocumentos($clave,$claveOriginal){
		//Conectar a la BD
		$conn=conecta("bd_mantenimiento");
		$stm_sql="UPDATE expediente_equipos SET equipos_id_equipo='$clave' WHERE equipos_id_equipo='$claveOriginal'";
		$rs=mysql_query($stm_sql);
		if (!$rs){
			//Redireccionar a una pagina de error
			$error=mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		mysql_close($conn);
	}
	
	//Funcion que se encarga de mostrar las refacciones agregadas a cada equipo
	function mostrarRefacciones(){
		//Realizar la conexion a la BD
		$conn=conecta("bd_mantenimiento");
		//Obtener el nombre del Equipo
		$nombre = obtenerDato("bd_mantenimiento", "equipos", "nom_equipo", "id_equipo", $_GET["id_equipo"]);
		$stm_sql="SELECT * FROM refacciones WHERE equipos_id_equipo='$_GET[id_equipo]'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "
				<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Refacciones Registradas de ".$_GET["id_equipo"]."</caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>NOMBRE</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	
					<tr>
						<td class='$nom_clase' align='center'><input type='radio' name='rdb_refacciones' id='rdb_refacciones$cont' value='$datos[nombre]'/></td>			
						<td class='$nom_clase' align='center'>$datos[nombre]</td>					
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
					</tr>";			
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			//Regresamos 1, indicando que si existen refacciones disponibles
			return 1;
		}
		else{
			echo "<p align='center' class='msje_correcto'><b>No Existen Refacciones del Equipo $nombre</b></p>";
			//REgresamos 0 en caso que no existan refacciones disponibles
			return 0;
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin 	function mostrarRefacciones(){

	//Funcion que permite eliminar la refaccion seleccionada
	function eliminarRefaccion(){
		//Recuperamos el ID del Equipo
		$id_equipo=strtoupper($_POST["id_equipo"]);
		//Recuperamos el nombre del documento a eliminar
		$nom_refa=$_POST["rdb_refacciones"];
		//REalizamos la conexion con la BD de manteniminiento
		$conn=conecta("bd_mantenimiento");
		//Crear sentencia SQL
		$stm_sql="DELETE FROM refacciones WHERE equipos_id_equipo='$id_equipo' AND nombre='$nom_refa'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se haya ejecutado la consulta
		if ($rs){
			//Redireccionamos a la misma pagina de donde llegamos aqui
			echo "<meta http-equiv='refresh' content='0;url=frm_modificarRefacciones.php?id_equipo=$id_equipo'>";
		}
		else{
			//Si los datos no se eliminaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}	
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//
	}//Fin de la funcion que elimina los documentos seleccionados

	//Esta funcion guarda las refacciones agregadas al equipo 
	function agregarRefacciones(){
		//Recuperamos el ID del Equipo
		$id_equipo=strtoupper($_POST["id_equipo"]);
		echo "<form name='frm_temporal' method='post'>";
		echo "<input type='text' name='txt_clave' value='$id_equipo'/>";
		echo "<input type='text' name='txt_nombre' value='$id_equipo'/>";
		echo "</form>";
		?>
		<script>
			setTimeout("document.frm_temporal.action='frm_agregarRefacciones.php?mod=si';document.frm_temporal.submit();",50);
		</script>
		<?php
	}//Fin de la function agregarRefacciones()

?> 