<?php
	$cmb_estado = $_POST['cmb_estado'];
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 30/Septiembre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de AgregarMaterial en la BD
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaiones generales en la BD
			3. Archivo de operaciones de Entrada de Material*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include_once("op_entradaMaterial.php");			
	/**   C�digo en: pages\alm\op_agregarMaterial.php                                   
      **/
	//Agregar el registro del material en la tabla materiales
	if(isset($_POST['txt_clave'])){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
										
		//Obtener la L�nea del Art�culo proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_lineaArticulo']))
			$lineaArticulo = $cmb_lineaArticulo;
		else
			$lineaArticulo = $hdn_lineaArticulo;			
		//Obtener la Unidad de Medida proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_unidadMedida']))
			$unidadMedida = $cmb_unidadMedida;
		else
			$unidadMedida = $hdn_unidadMedida;			
		//Obtener el Grupo proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_grupo']))
			$grupo = $cmb_grupo;
		else
			$grupo = $hdn_grupo;
			
		
		//Convertir los caracteres de los campos de texto en May�sculas
		$txt_clave = strtoupper($txt_clave); $txt_nombre = strtoupper($txt_nombre); $unidadMedida=strtoupper($unidadMedida); 
		$lineaArticulo = strtoupper($lineaArticulo); $grupo = strtoupper($grupo); $cmb_proveedor = strtoupper($cmb_proveedor); 
		$txt_ubicacion = strtoupper($txt_ubicacion); $txa_comentarios = strtoupper($txa_comentarios); $txt_unidadDespacho = strtoupper($txt_unidadDespacho);
		$txa_aplicaciones = strtoupper($txa_aplicaciones); $txt_moneda = strtoupper($txt_moneda); 
		
		//Cargar la imagen en el caso de que se haya cargado una y el tipo para ser almacenados en la BD 
		if($foto!="") 
			$foto_info = cargarImagen("foto"); 
		else 
			$foto_info = array("foto"=>"", "type"=>"");
		
		
		//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
		$txt_costoUnidad=str_replace(",","",$txt_costoUnidad);
		$txt_nombre = mysql_real_escape_string($txt_nombre);
		
		//FUNCION DE PRUEBA
		if (isset($_POST["hdn_continuar"]) && $_POST["hdn_continuar"]=="si")
			$stm_sql = "UPDATE materiales SET existencia=existencia+$txt_cantidad WHERE id_material='$txt_clave'";
		else{
			/*******CAMBIE ESTA CONSULTA PARA EN VEZ DE RECIBIR LA CANTIDAD DE ENTRADA, RECIBA 0, POR CUESTIONES SUMATORIA EN AGREGACIONES********/
			//Crear la sentencia para realizar el registro del nuevo material en la BD de Almacen en la tabla de materiales
			$stm_sql = "INSERT INTO materiales (id_material,nom_material,existencia,nivel_minimo,nivel_maximo,re_orden,costo_unidad,linea_articulo,grupo,relevancia,proveedor,fecha_alta,
						ubicacion,comentarios,fotografia,mime,codigo_barras,aplicacion,moneda,estatus)
						VALUES(\"$txt_clave\",\"$txt_nombre\",0,$txt_nivelMinimo,$txt_nivelMaximo,$txt_puntoReorden,$txt_costoUnidad,\"$lineaArticulo\",\"$grupo\",\"$cmb_relevancia\",\"$cmb_proveedor\",\"$hdn_fecha\",
						\"$txt_ubicacion\",\"$txa_comentarios\",\"$foto_info[foto]\",\"$foto_info[type]\",\"$txt_codigoBarras\",\"$txa_aplicaciones\",\"$txt_moneda\",\"$cmb_estado\")";
		}
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);
		if (isset($_POST["hdn_continuar"]) && $_POST["hdn_continuar"]!="si"){
			if($foto_info["foto"]!=""){ 
				//Extrae el contenido de la foto original
				$fp = opendir("documentos/");//Abrir el archivo temporal el modo lectura'r' binaria'b'
				rmdir("documentos/temp");
				closedir($fp);//Cerrar el puntero al archivo abierto	
			}
		}										
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			if (isset($_POST["hdn_continuar"]) && $_POST["hdn_continuar"]!="si"){
				//Guardar los datos de la unidad de medida, una vez que los datos del material se han guardado correctamente
				$stm_sql = "INSERT INTO unidad_medida (materiales_id_material,unidad_medida,factor_conv,unidad_despacho) VALUES('$txt_clave','$unidadMedida',$txt_factor,'$txt_unidadDespacho')";
				$rs = mysql_query($stm_sql);
			}
			else{
				$rs=true;
			}
			//Confirmar que la Unidad de Medida se haya guardado correctamente
			if($rs){														
				//Abrir la SESSION para registrar la variables
				session_start();
				
				if (isset($_POST["hdn_continuar"]) && $_POST["hdn_continuar"]!="si"){
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_almacen","$txt_clave","AgregarMaterial",$_SESSION['usr_reg']);
				}
				else{
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_almacen","$txt_clave","ModExistencia",$_SESSION['usr_reg']);
				}
				
				//Guardar las claves de los materiales que estan siendo registradas para quitarlas de la BD, en caso de que el proceso de registro no se lleve a cabo con exito
				if(isset($_SESSION['clavesRegistradasMat'])){
					$_SESSION['clavesRegistradasMat'][] = $txt_clave;
					if (isset($_POST["hdn_continuar"]) && $_POST["hdn_continuar"]=="si"){
						$_SESSION["clavesModificadasExistencia"][]=$txt_clave;
						$_SESSION["clavesModificadasExistenciaCantidad"][$txt_clave]=$txt_cantidad;
					}
				}
				
				//Complementar los datos de los materiales al arreglo de datosEntrada para registrarlos en el caso de que provengan de una Orden de Compra o una Requisicion
				if(isset($_SESSION['datosEntrada'])){										
					//Obtener el numero del Nuevo Material que se esta registrando
					$num = $_SESSION['infoEntrada']['contador'];
					if (!isset($_GET["ped"])){
						if(!isset($_SESSION["datosEntradaNR"]))
							$material = $_SESSION['datosEntrada'][$num-1];
					}
					else
						$material = $_SESSION['datosEntrada'][$_GET["pos"]];//
					
					if (isset($_POST["hdn_continuar"]) && $_POST["hdn_continuar"]=="si"){
						$existencia=obtenerDato("bd_almacen","materiales","existencia","id_material",$txt_clave);
						//Guardar la clave, la existencia y la unidad de medida del material agregado
						$material['clave'] = $txt_clave;
						$material['existencia'] = $existencia-$txt_cantidad;
						$material['unidad'] = $unidadMedida;
						$material['tipoMonede']=$txt_moneda;
					}
					else{
						if(!isset($_SESSION["datosEntradaNR"])){
							//Guardar la clave, la existencia y la unidad de medida del material agregado
							$material['clave'] = $txt_clave;
							$material['existencia'] = 0;
							$material['unidad'] = $unidadMedida;
							$material['tipoMonede']=$txt_moneda;
						}
					}
					
					if (!isset($_GET["ped"])){
						if(isset($_SESSION["datosEntradaNR"])){
							$existencia=$txt_cantidad;
							$material['clave'] = $txt_clave;
							$material['nombre']=$txt_nombre;
							$material['unidad'] = $unidadMedida;
							$existente=obtenerDato("bd_almacen","materiales","existencia","id_material",$txt_clave);
							if($existente=="")
								$material['existencia'] = 0;
							else
								$material['existencia'] = abs($existencia-$existente);
							$material['cantEntrada']=$existencia;
							$material['costoUnidad']=$txt_costoUnidad;
							$material['tipoMonede']=$txt_moneda;
							$material['costoTotal']=($existencia*$txt_costoUnidad);
							//Guardar los datos nuevamente en la SESSION
							$_SESSION['datosEntrada'][] = $material;
						}
						else
							//Guardar los datos nuevamente en la SESSION
							$_SESSION['datosEntrada'][$num-1] = $material;
					}
					else
						//Guardar los datos nuevamente en la SESSION
						$_SESSION['datosEntrada'][$_GET["pos"]] = $material;//<-------AQUI HAY QUE CHECAR QUE ROLLO CON EL POLLO

					//Verificar si todos los nuevos materiales han sido agregados al Catalogo de Almacen
					if($_SESSION['infoEntrada']['contador']==$_SESSION['infoEntrada']['cantRegistros']){
						//Quitar de la SESSION el arreglo de infoEntrada
						unset($_SESSION['infoEntrada']);
						//Enviar a la pagina para solicitar la Informacion complementaria de la Entrada del Nuevo Material
						header("Location: frm_entradaMaterial3A.php?prov=$cmb_proveedor");						
					}
					else{
						//Incrementar el contador para saber cuantos Materiales se han registrado hasta el momento
						$_SESSION['infoEntrada']['contador'] += 1;
						if (!isset($_GET["ped"]))
							//Enviar a la pagina de Agregar Material para seguir registrando los materiales faltantes
							header("Location: frm_agregarMaterial.php");
						else
							//Enviar a la pagina de Agregar Material para seguir registrando los materiales faltantes
							header("Location: frm_agregarMaterial.php?ped");
					}
				}				
				else{
					//Llenar el arreglo con los datos de la entrada del nuevo material agregado al Catalogo de Alamacen
					$datosEntrada = array(array("clave"=>$txt_clave, "nombre"=>$txt_nombre, "unidad"=>$unidadMedida, "existencia"=>$txt_cantidad, 
					"cantEntrada"=>$txt_cantidad,"costoUnidad"=>$txt_costoUnidad, "costoTotal"=>($txt_cantidad*$txt_costoUnidad), "tipoMoneda"=>$txt_moneda));
					//Crear el ID de la entrada de material
					$_SESSION['id_entrada'] = obtenerIdEntrada();
					//Guardar el arreglo datosEntrada en una variable de Sesion que se enviar� al formulario para registrar la entrada de material
					$_SESSION['datosEntrada']=$datosEntrada;
					//Definir el Origen de la Orden de Compra
					$_SESSION['origen'] = "Compra Directa";	
					$_SESSION['no_origen'] = "N/A";
					
					
					//Guardar la clave del material que esta siendo registrada para quitarla de la BD, en caso de que el proceso de registro no se lleve a cabo con exito
					if(!isset($_SESSION['procesoRegistroMat'])){
						$_SESSION['procesoRegistroMat'] = "NoTerminado";			
						$_SESSION['clavesRegistradasMat'] = array(0=>$txt_clave);
					}
					
					//Enviar a la pagina para solicitar la Informacion complementaria de la Entrada del Nuevo Material
					header("Location: frm_entradaMaterial3A.php?prov=$cmb_proveedor");
				}
			}
			else{
				//Redireccionar a una pagina de error en el caso de que no se haya podido guardar la Unidad de Medida del Nuevo Mateial
				$error = mysql_error();
				header("Location: error.php?err=$error");
			}				
		}
		else{
			//Redireccionar a una pagina de error en el caso de que no se haya guardado el Nuevo Material
			if(mysql_errno()=="1062")
				$error="La clave <em><u>$txt_clave</u></em> ya esta asignada a otro Material";
			else
				$error = mysql_error();
			
			header("Location: error.php?err=$error");
		}
		//La Conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen","$txt_clave","agregar",$_SESSION['usr_reg']);
	}

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
		
		//Verificamos si el archivo es una imagen v�lida y que el tama�o de la misma no exceda los 10,000 Kb 10,240,000 Bytes
		if(in_array($type, $mimetypes) && $size<10240000){	
			//Cargar y Redimensionar la Imagen en el Directorio "../alm/documentos/temp"
			$archivoRedimensionado = cargarFotosEmp($nomInputFile);									
			//Extrae el contenido de la foto original
			$fp = fopen("documentos/temp"."/".$name, "rb");//Abrir el archivo temporal el modo lectura'r' binaria'b'
			$tfoto = fread($fp, filesize("documentos/temp"."/".$name));//Leer el archivo completo limitando la lectura al tama�o del archivo
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
?>