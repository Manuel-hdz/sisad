<?php
	/**
	  * Nombre del Módulo: Producción                                               
	  * Nombre Programador: Miguel Angel Garay Castro                          
	  * Fecha: 19/07/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de AgregarMaterial en la BD
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaiones generales en la BD
			3. Archivo de operaciones de Entrada de Material*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			//include_once("op_entradaMaterial.php");			
	/**   Código en: pages\ger\op_agregarMaterial.php                                   
      **/
	  
	//Agregar el registro del material en la tabla materiales
	if(isset($_POST['txt_clave'])){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");
					 					
		//Obtener la Línea del Artículo proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_lineaArticulo']))
			$lineaArticulo = $cmb_lineaArticulo;
		else
			$lineaArticulo = $hdn_lineaArticulo;			
		//Obtener la Unidad de Medida proveniente del ComboBox o del elemento Hidden
		if(isset($_POST['cmb_unidadMedida']))
			$unidadMedida = $cmb_unidadMedida;
		else
			$unidadMedida = $hdn_unidadMedida;			
			
		
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_clave = strtoupper($txt_clave); $txt_nombre = strtoupper($txt_nombre); $unidadMedida=strtoupper($unidadMedida); 
		$lineaArticulo = strtoupper($lineaArticulo); $cmb_proveedor = strtoupper($cmb_proveedor); $txt_ubicacion = strtoupper($txt_ubicacion);
		$txa_comentarios = strtoupper($txa_comentarios); $txt_unidadDespacho = strtoupper($txt_unidadDespacho);
			
		//Cargar la imagen en el caso de que se haya cargado una y el tipo para ser almacenados en la BD
		if($foto!="") 
			$foto_info = cargarImagen("foto"); 
		else 
			$foto_info = array("foto"=>"", "type"=>"");
		
		
		//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
		$txt_costoUnidad=str_replace(",","",$txt_costoUnidad);
						
		/*******CAMBIE ESTA CONSULTA PARA EN VEZ DE RECIBIR LA CANTIDAD DE ENTRADA, RECIBA 0, POR CUESTIONES SUMATORIA EN AGREGACIONES********/
		//Crear la sentencia para realizar el registro del nuevo material en la BD de Almacen en la tabla de materiales
		$stm_sql = "INSERT INTO materiales (id_material,nom_material,existencia,nivel_minimo,nivel_maximo,re_orden,costo_unidad,linea_articulo,grupo, relevancia,proveedor,fecha_alta,ubicacion,comentarios,fotografia,mime)
					VALUES('$txt_clave','$txt_nombre','$txt_cantidad',$txt_nivelMinimo,$txt_nivelMaximo,$txt_puntoReorden,$txt_costoUnidad,'$lineaArticulo','$txt_grupo','$cmb_relevancia','$cmb_proveedor','$hdn_fecha','$txt_ubicacion','$txa_comentarios',
					'$foto_info[foto]','$foto_info[type]')";						
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);	
		if($foto_info["foto"]!=""){ 
			//Extrae el contenido de la foto original
			$fp = opendir("documentos/");//Abrir el archivo temporal el modo lectura'r' binaria'b'
			rmdir("documentos/temp");
			closedir($fp);//Cerrar el puntero al archivo abierto	
		}
										
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			//Guardar los datos de la unidad de medida, una vez que los datos del material se han guardado correctamente
			$stm_sql = "INSERT INTO unidad_medida (materiales_id_material,unidad_medida,factor_conv,unidad_despacho) VALUES('$txt_clave','$unidadMedida',$txt_factor,'$txt_unidadDespacho')";
			$rs = mysql_query($stm_sql);
			//Confirmar que la Unidad de Medida se haya guardado correctamente
			if($rs){														
				//Abrir la SESSION para registrar la variables
				session_start();
				
				//Registrar la Operacion en la Bitácora de Movimientos
				registrarOperacion("bd_almacen","$txt_clave","AgregarMat",$_SESSION['usr_reg']);
				//Realizar la conexion a la BD de Gerencia
				registrarOperacion("bd_gerencia","$txt_clave","AgregarMat",$_SESSION['usr_reg']);
				//Guardar las claves de los materiales que estan siendo registradas para quitarlas de la BD, en caso de que el proceso de registro no se lleve a cabo con exito
				if(isset($_SESSION['clavesRegistradasMat']))
					$_SESSION['clavesRegistradasMat'][] = $txt_clave;
						echo "<meta http-equiv='refresh' content='0;url=exito.php'>";

					}
					//Guardar la clave del material que esta siendo registrada para quitarla de la BD, en caso de que el proceso de registro no se lleve a cabo con exito
					if(!isset($_SESSION['procesoRegistroMat'])){
						$_SESSION['procesoRegistroMat'] = "NoTerminado";			
						$_SESSION['clavesRegistradasMat'] = array(0=>$txt_clave);			
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
	

?>