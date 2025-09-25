<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 09/Noviembre/2011                                      			
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
		$conn = conecta("bd_aseguramiento");
		
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
						<th class='nombres_columnas' align='center'>NORMA</th>
						<th class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>FECHA REGISTRO</th>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>DESCARGAR</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{	
				$norma=obtenerDato("bd_aseguramiento", "catalogo_norma", "norma", "id_norma", $datos['catalogo_norma_id_norma']);
				$clasificacion=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "clasificacion", "id_clasificacion", $datos['catalogo_clasificacion_id_clasificacion']);	
				if($norma==""){
					$norma="DOCUMENTO UBICADO EN CARPETA PRINCIPAL";
				}
				if($clasificacion==""){
					$clasificacion="N/A";
				}						
				echo "	<tr>
							<td class='$nom_clase' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_documento]'/>
							</td>				
							<td class='$nom_clase' align='center'>$datos[id_documento]</td>					
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='center'>$norma</td>
							<td class='$nom_clase' align='center'>$clasificacion</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>					
							<td class='$nom_clase' align='left'>$datos[descripcion]</td>";
							?>
							<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
								title="Descargar Documento<?php echo $datos['nombre'];?>" 
								onClick="javascript:window.open('marco_descarga.php?id_documento=<?php echo $datos['id_documento'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $datos['ruta'];?>&nombre=<?php echo $datos['nombre'];?>&tipo=<?php echo $datos['tipo_archivo'];?>',
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
		
		//Conectar a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");

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
					<input name="txt_idDocumento" id="txt_idDocumento" type="text" class="caja_de_texto" size="15" maxlength="15" 
					value="<?php echo $datos['id_documento'];?>" onchange="validarCaracteres(this);" onkeypress="return permite(event,'num_car', 6);"/>				</td>
          		<td width="52"><div align="right">Fecha</div></td>
          		<td width="102">
					<input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/>				</td>
          		<td width="200"><div align="right">*Nombre </div></td>
          		<td width="236">
					<input name="txt_nomDoc" id="txt_nomDoc" type="text" class="caja_de_texto" size="40" maxlength="80" 
					onkeypress="return permite(event,'num_car',0);" value="<?php echo $datos['nombre'];?>"/>				</td>
        	</tr>
			<tr>
          	<td width="97"><div align="right">Norma</div></td>
          	<td colspan="3"><?php 
				$normaOri=obtenerDato("bd_aseguramiento", "catalogo_norma", "norma", "id_norma", $datos['catalogo_norma_id_norma']);
				$cmb_norma=$normaOri;
				$conn = conecta("bd_aseguramiento");
				$result=mysql_query("SELECT DISTINCT norma FROM catalogo_norma ORDER BY norma");
				if($norma=mysql_fetch_array($result)){?>
				 <select name="cmb_norma" id="cmb_norma" size="1" class="combo_box" onchange="desactivarCombo();">
					<option value="" >Norma</option>
					<?php 
					  do{
							if ($norma['norma'] == $cmb_norma){
								echo "<option value='$norma[norma]' selected='selected'>$norma[norma]</option>";
							}
							else{
								echo "<option value='$norma[norma]'>$norma[norma]</option>";
							}
						}while($norma=mysql_fetch_array($result)); 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
				</select>
				  <?php }
					else{
						echo "<label class='msje_correcto'> No hay Normas Registradas</label>
						<input type='hidden' name='cmb_norma' id='cmb_norma'/>";?>
						<input type="hidden" name="hdn_normaDefinida" id="hdn_normaDefinida" value=""/>
					<?php }?>	 	 	</td>
          	<td><div align="right">
              	<input type="checkbox" name="ckb_norma" id="ckb_norma" 
				onclick="agregarNuevaNormaMod(this, 'txt_norma', 'cmb_norma');  desactivarCombo();" 
				title="Seleccione para Escribir el Nombre de una Norma que no Exista" />
           	 	Agregar Norma </div>			</td>
          	<td><input name="txt_norma" id="txt_norma" type="text" class="caja_de_texto" size="40" readonly="readonly"/></td>
        </tr>
        <tr>
        	<td width="97"><div align="right">Clasificaci&oacute;n</div></td>
          	<td colspan="3"><?php 
				$clasificacionOri=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "clasificacion", "id_clasificacion", $datos['catalogo_clasificacion_id_clasificacion']); 
				$cmb_clasificacion=$clasificacionOri;
				$conn = conecta("bd_aseguramiento");
				$result=mysql_query("SELECT DISTINCT clasificacion FROM catalogo_clasificacion ORDER BY clasificacion");
				if($clasificacion=mysql_fetch_array($result)){?>
          	  <select name="cmb_clasificacion" id="cmb_clasificacion" size="1" class="combo_box" onchange="desactivarCombo();">
                <option value="">Clasificaci&oacute;n</option>
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
					<input type='hidden' name='cmb_clasificacion' id='cmb_clasificacion'/>";
				 ?>
             	 <input type="hidden" name="hdn_clafisificacionDefinida" id="hdn_clafisificacionDefinida" value=""/>
              <?php }?>		  </td>
         	<td>
			  <div align="right">
					<input type="checkbox" name="ckb_clasificacion" id="ckb_clasificacion" 
					onclick="agregarNuevaClasificacionMod(this, 'txt_clasificacion', 'cmb_clasificacion'); desactivarCombo();" 
					title="Seleccione para Escribir el Nombre de una Ubicaci&oacute;n que no Exista" />
					Agregar Clasificación			  </div>			</td>
          	<td>
				<input name="txt_clasificacion" id="txt_clasificacion" type="text" class="caja_de_texto" size="40" readonly="readonly"/>			</td>
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
					<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Valores" onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a Seleccionar Otro Registro" 
					onmouseover="window.status='';return true"  onclick="confirmarSalida('frm_modificarDocumentos.php')" />
					<input type="hidden" name="rdb_id" id="rdb_id" value="<?php echo $_POST['rdb_id'];?>"/>
					<input type="hidden" name="hdn_ruta" id="hdn_ruta" value="<?php echo $datos['ruta'];?>"/>
					<input type="hidden" name="hdn_id" id="hdn_id" value="<?php echo $datos['id_documento'];?>"/>
					<input type="hidden" name="hdn_tipo" id="hdn_tipo" value="<?php echo $datos['tipo_archivo'];?>"/>
					<input type="hidden" name="hdn_comboNorma" id="hdn_comboNorma" value="<?php echo $normaOri;?>"/>
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
		if (isset($_FILES["file_documento"]["error"])==0){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			eliminarDocumento();
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			//Guardamos el tamaño del arreglo
			$tam = count($archivoSec)-1;
			//Ubicamos el tipo en la posisicion final dela arreglo para obtener el tipo de archivo
			$tipo=$archivoSec[$tam];
			$archivo=$_POST['txt_idDocumento'].'.'.$tipo;
			$resSubirArch = subirArchivo($archivo);
			guardarModificacionRegistroArchivo($archivo);
		}
		if(isset($_FILES["file_documento"]["error"])==4){
			guardarModificacionRegistro();
		}
	 }
	 
	 //Esta funcion permite registrar los Archivos en la BD
	function guardarModificacionRegistroArchivo($archivo){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado; para recuperar el nombre en la variable $archivo
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			//Guardamos el tamaño del arreglo
			$tam = count($archivoSec)-1;
			//Ubicamos el tipo en la posisicion final dela arreglo para obtener el tipo de archivo
			$tipo=$archivoSec[$tam];
			$archivo=$_POST['txt_idDocumento'].'.'.$tipo;
		}
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD si estos permanecen nulos, no se selecciono norma ni, clasificacion
		$id_norma="";
		$id_clasificacion="";
		
		//Verificamos que el combo norma este definido y que no venga vacio. Para generar la ruta donde se guardara dicho archivo
		if(isset($_POST["cmb_norma"])&&$_POST["cmb_norma"]!=""){
			$norma = $_POST["cmb_norma"];
			$ruta='documentos/SGC/'.$norma;
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_norma=obtenerDato("bd_aseguramiento", "catalogo_norma", "id_norma", "norma", $_POST["cmb_norma"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_norma
		if(isset($_POST["txt_norma"])&&$_POST["txt_norma"]!=""){
			$valNor=obtenerDato("bd_aseguramiento", "catalogo_norma", "norma", "norma", $_POST["txt_norma"]);
			if($valNor==""){
				//Obtenemos el id de la norma para realizar la insercion en la BD
				$id_norma = obtenerIdNorma();
				$stm_sql2 = "INSERT INTO catalogo_norma(id_norma,norma) VALUES('$id_norma','$_POST[txt_norma]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
			}
			$norma=$_POST['txt_norma'];
			$ruta='documentos/SGC/'.$norma;
		}
		//Verificamos que se encuentre definido el combo clasificacion de ser asi obtenemos el dato correspondiente
		if(isset($_POST["cmb_clasificacion"])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion = $_POST["cmb_clasificacion"];
			$ruta='documentos/SGC/'.$norma.'/'.$clasificacion;
			$id_clasificacion=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "id_clasificacion", "clasificacion", $_POST["cmb_clasificacion"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_clasificacion
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$valCla=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "clasificacion", "clasificacion", $_POST["txt_clasificacion"]);
			if($valCla==""){
				$id_clasificacion = obtenerIdClasifcacion();
				$stm_sql1 = "INSERT INTO catalogo_clasificacion(id_clasificacion,clasificacion) VALUES('$id_clasificacion','$_POST[txt_clasificacion]')";
				//Ejecutar la sentencia previamente creada 
				$rs1 = mysql_query($stm_sql1);
			}
			$clasificacion=$_POST['txt_clasificacion'];
			$ruta='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		
		//Si los campos y los combos permanecen vacios quiere decir que el archivo se guardara en la raiz
		if($_POST["txt_norma"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_norma"]==""&&$_POST["cmb_clasificacion"]==""){
			$ruta='documentos/SGC';
		}
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$nombre=strtoupper($_POST["txt_nomDoc"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		
		if($_POST['rdb_id']!=$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET id_documento='$_POST[txt_idDocumento]',catalogo_norma_id_norma='$id_norma',
					   catalogo_clasificacion_id_clasificacion='$id_clasificacion', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$archivo', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$tipo' WHERE id_documento='$_POST[rdb_id]'";
		}
		if($_POST['rdb_id']==$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET catalogo_norma_id_norma='$id_norma',
					   catalogo_clasificacion_id_clasificacion='$id_clasificacion', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$archivo', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$tipo' WHERE id_documento='$_POST[rdb_id]'";
		}
						
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		if ($band==1){
			//echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$_POST['txt_idDocumento'],"ModificarDocumento",$_SESSION['usr_reg']);
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
		$norma="";
		$clasificacion="";
		/***************************************CASOS EN LOS CUALES SE PODRAN GUARDAR LOS DOCUMENTOS*******************************************************/
		
		//Caso 1.- en el cual si no esta definida la norma ni la clasificacion se quedaran en la raiz
		//Solo verificamos que se encuentren vacias las cajas de texto y los combos, pues si no se selecciona ninguno iran en el post
		if($_POST["txt_norma"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_norma"]==""&&$_POST["cmb_clasificacion"]==""){
			$carpeta2='documentos/SGC';
		}
		
		//Caso 2.-Cuando se haya seleccionado solo norma 
		if(isset($_POST["txt_norma"])&&$_POST["txt_norma"]!=""){
			$norma=strtoupper($_POST["txt_norma"]);
			$carpeta2='documentos/SGC/'.$norma;
		}
		if(isset($_POST['cmb_norma'])&&$_POST["cmb_norma"]!=""){
			$norma=$_POST["cmb_norma"];
			$carpeta2='documentos/SGC/'.$norma;
		}
		
		//Caso 3.- Cuando se haya seleccionado la Norma y Ademas se haya Seleccionado una clasificacion
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$clasificacion=strtoupper($_POST["txt_clasificacion"]);
			$carpeta3='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		if(isset($_POST['cmb_clasificacion'])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion=$_POST["cmb_clasificacion"];
			$carpeta2='documentos/SGC/'.$norma.'/'.$clasificacion;
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
					if (!file_exists($carpeta3."/")){
						$carpeta2=$carpeta3;
						mkdir($carpeta2."/", 0777);
					}
				}
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
	
	//Funcion que permite obtener el id de la norma
	function obtenerIdNorma(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_norma)+1 AS cant FROM catalogo_norma";
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
		$conn = conecta("bd_aseguramiento");
		
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
		$conn = conecta("bd_aseguramiento");
		
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
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD si estos permanecen nulos, no se selecciono norma ni, clasificacion
		$id_norma="";
		$id_clasificacion="";
		$ruta="";
		$tipo="";
		
		//Verificamos que el combo norma este definido y que no venga vacio. Para generar la ruta donde se guardara dicho archivo
		if(isset($_POST["cmb_norma"])&&$_POST["cmb_norma"]!=""){
			$norma = $_POST["cmb_norma"];
			$ruta='documentos/SGC/'.$norma;
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_norma=obtenerDato("bd_aseguramiento", "catalogo_norma", "id_norma", "norma", $_POST["cmb_norma"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_norma
		if(isset($_POST["txt_norma"])&&$_POST["txt_norma"]!=""){
			//Obtenemos el id de la norma para realizar la insercion en la BD
			$id_norma = obtenerIdNorma();
			$stm_sql2 = "INSERT INTO catalogo_norma(id_norma,norma) VALUES('$id_norma','$_POST[txt_norma]')";
			//Ejecutar la sentencia previamente creada
			$rs2 = mysql_query($stm_sql2);
			$norma=$_POST['txt_norma'];
			$ruta='documentos/SGC/'.$norma;
		}
		//Verificamos que se encuentre definido el combo clasificacion de ser asi obtenemos el dato correspondiente
		if(isset($_POST["cmb_clasificacion"])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion = $_POST["cmb_clasificacion"];
			$ruta='documentos/SGC/'.$norma.'/'.$clasificacion;
			$id_clasificacion=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "id_clasificacion", "clasificacion", $_POST["cmb_clasificacion"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_clasificacion
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$id_clasificacion = obtenerIdClasifcacion();
			$stm_sql1 = "INSERT INTO catalogo_clasificacion(id_clasificacion,clasificacion) VALUES('$id_clasificacion','$_POST[txt_clasificacion]')";
			//Ejecutar la sentencia previamente creada 
			$rs1 = mysql_query($stm_sql1);
			$clasificacion=$_POST['txt_clasificacion'];
			$ruta='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		
		//Si los campos y los combos permanecen vacios quiere decir que el archivo se guardara en la raiz
		if($_POST["txt_norma"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_norma"]==""&&$_POST["cmb_clasificacion"]==""){
			$ruta='documentos/SGC';
		}
			
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$nombre=strtoupper($_POST["txt_nomDoc"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		
		if($_POST['rdb_id']!=$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET id_documento='$_POST[txt_idDocumento]',catalogo_norma_id_norma='$id_norma',
					   catalogo_clasificacion_id_clasificacion='$id_clasificacion', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$_POST[txt_idDocumento].$_POST[hdn_tipo]', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$_POST[hdn_tipo]' 
						WHERE id_documento='$_POST[rdb_id]'";
		}
		if($_POST['rdb_id']==$_POST['txt_idDocumento']){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "UPDATE repositorio_documentos SET catalogo_norma_id_norma='$id_norma',
					   catalogo_clasificacion_id_clasificacion='$id_clasificacion', nombre='$nombre', fecha='$fecha',  
						nom_archivo='$_POST[txt_idDocumento].$_POST[hdn_tipo]', descripcion='$descripcion', ruta='$ruta', tipo_archivo='$_POST[hdn_tipo]' 
						WHERE id_documento='$_POST[rdb_id]'";
		}
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		if ($band==1){
			//echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$_POST['txt_idDocumento'],"ModificarDocumento",$_SESSION['usr_reg']);
			if($_POST["hdn_comboNorma"]!=$_POST["txt_norma"] || !isset($_POST['cmb_norma'])){
				subirArchivoMod();
			}
			else{?>
				<script>
					setTimeout("alert('Archivo Modificado Correctamente');",500);
				</script>
			<?php }
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
		$norma="";
		$clasificacion="";
		/***************************************CASOS EN LOS CUALES SE PODRAN GUARDAR LOS DOCUMENTOS*******************************************************/
		
		//Caso 1.- en el cual si no esta definida la norma ni la clasificacion se quedaran en la raiz
		//Solo verificamos que se encuentren vacias las cajas de texto y los combos, pues si no se selecciona ninguno iran en el post
		if($_POST["txt_norma"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_norma"]==""&&$_POST["cmb_clasificacion"]==""){
			$carpeta2='documentos/SGC';
		}
		
		//Caso 2.-Cuando se haya seleccionado solo norma 
		if(isset($_POST["txt_norma"])&&$_POST["txt_norma"]!=""){
			$norma=strtoupper($_POST["txt_norma"]);
			$carpeta2='documentos/SGC/'.$norma;
		}
		if(isset($_POST['cmb_norma'])&&$_POST["cmb_norma"]!=""){
			$norma=$_POST["cmb_norma"];
			$carpeta2='documentos/SGC/'.$norma;
		}
	
		//Caso 3.- Cuando se haya seleccionado la Norma y Ademas se haya Seleccionado una clasificacion
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$clasificacion=strtoupper($_POST["txt_clasificacion"]);
			$carpeta3='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		if(isset($_POST['cmb_clasificacion'])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion=$_POST["cmb_clasificacion"];
			$carpeta3='documentos/SGC/'.$norma.'/'.$clasificacion;
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
			rename($carpeta2."/".$_POST['hdn_id'].".".$_POST["hdn_tipo"], $carpeta2."/".$_POST['txt_idDocumento'].".".$_POST["hdn_tipo"]);?>
			<script>
				setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Modificado Correctamente');",500);
			</script>
			<?php	
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";		
	}//Fin de la funcion 
	
	
?>