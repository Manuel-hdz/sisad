<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 19/Enero/2012                                      		
	  * Descripción: Este archivo permite descargar los documentos en el servidor asi como en la Base de datos
	  **/
	 	
	
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarDocumentos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT *	FROM repositorio_documentos ORDER BY id_documento";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosDocumentos'> 
				<thead>
				";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>CLAVE</th>
						<th class='nombres_columnas' align='center'>NOMBRE</th>
						<th class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>CARPETA</th>
						<th class='nombres_columnas' align='center'>FECHA REGISTRO</th>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>DESCARGAR</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{	
				$clasificacion=obtenerDato("bd_seguridad", "catalogo_clasificacion", "clasificacion", "id_clasificacion", $datos['catalogo_clasificacion_id_clasificacion']);
				$carpeta=obtenerDato("bd_seguridad", "catalogo_carpetas", "carpeta", "id_carpeta", $datos['catalogo_carpetas_id_carpeta']);	
				if($clasificacion==""){
					$clasificacion="DOCUMENTO UBICADO EN CARPETA PRINCIPAL";
				}
				if($carpeta==""){
					$carpeta="N/A";
				}						
				echo "	<tr>
							<td class='$nom_clase' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_documento]'/>
							</td>				
							<td class='$nom_clase' align='center'>$datos[id_documento]</td>					
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='center'>$clasificacion</td>
							<td class='$nom_clase' align='center'>$carpeta</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>					
							<td class='$nom_clase' align='left'>$datos[descripcion]</td>";
							?>
							<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
								title="Descargar Documento<?php echo $datos['nombre'];?>" 
								onClick="javascript:window.open('marco_descargaSeg.php?id_documento=<?php echo $datos['id_documento'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $datos['ruta'];?>&nombre=<?php echo $datos['nombre'];?>&tipo=<?php echo $datos['tipo_archivo'];?>',
								'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
					<?php
						echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Documentos Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Funcion que se encarga de modifica  el Registro seleccionado
	function modificarRegistroSeleccionado(){
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Conectar a la BD de Seguridad
		$conn = conecta("bd_seguridad");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM repositorio_documentos  WHERE id_documento = '$_POST[rdb_id]'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);?>
		<fieldset class="borde_seccion" id="tabla-modificarDocumento" name="tabla-modificarDocumento">
		<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Documento </legend>	
		<br>
	
		<form onsubmit="return valFormModDocumentos(this);" name="frm_modificarDocumento"  id="frm_modificarDocumento" method="post" 
		action="frm_modificarDocumentos.php"  enctype="multipart/form-data">
        <table width="900" height="271"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td>*Id  Documento </td>
          		<td width="116">
					<input name="txt_idDocumento" id="txt_idDocumento" type="text" class="caja_de_texto" size="10" maxlength="10" 
					value="<?php echo $datos['id_documento'];?>" onchange="validarCaracteres(this);" onkeypress="return permite(event,'num_car', 1);"/>				</td>
          		<td width="52"><div align="right">Fecha</div></td>
          		<td width="102">
					<input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/>				</td>
          		<td width="200"><div align="right">*Nombre </div></td>
          		<td width="236">
					<input name="txt_nomDoc" id="txt_nomDoc" type="text" class="caja_de_texto" size="40" maxlength="80" 
					onkeypress="return permite(event,'num_car',7);" value="<?php echo $datos['nombre'];?>"/>				</td>
        	</tr>
			<tr>
          	<td width="97"><div align="right">Clasificaci&oacute;n</div></td>
          	<td colspan="3"><?php 
				$clasificacionOri=obtenerDato("bd_seguridad", "catalogo_clasificacion", "clasificacion", "id_clasificacion", $datos['catalogo_clasificacion_id_clasificacion']);
				$cmb_clasificacion=$clasificacionOri;
				$conn = conecta("bd_seguridad");
				$result=mysql_query("SELECT DISTINCT clasificacion FROM catalogo_clasificacion ORDER BY clasificacion");
				if($clasificacion=mysql_fetch_array($result)){?>
				 <select name="cmb_clasificacion" id="cmb_clasificacion" size="1" class="combo_box" onchange="desactivarCombo();">
					<option value="" >Clasificacion</option>
					<?php 
					  do{
							if ($clasificacion['clasificacion'] == $cmb_clasificacion){
								echo "<option value='$clasificacion[clasificacion]' selected='selected'>$clasificacion[clasificacion]</option>";
							}
							else{
								echo "<option value='$clasificacion[clasificacion]'>$clasificacion[clasificacion]</option>";
							}
						}while($clasificacion=mysql_fetch_array($result)); 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
				</select>
				  <?php }
					else{
						echo "<label class='msje_correcto'> No hay Clasificaciones Registradas</label>
						<input type='hidden' name='cmb_clasificacion' id='cmb_clasificacion'/>";?>
					<?php }?>	 	 	</td>
          	<td><div align="right">
              	<input type="checkbox" name="ckb_clasificacion" id="ckb_clasificacion" 
				onclick="agregarNuevaClasificacionMod(this, 'txt_clasificacion', 'cmb_clasificacion');  desactivarCombo();" 
				title="Seleccione el Nombre de una Clasificacion que no Exista" />
           	 	Agregar Clasificaci&oacute;n </div>			</td>
          	<td><input name="txt_clasificacion" id="txt_clasificacion" type="text" class="caja_de_texto" size="30" readonly="readonly"/></td>
        </tr>
        <tr>
        	<td width="97"><div align="right">Carpeta</div></td>
          	<td colspan="3"><?php 
				$carpetaOri=obtenerDato("bd_seguridad", "catalogo_carpetas", "carpeta", "id_carpeta", $datos['catalogo_carpetas_id_carpeta']); 
				$cmb_carpeta=$carpetaOri;
				$conn = conecta("bd_seguridad");
				$result=mysql_query("SELECT DISTINCT carpeta FROM catalogo_carpetas ORDER BY carpeta");
				if($carpeta=mysql_fetch_array($result)){?>
          	  <select name="cmb_carpeta" id="cmb_carpeta" size="1" class="combo_box" onchange="desactivarCombo();">
                <option value="">Carpeta</option>
                <?php 
					  do{
							if ($carpeta['carpeta'] == $cmb_carpeta){
								echo "<option value='$carpeta[carpeta]' selected='selected'>$carpeta[carpeta]</option>";
							}
							else{
								echo "<option value='$carpeta[carpeta]'>$carpeta[carpeta]</option>";
							}
						}while($carpeta=mysql_fetch_array($result)); 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
              </select>
          	  <?php }
				else{
					echo "<label class='msje_correcto'> No hay Carpetas Registradas</label>
					<input type='hidden' name='cmb_carpeta' id='cmb_carpeta'/>";
				 ?>
              <?php }?>		  </td>
         	<td>
			  <div align="right">
					<input type="checkbox" name="ckb_carpeta" id="ckb_carpeta" 
					onclick="agregarNuevaCarpetaMod(this, 'txt_carpeta', 'cmb_carpeta'); desactivarCombo();" 
					title="Seleccione el Nombre de una Carpeta que no Exista" />
					Agregar Carpeta			  </div>			</td>
          	<td>
				<input name="txt_carpeta" id="txt_carpeta" type="text" class="caja_de_texto" size="30" readonly="readonly"/>			</td>
        </tr>
        <tr>
          	<td><div align="right">*Documento</div></td>
          	<td colspan="3"><input type="file" name="file_documento" id="file_documento" size="36" value=""/></td>
          	<td><div align="right">Descripci&oacute;n</div></td>
          	<td>
				<textarea name="txa_descripcion" id="txa_descripcion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"><?php echo $datos['descripcion'];?></textarea>			</td>
        </tr>
        <tr><td colspan="5"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
        <tr>
        	<td colspan="6">
				<div align="center">
					<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Cambios en Documentos"
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Valores" onmouseover="window.status='';return true"
					onclick="habilitarCombosDoc();"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a Seleccionar Otro Registro" 
					onmouseover="window.status='';return true"  onclick="confirmarSalida('frm_modificarDocumentos.php')" />
					<input type="hidden" name="rdb_id" id="rdb_id" value="<?php echo $_POST['rdb_id'];?>"/>
					<input type="hidden" name="hdn_ruta" id="hdn_ruta" value="<?php echo $datos['ruta'];?>"/>
					<input type="hidden" name="hdn_id" id="hdn_id" value="<?php echo $datos['id_documento'];?>"/>
					<input type="hidden" name="hdn_tipo" id="hdn_tipo" value="<?php echo $datos['tipo_archivo'];?>"/>
					<input type="hidden" name="hdn_comboCarpeta" id="hdn_comboCarpeta" value="<?php echo $carpetaOri;?>"/>
					<input type="hidden" name="hdn_comboClasificacion" id="hdn_comboClasificacion" value="<?php echo $clasificacionOri;?>"/>
				</div>			</td>
        </tr>
      </table>
	</form>
	</fieldset>
	<div id="calendario">
		<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_modificarDocumento.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />								
	</div>
	<?php
	}						
	
		
	//Verificamos que este definido el botón de guardar en el post
	 if (isset($_POST["sbt_guardar"])){
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			eliminarDocumento();
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			$archivo=$_POST['txt_idDocumento'].'.'.$archivoSec[1];
			$resSubirArch = subirArchivo($archivo);
			guardarModificacionRegistroArchivo($archivo);
		}
		else if(isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]==""){
			guardarModificacionRegistro();
			if($_POST["hdn_comboClasificacion"]=!$_POST["txt_clasificacion"]||$_POST["hdn_comboClasificacion"]=!$_POST["cmb_clasificacion"]){
				subirArchivoMod();
			}
		}
	 }
	 
	 //Esta funcion permite registrar los Archivos en la BD
	function guardarModificacionRegistroArchivo($archivo){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado; para recuperar el nombre en la variable $archivo
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			$archivo=$_POST['txt_idDocumento'].'.'.$archivoSec[1];
			$tipo=$archivoSec[1];
		}
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD si estos permanecen nulos, no se selecciono norma ni, clasificacion
		$id_carpeta="";
		$id_clasificacion="";
		
		
		//Verificamos que el combo norma este definido y que no venga vacio. Para generar la ruta donde se guardara dicho archivo
		if(isset($_POST["cmb_clasificacion"])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion = $_POST["cmb_clasificacion"];
			$ruta='documentos/SI/'.$clasificacion;
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_clasificacion=obtenerDato("bd_seguridad", "catalogo_clasificacion", "id_clasificacion", "clasificacion", $_POST["cmb_clasificacion"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_norma
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$validarCla=obtenerDato("bd_seguridad", "catalogo_clasificacion", "clasificacion", "clasificacion", strtoupper($_POST["txt_clasificacion"]));
			if($validarCla==""){
				//Obtenemos el id de la norma para realizar la insercion en la BD
				$id_clasificacion = obtenerIdClasificacion();
				$stm_sql2 = "INSERT INTO catalogo_clasificacion(id_clasificacion,clasificacion) VALUES('$id_clasificacion','$_POST[txt_clasificacion]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
			}
			$clasificacion=$_POST['txt_clasificacion'];
			$ruta='documentos/SI/'.$clasificacion;
		}
		//Verificamos que se encuentre definido el combo clasificacion de ser asi obtenemos el dato correspondiente
		if(isset($_POST["cmb_carpeta"])&&$_POST["cmb_carpeta"]!=""){
			$carpeta = $_POST["cmb_carpeta"];
			$ruta='documentos/SI/'.$clasificacion.'/'.$carpeta;
			$id_carpeta=obtenerDato("bd_seguridad", "catalogo_carpetas", "id_carpeta", "carpeta", $_POST["cmb_carpeta"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_clasificacion
		if(isset($_POST["txt_carpeta"])&&$_POST["txt_carpeta"]!=""){
			$validarCar=obtenerDato("bd_seguridad", "catalogo_carpetas", "carpeta", "carpeta", strtoupper($_POST["txt_carpeta"]));
			if($validarCar==""){
				$id_carpeta = obtenerIdCarpeta();
				$stm_sql1 = "INSERT INTO catalogo_carpetas(id_carpeta,carpeta) VALUES('$id_carpeta','$_POST[txt_carpeta]')";
				//Ejecutar la sentencia previamente creada 
				$rs1 = mysql_query($stm_sql1);
			}
			$carpeta=$_POST['txt_carpeta'];
			$ruta='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		
		//Si los campos y los combos permanecen vacios quiere decir que el archivo se guardara en la raiz
		if($_POST["txt_carpeta"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_carpeta"]==""&&$_POST["cmb_clasificacion"]==""){
			$ruta='documentos/SI';
		}
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$nombre=strtoupper($_POST["txt_nomDoc"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		
		if($_POST['rdb_id']!=$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET id_documento='$_POST[txt_idDocumento]',catalogo_clasificacion_id_clasificacion='$id_clasificacion',
					   catalogo_carpetas_id_carpeta='$id_carpeta', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$archivo', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$tipo' WHERE id_documento='$_POST[rdb_id]'";
		}
		if($_POST['rdb_id']==$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET catalogo_clasificacion_id_clasificacion='$id_clasificacion',
					   catalogo_carpetas_id_carpeta='$id_carpeta', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$archivo', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$tipo' WHERE id_documento='$_POST[rdb_id]'";
		}
						
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_seguridad",$_POST['txt_idDocumento'],"ModificarDocumento",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
		
	//Esta funcion permite guardas los Documentos en el SERVIDOR
	function subirArchivo($archivo){
		//Incluimos archivos para realizar las operaciones con la BAse de datos
		include_once("../../includes/op_operacionesBD.php");
		
		//Creamos la variable ruta que servira para abrir la misma	
		$Ruta='';
		
		//Declaramos las variables que nos serviran para el proceso de la carga de documentos
		$carpeta2="";
		$carpeta3="";
		$carpeta="";
		$clasificacion="";
		/***************************************CASOS EN LOS CUALES SE PODRAN GUARDAR LOS DOCUMENTOS*******************************************************/
		
		///Caso 1.- en el cual si no esta definida la norma ni la clasificacion se quedaran en la raiz
		//Solo verificamos que se encuentren vacias las cajas de texto y los combos, pues si no se selecciona ninguno iran en el post
		if($_POST["txt_carpeta"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_carpeta"]==""&&$_POST["cmb_clasificacion"]==""){
			$carpeta2='documentos/SI';
		}
		
		//Caso 2.-Cuando se haya seleccionado solo norma 
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$clasificacion=strtoupper($_POST["txt_clasificacion"]);
			$carpeta2='documentos/SI/'.$clasificacion;
		}
		if(isset($_POST['cmb_clasificacion'])&&$_POST['cmb_clasificacion']!=""){
			$clasificacion=$_POST["cmb_clasificacion"];
			$carpeta2='documentos/SI/'.$clasificacion;
		}
		
		//Caso 3.- Cuando se haya seleccionado la Norma y Ademas se haya Seleccionado una clasificacion
		if(isset($_POST["txt_carpeta"])&&$_POST["txt_carpeta"]!=""){
			$carpeta=strtoupper($_POST["txt_carpeta"]);
			$carpeta3='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		if(isset($_POST['cmb_carpeta'])&&$_POST['cmb_carpeta']!=""){
			$carpeta=$_POST["cmb_carpeta"];
			$carpeta3='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		
		//Abrimos la ruta para crear la carpeta
		$dir = opendir($Ruta); 
		//Verificamos que el archivo haya sido updated
		if (is_uploaded_file($_FILES['file_documento']['tmp_name'])) { 
			//Si $carpeta no ah sido creado se crea con mkdir
			if (!file_exists($carpeta2."/")){
				mkdir($carpeta2."/", 0777);
				//Si la carpeta 3 se encuentra vacia quiere decir que no se selecciono la clasificacion
				if($carpeta3!=""){
					if(!file_exists($carpeta3."/")){
						mkdir($carpeta2."/", 0777);
					}
				}
			}
			else if(!file_exists($carpeta3."/")){
					mkdir($carpeta2."/", 0777);
			}
			//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
			if(!file_exists($carpeta2."/".$archivo)){		
				move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta2."/".$archivo);     	    	 	
				?>
				<script>
					setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
				</script>
				<?php
			}
			else{
				?>
				<script>
					setTimeout("alert('La Clave Utilizada Para El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe, Ingrese Otra');",500);
				</script>
				<?php
			}				
		}
	}//Fin de la funcion 
	
	//Funcion que permite obtener el id de la carpeta
	function obtenerIdCarpeta(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_carpeta)+1 AS cant FROM catalogo_carpetas";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=001;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()


	//Funcion que permite obtener el id de la clasificacion
	function obtenerIdClasifcacion(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_clasificacion)+1 AS cant FROM catalogo_clasificacion";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=001;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	 
	 
	//Función que permite eliminar el plano segun sea seleccionado
	function eliminarDocumento(){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_seguridad");
		
		//Creamos la consulta
		$stm_sql ="SELECT * FROM repositorio_documentos WHERE id_documento='$_POST[rdb_id]'";
				
		//Verificar si la sentencia ejecutada se genero con exito
		$rs=mysql_query($stm_sql);
		
		//verificamos que la sentencia sea ejecutada con exito
		if ($rs){
			//Guardamos los datos necesarios para poder tomarlos de la consulta e indicar que archivo sera eliminado
			if($datos=mysql_fetch_array($rs)){						
				$ruta=$datos["ruta"];
				$nombreArchivo=$datos["nom_archivo"];
			}
			
				//Creamos arreglos para verificar si las carpetas tienen datos; ya que si tienen datos no pueden ser eliminadas
			$archivos=array();
			
			//Abrimos el archivo y reccorremos en busqueda de sub-carpetas o archivos
			if($gestor = opendir($ruta)) {
	    		while(false !== ($arch = readdir($gestor))){
					if ($arch != "." && $arch != ".."){
				   		//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
						@unlink($ruta."/".$nombreArchivo);
					}
	    		}
			}
	   	 	closedir($gestor);
		}
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}

	//Esta funcion permite registrar los Archivos en la BD
	function guardarModificacionRegistro(){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad Industrial
		$conn = conecta("bd_seguridad");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD si estos permanecen nulos, no se selecciono norma ni, clasificacion
		$id_carpeta="";
		$id_clasificacion="";
		$ruta="";
		$tipo="";
		
		//Verificamos que el combo norma este definido y que no venga vacio. Para generar la ruta donde se guardara dicho archivo
		if(isset($_POST["cmb_clasificacion"])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion = $_POST["cmb_clasificacion"];
			$ruta='documentos/SI/'.$clasificacion;
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_clasificacion=obtenerDato("bd_seguridad", "catalogo_clasificacion", "id_clasificacion", "clasificacion", $_POST["cmb_clasificacion"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_norma
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$validarCla=obtenerDato("bd_seguridad", "catalogo_clasificacion", "clasificacion", "clasificacion", $_POST["txt_clasificacion"]);
			if($validarCla==""){
				//Obtenemos el id de la norma para realizar la insercion en la BD
				$id_clasificacion = obtenerIdClasificacion();
				$stm_sql2 = "INSERT INTO catalogo_clasificacion(id_clasificacion,clasificacion) VALUES('$id_clasificacion','$_POST[txt_clasificacion]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
			}
			$clasificacion=$_POST['txt_clasificacion'];
			$ruta='documentos/SI/'.$clasificacion;
		}
		
		//Verificamos que se encuentre definido el combo clasificacion de ser asi obtenemos el dato correspondiente
		if(isset($_POST["cmb_carpeta"])&&$_POST["cmb_carpeta"]!=""){
			$carpeta = $_POST["cmb_carpeta"];
			$ruta='documentos/SI/'.$clasificacion.'/'.$carpeta;
			$id_carpeta=obtenerDato("bd_seguridad", "catalogo_carpetas", "id_carpeta", "carpeta", $_POST["cmb_carpeta"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_clasificacion
		if(isset($_POST["txt_carpeta"])&&$_POST["txt_carpeta"]!=""){
			echo "carpeta".$validarCar=obtenerDato("bd_seguridad", "catalogo_carpetas", "carpeta", "carpeta", $_POST["txt_carpeta"]);
			if($validarCla==""){
				$id_carpeta = obtenerIdCarpeta();
				$stm_sql1 = "INSERT INTO catalogo_carpetas(id_carpeta,carpeta) VALUES('$id_carpeta','$_POST[txt_carpeta]')";
				//Ejecutar la sentencia previamente creada 
				$rs1 = mysql_query($stm_sql1);
			}
			$carpeta=$_POST['txt_carpeta'];
			$ruta='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		
		//Si los campos y los combos permanecen vacios quiere decir que el archivo se guardara en la raiz
		if($_POST["txt_carpeta"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_carpeta"]==""&&$_POST["cmb_clasificacion"]==""){
			$ruta='documentos/SI';
		}
			
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$nombre=strtoupper($_POST["txt_nomDoc"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		
		if($_POST['rdb_id']!=$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET id_documento='$_POST[txt_idDocumento]',catalogo_clasificacion_id_clasificacion='$id_clasificacion',
					   catalogo_carpetas_id_carpeta='$id_carpeta', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$_POST[txt_idDocumento].$_POST[hdn_tipo]', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$_POST[hdn_tipo]' 
						WHERE id_documento='$_POST[rdb_id]'";
		}
		if($_POST['rdb_id']==$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
		 	$stm_sql = "UPDATE repositorio_documentos SET catalogo_clasificacion_id_clasificacion='$id_clasificacion',
					   catalogo_carpetas_id_carpeta='$id_carpeta', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$_POST[txt_idDocumento].$_POST[hdn_tipo]', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$_POST[hdn_tipo]' 
						WHERE id_documento='$_POST[rdb_id]'";
		}
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		if ($band==1){
			$error=mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_seguridad",$_POST['txt_idDocumento'],"ModificarDocumento",$_SESSION['usr_reg']);
			?>
				<script language="javascript" type="text/javascript">
					setTimeout("alert('Archivo Modificado Correctamente');",500);
				</script>
			<?php
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}	
	
	//Esta funcion permite guardas los Documentos en el SERVIDOR
	function subirArchivoMod(){
		//Incluimos archivos para realizar las operaciones con la BAse de datos
		include_once("../../includes/op_operacionesBD.php");
		
		//Creamos la variable ruta que servira para abrir la misma	
		$Ruta='';
		
		//Declaramos las variables que nos serviran para el proceso de la carga de documentos
		$carpeta2="";
		$carpeta3="";
		$carpeta="";
		$clasificacion="";
		/***************************************CASOS EN LOS CUALES SE PODRAN GUARDAR LOS DOCUMENTOS*******************************************************/
		
		//Caso 1.- en el cual si no esta definida la norma ni la clasificacion se quedaran en la raiz
		//Solo verificamos que se encuentren vacias las cajas de texto y los combos, pues si no se selecciona ninguno iran en el post
		if($_POST["txt_carpeta"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_carpeta"]==""&&$_POST["cmb_clasificacion"]==""){
			$carpeta2='documentos/SI';
		}
		
		//Caso 2.-Cuando se haya seleccionado solo norma 
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$clasificacion=strtoupper($_POST["txt_clasificacion"]);
			$carpeta2='documentos/SI/'.$clasificacion;
		}
		if(isset($_POST['cmb_clasificacion'])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion=$_POST["cmb_clasificacion"];
			$carpeta2='documentos/SI/'.$clasificacion;
		}
		
		//Caso 3.- Cuando se haya seleccionado la Norma y Ademas se haya Seleccionado una clasificacion
		if(isset($_POST["txt_carpeta"])&&$_POST["txt_carpeta"]!=""){
			$carpeta=strtoupper($_POST["txt_carpeta"]);
			$carpeta3='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		if(isset($_POST['cmb_carpeta'])&&$_POST["cmb_carpeta"]!=""){
			$carpeta=$_POST["cmb_carpeta"];
			$carpeta3='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		//Abrimos la ruta para crear la carpeta
		$dir = opendir($Ruta); 
		//Si $carpeta no ah sido creado se crea con mkdir
		if(!file_exists($carpeta2."/")){
			mkdir($carpeta2."/", 0777);
			//Si la carpeta 3 se encuentra vacia quiere decir que no se selecciono la clasificacion
			if($carpeta3!=""){
				if (!file_exists($carpeta3."/")){
					$carpeta2=$carpeta3;
					mkdir($carpeta2."/", 0777);
				}
			}
		}
		//Si la carpeta 3 se encuentra vacia quiere decir que no se selecciono la clasificacion
		if($carpeta3!=""){
			if(!file_exists($carpeta3."/")){
				mkdir($carpeta3."/", 0777);
			}
			$carpeta2=$carpeta3;
		}
	   		//Usamos la funcion remame por primera vez ´para mover el archivo a la ubicacion en caso de que lo modifique
			rename($_POST["hdn_ruta"]."/".$_POST["hdn_id"].".".$_POST["hdn_tipo"], $carpeta2."/".$_POST["hdn_id"].".".$_POST["hdn_tipo"]);
			//Renombramos el archivo y completamos con la informacion necesaria para poder realizar el rename
			rename($carpeta2."/".$_POST['hdn_id'].".".$_POST["hdn_tipo"], $carpeta2."/".$_POST['txt_idDocumento'].".".$_POST["hdn_tipo"]);	
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";		
	}//Fin de la funcion 
	
	
	function obtenerIdClasificacion(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_clasificacion)+1 AS cant FROM catalogo_clasificacion";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=001;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
?>