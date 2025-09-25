<?php
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Nadia Madah� L�pez Hern�ndez                            
	  * Fecha: 15/Octubre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de Generar Requisicones 
	  **/
	
	//Esta funci�n se encarga de generar el Id de la Requisicion de acurdo a los registros existentes en la BD
	function obtenerIdRequisicion(){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
		
		//Definir las dos letras en la Id de la Requisicion
		$id_cadena = "ALM";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Requisicion registradas 
		$stm_sql = "SELECT COUNT(id_requisicion) AS cant FROM requisiciones WHERE id_requisicion LIKE 'ALM$mes$anio%'";
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
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdRequisicion()			
	
	function guardarRequisicion2(){
		$id = obtenerIdRequisicion();
		$conn = conecta("bd_almacen");
		$stm_sql = "SELECT * 
					FROM  `requisiciones` 
					WHERE  `id_requisicion_procedencia` LIKE  '$_POST[txt_numero]'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$error = "Requisici�n $_POST[txt_numero] ya se encuentra registrada";
			echo "<meta http-equiv='refresh' content='2;url=error.php?err=$error'>";
		}
		else{
			if($_GET["depto"] == "sabinas"){
				$area = "SABINAS";
			} else {
				$area = $_POST["txt_areaSolicitante"];
			}
			$estado = $_POST["cmb_estado"];
			$id_req = $_POST["txt_numero"];
			$solic = $_POST["txt_solicita"];
			$elaboro = $_POST["txt_elaboradorReq"];
			$just = $_POST["txa_justificacion"];
			$coment = strtoupper($_POST["txa_comentarios"]);
			$num_reg = $_POST["hdn_numReg"];
			$prioridad = $_POST["hdn_prioridad"];
			$fecha = date("Y/m/d");
			$stm_sql = "INSERT INTO requisiciones (id_requisicion, area_solicitante, fecha_req, justificacion_tec, elaborador_req, solicitante_req, estado, comentario_compras, 
					prioridad,observaciones,id_requisicion_procedencia)
					VALUES('$id','$area','$fecha','$just','$elaboro','$solic','ENVIADA', '$coment',
					'$prioridad','$coment','$id_req')";
			$rs = mysql_query($stm_sql);
			if($rs){
				$band = 0;
				for($i=1; $i<$num_reg; $i++){
					$con_clave = 1;
					$clave_mat = $_POST["txt_id_material".$i];
					$cantidad = $_POST["txt_cantidad".$i];
					$unidad = strtoupper($_POST["txt_unidad".$i]);
					$descri = strtoupper($_POST["txt_descripcion".$i]);
					$aplicacion = strtoupper($_POST["txt_aplicacion".$i]);
					if($clave_mat == "N/A"){
						$con_clave = 0;
					}
					if($cantidad != 0){
						$stm_sql = "INSERT INTO detalle_requisicion (requisiciones_id_requisicion, materiales_id_material, cant_req, unidad_medida, descripcion, aplicacion, con_clave, partida)
						VALUES('$id','$clave_mat', $cantidad, '$unidad', '$descri', '$aplicacion', '$con_clave', $i)";
						$rs = mysql_query($stm_sql);
						if(!$rs)
							$band = 1;
						else{//Si el registros del material en la requisici�n se llevo a cabo con exito, verificar si el material se encuentra registrado en las alertas y cambiar el estado de la misma
							regEstadoAlerta($clave_mat);
						}	
					}
				}
				if($band==0){
					$cveReq = $_POST["hdn_numero"];
					$comentario = strtoupper($_POST["txa_comentarios"]);
					$departamento = $_GET["depto"];
					$base = $_POST["hdn_bd"];
					$estadoReq = "EN PROCESO";
					//Llamamos a la funcion que almacena el comentario con la clave de requisicion, el comentario y el departamento de trabajo actual
					guardarComentario($cveReq,$comentario,$departamento,$base,$estadoReq);
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_almacen",$id,"GenerarRequisicion",$_SESSION['usr_reg']);
				
					?>
					<script type='text/javascript' language='javascript'>
						setTimeout("window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $id; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
					</script>
				
					<?php
					//Redireccionar a la Pagina de exito despues de 5 segundos				
					echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
				} 
				else{
					//Borrar la Requisicion en caso de que los materiales no se hayan agregado por completo
					mysql_query("DELETE FROM requisiciones WHERE id_requisicion='".$id."'");
					//Borrar el detalle de la Requisicion en caso de que los materiales no se hayan agregado por completo
					mysql_query("DELETE FROM detalle_requisicion WHERE requisiciones_id_requisicion='".$id."'");
					//Redireccionar a una pagina de error
					$error = "No se pudo guardar la Requisici&oacute;n";
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				}
			}
			else{
				//Redireccionar a una pagina de error
				$error = mysql_error();
			
				echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>$error";
				break;
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		mysql_close($conn);
	}
	
	//Agregar el registro de la Requisicion de Materiales a las tablas de detalle_Requisicion y Requisiciones
	function guardarRequisicion($txa_justificacionReq,$hdn_fecha,$txt_areaSolicitante,$txt_solicitanteReq,$txt_elaboradorReq){
		
		$_SESSION['id_requisicion'] = obtenerIdRequisicion();
		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");					
		
		//Pasar a Mayusculas los datos para GenerarRequisicion
		$txa_justificacionReq = strtoupper($txa_justificacionReq); $txt_areaSolicitante = strtoupper($txt_areaSolicitante); 
		$txt_solicitanteReq = strtoupper($txt_solicitanteReq); $correo = $_POST["txt_correo"];
		$txt_elaboradorReq = strtoupper($txt_elaboradorReq);
		if($_SESSION["tipo_usr"] == "administrador")
			$autorizada = 1;
		else
			$autorizada = 0;
		
		$autorizada = 1;
		
		//asignar a la variable $cmb_prioridad el valor seleccionado previamente
		$cmb_prioridad=$_POST["cmb_prioridad"];
			
		$comentario="";
		//Verificar si hay un comentario agregado
		if (isset($_SESSION['comentario']))
			$comentario=$_SESSION['comentario'];
			
		//Crear la sentencia para almacenar los datos de la entrada en la BD
		$stm_sql = "INSERT INTO requisiciones (id_requisicion, area_solicitante, fecha_req, justificacion_tec, elaborador_req, solicitante_req, estado, comentario_compras, 
					prioridad,observaciones,autorizada,correo)
					VALUES('$_SESSION[id_requisicion]','$txt_areaSolicitante','$hdn_fecha','$txa_justificacionReq','$txt_elaboradorReq','$txt_solicitanteReq','ENVIADA', 'N/A',
					'$cmb_prioridad','$comentario',$autorizada,'$correo')";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);		
		
		if($rs){			
			//Si la bandera se activa significa que hubo errores
			$band = 0;
			$cont = 1;
			//Registrar todos los materiales dados de alta en el arreglo $datosRequisicion
			foreach ($_SESSION['datosRequisicion'] as $ind => $material) {
				//Crear la sentencia para realizar el registro de los datos del detalle de la Requisicion
				$stm_sql = "INSERT INTO detalle_requisicion (
								requisiciones_id_requisicion, 
								materiales_id_material, 
								cant_req, 
								unidad_medida, 
								descripcion, 
								aplicacion, 
								id_control_costos, 
								id_cuentas, 
								id_subcuentas, 
								con_clave, 
								precio_unit, 
								tipo_moneda, 
								partida
							) VALUES(
								'$_SESSION[id_requisicion]',
								'$material[clave]', 
								$material[cantReq], 
								'$material[unidad]', 
								'$material[material]', 
								'$material[aplicacionReq]', 
								'$material[cc]', 
								'$material[cuenta]', 
								'$material[subcuenta]', 
								'$material[nuevo_con_clave]', 
								'$material[costoU]', 
								'$material[moneda]', 
								$cont
							)";
				//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_requisicion
				$rs = mysql_query($stm_sql);
				if(!$rs)
					$band = 1;
				else{//Si el registros del material en la requisici�n se llevo a cabo con exito, verificar si el material se encuentra registrado en las alertas y cambiar el estado de la misma
					regEstadoAlerta($material['clave']);
				}
				$cont++;
			}
			//Confirmar que la insercion de datos fue realizada con exito.
			if($band==0){
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_almacen",$_SESSION['id_requisicion'],"GenerarRequisicion",$_SESSION['usr_reg']);
				
				?>
				<script type='text/javascript' language='javascript'>
					setTimeout("window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $_SESSION['id_requisicion']; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
				</script>
				
				<?php
				//Redireccionar a la Pagina de exito despues de 5 segundos				
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
				//Vaciar la informaci�n almacenada en la SESSION
				unset($_SESSION['datosRequisicion']);
				unset($_SESSION['id_requisicion']);
				//Comentario de la Requisicion
				if(isset($_SESSION['comentario']))
					unset($_SESSION['comentario']);
				//Fotografias en las requisiciones
				if(isset($_SESSION["fotosReq"]))
					unset($_SESSION['fotosReq']);						
			} 
			else{
				//Borrar la Requisicion en caso de que los materiales no se hayan agregado por completo
				mysql_query("DELETE FROM requisiciones WHERE id_requisicion='".$_SESSION['id_requisicion']."'");
				//Borrar el detalle de la Requisicion en caso de que los materiales no se hayan agregado por completo
				mysql_query("DELETE FROM detalle_requisicion WHERE requisiciones_id_requisicion='".$_SESSION['id_requisicion']."'");
				//Redireccionar a una pagina de error
				$error = "No se pudo guardar la Requisici&oacute;n";
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Redireccionar a una pagina de error
			$error = mysql_error();
			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen",$_SESSION['id_requisicion'],"requisicion",$_SESSION['usr_reg']);
	}//Fin de la funcion guardarCambios($txt_proveedor,$txt_noRequisicion,$txt_noFactura,$txt_costo,$txt_fecha,$cmb_aceptado,$txa_comentarios)
	
	
	/* Esta funcion se encarga de verificar si alguno de los materiales registrados en la requisicion tiene una alerta en la tabla de alertas y revisar el estado, 
	 * si el estado es 1, entonces cambiarlo a 2, que significa que el material ha sido requicitado y dejar de desplegar la alerta
	 */
	function regEstadoAlerta($id_material){
		//Crear consluta para verificar si el material que se esta registrado genero una alerta
		$stm_sql = "SELECT * FROM alertas WHERE materiales_id_material = '$id_material' AND estado = 1";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Evaluar los resultados y si se encuentra el material, cambiar el estado de la alerta de 1 a 2
		if($datos=mysql_fetch_array($rs)){
			mysql_query("UPDATE alertas SET estado = 2 WHERE materiales_id_material = '$id_material'");
		}
			
	}
	
	
	//Desplegar los materiales agregados a la Requisicion
	function mostrarRegistros($datosRequisicion){
		echo "				
		<table cellpadding='5' align='center' width='100%'>      			
			<tr>
				<td width='80' class='nombres_columnas' align='center'>CLAVE</td>
        		<td width='180' class='nombres_columnas' align='center'>NOMBRE (DESCRIPCI&Oacute;N)</td>
				<td width='100' class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
			    <td width='70' class='nombres_columnas' align='center'>CANT.</td>
				<td width='120' class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
				<td width='120' class='nombres_columnas' align='center'>CENTRO DE COSTOS</td>
				<td width='120' class='nombres_columnas' align='center'>CUENTA</td>
				<td width='120' class='nombres_columnas' align='center'>SUBCUENTA</td>
				<td width='30' class='nombres_columnas'></td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$aux="";
		$claveMat="";
		foreach ($datosRequisicion as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {
				switch($key){
					case "clave":
						echo "<td class='nombres_filas' align='center'>$value</td>";
						$claveMat=$value;
					break;
					case "material":
						echo "<td class='$nom_clase'>$value</td>";
						$aux=$value;
					break;
					case "unidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantReq":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "aplicacionReq":
						/*
						$dato = strtoupper(obtenerDatoTabla('categorias_mat','id_categoria',$value,"bd_almacen"));
						echo "<td class='$nom_clase' align='center'>$dato</td>";
						*/
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					/*
					case "equipo":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					*/
					case "cc":
						$dato = obtenerDatoTabla('control_costos','id_control_costos',$value,"bd_recursos");
						echo "<td class='$nom_clase' align='center'>$dato</td>";
					break;
					case "cuenta":
						$dato = obtenerDatoTabla('cuentas','id_cuentas',$value,"bd_recursos");
						echo "<td class='$nom_clase' align='center'>$dato</td>";
					break;
					case "subcuenta":
						$dato = obtenerDatoTabla('subcuentas','id_subcuentas',$value,"bd_recursos");
						echo "<td class='$nom_clase' align='center'>$dato</td>";
					break;
				}				
			}
			//Colocar la Imagen para permitir la Edicion del registro seleccionado
			?><td class="<?php echo $nom_clase;?>">
				<input type="image" src="../../images/editar.png" width="30" height="25" border="0" title="Modificar Registro" 
				onclick="location.href='frm_editarRegistros.php?origen=requisicion&pos=<?php echo $cont-1; ?>'" />
			</td><?php
			
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarRegistros($datosReqisicion)
	
	
	//Esta funci�n verifica que no se duplique un registro en el arreglo que guarda los datos del Detalle de la Requisici�n
	function verRegDuplicado($arr,$campo_clave,$campo_ref){
		$tam = count($arr);		
		$datos = $arr[$tam-1];
		if($datos[$campo_clave]==$campo_ref && $datos[$campo_clave] != "N/A")
			return true;
		else 
			return false;
	}
	
	//Esta funcion permite subir Archivos que forman parte del registro de las requiisiciones
	function subirFotos($clave, $nombre){
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");
		$nombreMostrar=obtenerDato("bd_almacen","materiales","nom_material","id_material",$nombre);
		$band=true;
		$nomArchivo=strtoupper(substr($_FILES['file_documento']['name'],-3));
		//Verificamos el tipo de archivo
		if($nomArchivo!= 'JPG'){
			?>
				<script>
					setTimeout("alert('El Formato del Archivo no es V�lido. S�lo se Permiten Im�genes JPG');",500);
				</script>
			<?php
			exit('');
			 
			$band = false;
			
		}
		//De lo contrario el archivo es valido y se procedera a subir el archivo en el lugar correspondiente
		else{
		//Obtenemos el nombre del material para mostrarlo en caso requerido
		$nombreMatSinC=$nombre;
		//Guardamos el nombre original del archivo en una variable para su facil manipulacion
		$nombreArchivoOriginal=$_FILES['file_documento']['name'];
		$seccRuta=explode(".",$nombreArchivoOriginal);
		$ext=$seccRuta[1];
		$nombreMat=$nombreMatSinC.".".$ext;
		//Partimos la cadena de texto para almacenarlas en un arreglo caracter por caracter
		$nombreMatSplit = str_split($nombreMat);
		//Verificamos el valor almacenado para sustituirlo
		for($i=0; $i<count($nombreMatSplit); $i++){
			if($nombreMatSplit[$i]=='"'){
				$nombreMatSplit[$i] = "�";
			}
			if($nombreMatSplit[$i]=='/'){
				$nombreMatSplit[$i] = "@";
			}
			if($nombreMatSplit[$i] =='-'){
				$nombreMatSplit[$i] = "�";
			}
			if($nombreMatSplit[$i]=='%'){
				$nombreMatSplit[$i] = "+";
			}
		}
		//Incrementamos el contador
		$contad = 1;
		//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
		foreach($nombreMatSplit as $key => $valor){
			if($contad==1){
				$nombreMat = $valor;
			}
			if($contad>1){
				$nombreMat .= $valor;
			}
			$contad++;
		}
		//Creamos la variable ruta que servira para abrir la misma	
		$Ruta='';			
		//Creamos la carpeta inicial dentro de documentos
		$carpeta='documentos/'.$clave;
		//Abrimos la ruta para crear la carpeta
		$dir = opendir($Ruta); 
		//Verificamos que el archivo haya sido updated
		if (is_uploaded_file($_FILES['file_documento']['tmp_name'])) { 
			//Si $carpeta no ah sido creado se crea con mkdir
			if (!file_exists($carpeta."/")){
				mkdir($carpeta."/", 0777);
			}
			//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
			if (!file_exists($carpeta."/".$nombreMat)){
				move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta."/". $nombreMat); 
				//llamar la funcion que se encarga de reducir el peso de la fotografia 
				redimensionarFoto($carpeta."/".$nombreMat,$nombreMat,$carpeta."/",100,100);
				?>
				<script>
				setTimeout("alert('Imagen para Material Cargado Correctamente'); window.close();",500);
				</script>
				<?php
			}
			else{?>
			<script language="javascript" type="text/javascript">
				if(confirm("�El Registro ya Cuenta con Una Imagen Cargada\nDesea Sustituir la Imagen?")){<?php 
					//Eliminamos el archivo
					@unlink($carpeta."/".$nombreMat);
					//Movemos el actual
					move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta."/". $nombreMat); 
					//llamar la funcion que se encarga de reducir el peso de la fotografia 
					redimensionarFoto($carpeta."/".$nombreMat,$nombreMat,$carpeta."/",100,100);
				?>
					setTimeout("alert('La Imagen para Material Fue Sustituida Correctamente'); window.close();",500);
				}
				else{
					window.close();
				}
				</script><?php 
			}
		}
	}
	return $band;	
	}//Fin de la funcion  subirFotos($clave, $nombre)
	
	//Funcion que borra el arreglo de sesion y las fotos en caso de cancelar
	function borrarFotos($carpeta){
		//Borrar los ficheros temporales
		$h=opendir('documentos/'.$carpeta);
		while ($file=readdir($h)){
			if (substr($file,-4)=='.jpg'){
				@unlink("documentos/".$carpeta."/".$file);
			}
		}
	}//Fin de la funcion borrarFotos()
	
	
	//Funcion que borra los archivos que  han sido cargados durante la sesion si esta fue cerrada
	function borrarFotosSesionAlmacen(){
		//Almacenamos el valor de la clave de la requisicion
		$carpeta=$_SESSION["fotosReq"][0]["clave"];
		//Borrar los ficheros temporales
		$h=opendir('alm/documentos/'.$carpeta);
		while ($file=readdir($h)){
			if (substr($file,-4)=='.jpg'){
				@unlink("alm/documentos/".$carpeta."/".$file);
			}
		}
	}//Fin de la funcion borrarFotosSesionAlmacen()
	
	function obtenerDatoTabla($tabla,$busq,$valor,$bd){
		$dat = "N/A"; 
		$con = conecta("$bd");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
?>