<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 21/Febrero/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de AgregarEquipo en la BD
	**/

	//Verificar que se haya presionado el boton agregar desde la pagina frm_agregarEquipo.php
	if (isset($_POST["btn_agregar"])){
		agregarEquipo();
	}

	//Esta funcion Agrega el Equipo en la Base de Datos
	function agregarEquipo(){
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");		
		$id_equipo=strtoupper($_POST["txt_clave"]);
		$nom_equipo=strtoupper($_POST["txt_nombre"]);
		$fecha_alta=modFecha($_POST["txt_fecha"],3);
		$marca_modelo=strtoupper($_POST["txt_marcaModelo"]);
		$modelo=strtoupper($_POST["txt_modelo"]);
		$poliza=strtoupper($_POST["txt_poliza"]);
		$num_serie=strtoupper($_POST["txt_serie"]);
		$num_serieOlla=strtoupper($_POST["txt_serieOlla"]);
		$placas=strtoupper($_POST["txt_placa"]);
		$tenencia=strtoupper($_POST["txt_tenencia"]);
		$tar_circ=strtoupper($_POST["txt_tarjetaCirculacion"]);
		$motor=strtoupper($_POST["txt_motor"]);
		$area=strtoupper($_POST["cmb_area"]);
		$familia="";
		//Control de costos
		$cmb_con_cos=$_POST["cmb_con_cos"];
		$cmb_cuenta=$_POST["cmb_cuenta"];
		//Verificamos si viene el combo Activo para preparar la Sentencia SQL
		if (isset($_POST["cmb_familia"]))
			$familia=$_POST["cmb_familia"];
		else
			$familia=strtoupper($_POST["txt_nuevaFamilia"]);
		$fecha_fabrica=modFecha($_POST["txt_fechaFabricacionEquipo"],3);
		$asignado=strtoupper($_POST["txt_asignado"]);
		//El valor por default del Estado del vehiculo es ACTIVO
		$estado="ACTIVO";
		$proveedor=strtoupper($_POST["txt_proveedor"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$metrica=$_POST["cmb_metrica"];
		//Cargar la imagen en el caso de que se haya cargado una y el tipo para ser almacenados en la BD
		if(isset($_FILES["foto"])) 
			$foto_info = cargarImagen("foto"); 
		else 
			$foto_info = array("foto"=>"", "type"=>"");
			
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		
		//Creamos la sentencia SQL para insertar los datos en Equipo
		$stm_sql="INSERT INTO equipos (id_equipo,nom_equipo,fecha_alta,marca_modelo,modelo,poliza,num_serie,num_serie_olla,
		placas,tenencia,tar_circulacion,tipo_motor,area,familia,fecha_fabrica,asignado,estado,proveedor,descripcion,metrica,fotografia,mime,relevancia,id_control_costos,id_cuentas)
		VALUES ('$id_equipo','$nom_equipo','$fecha_alta','$marca_modelo','$modelo','$poliza','$num_serie','$num_serieOlla',
		'$placas','$tenencia','$tar_circ','$motor','$area','$familia','$fecha_fabrica','$asignado','$estado','$proveedor','$descripcion',
		'$metrica','$foto_info[foto]','$foto_info[type]','','$cmb_con_cos','$cmb_cuenta')";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($rs){
			//Abrir la SESSION para registrar la variables
			session_start();
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_mantenimiento","$id_equipo","AgregarEquipoNuevo",$_SESSION['usr_reg']);	
			//Eliminamos la carpeta del servidor
			if($foto_info["foto"]!=""){ 
			//Abrimos el directorio en el cual se encuentra almacenada la foro
				$fp = opendir("documentos/");		
				rmdir("documentos/temp");
				closedir($fp);//Cerrar el puntero al archivo abierto	
			}
			
			/***********************/
			//Agregar el Equipo a la Tabla Acumulado_servicios, siempre y cuando tenga como metrica los Horometros, para manejo de Alertas
			if($metrica=="HOROMETRO")
				agregarEquipoAcumulacionHrs($id_equipo);
			/***********************/
			
			//Si los datos fueron agregados correctamente, se redirecciona a otra pagina que muestra el resultado de los datos agregados
			echo "<meta http-equiv='refresh' content='0;url=frm_equipoAgregado.php?id_eq=$id_equipo'>";
		}
		else{
			//Obtenemos el error que se haya generado
			$error=mysql_error();
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
			
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);
		
	}//Fin de la funcion para registrar el equipo
	
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
			//Cargar y Redimensionar la Imagen en el Directorio "../alm/documentos/temp"
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
	
	//Funcion que agrega el Equipo a la Tabla de Servicios Acumulados
	function agregarEquipoAcumulacionHrs($equipo){
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//No hay registro de horas, ingresar las horas directamente a la BD
		$sql="INSERT INTO acumulado_servicios (equipos_id_equipo,hrs_acum) VALUES ('$equipo',0)";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin de function agregarEquipoAcumulacionHrs($equipo)
	
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
	
	
	//Esta funcion permite registrar la documentación del Equipo en que se este trabajando
	function registrarDocumentosEquipo($id_equipo,$documentos){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		$band=0;
		//Registrar todos los materiales dados de alta en el arreglo $datosEntrada
		foreach ($_SESSION["documentos"] as $ind => $doc){
			//Crear la sentencia para realizar el registro de los datos del detalle de la Entrada de Material
			$stm_sql = "INSERT INTO expediente_equipos (equipos_id_equipo,nom_docto,estatus,ubicacion,nom_archivo)
			VALUES('$id_equipo','$doc[nombre]','$doc[estatus]','$doc[ubicacion]','$doc[archivo]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_entradas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		if ($band==1)
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_mantenimiento","$id_equipo","AgregarDocumentacionEquipo",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	//Desplegar los documentos registrados en el apartado de Agregar Documentos de Equipos
	function mostrarDocumentosReg($documentos){
		echo "<table cellpadding='5' width='590'>";
		echo "<caption><p class='msje_correcto'><strong>Documentos agregados al registro de ".$_POST['txt_clave']."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>DOCUMENTO</td>
        		<td class='nombres_columnas' align='center'>ESTADO</td>
			    <td class='nombres_columnas' align='center'>UBICACI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>ARCHIVO</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($documentos as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {
				switch($key){
					case "nombre":
						echo "<td class='nombres_filas'>$value</td>";
					break;
					case "estatus":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "ubicacion":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "archivo":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarDocumentosReg($documentos)	
	
	//Esta funcion permite subir Archivos que forman parte de la documentacion de un Vehiculo
	function subirArchivos($clave){
		if((substr($_FILES['file_documento']['type'],0,5) != 'image')&& (substr($_FILES['file_documento']['type'],12,3) != 'pdf')&&(substr($_FILES['file_documento']['type'],12,6) != 'msword')){
			?>
				<script>
					setTimeout("alert('El Formato del Archivo no es Válido. Sólo se Permiten PDF, DOC e Imágenes');",500);
				</script>
			<?php
			exit('');
		}
		else{	
			$Ruta='';
			$carpeta='documentos/'.$clave;
   			$dir = opendir($Ruta); 
		   	if (is_uploaded_file($_FILES['file_documento']['tmp_name'])) { 
				if (!file_exists($carpeta."/"))
					mkdir($carpeta."/", 0777); 
				if (!file_exists($carpeta."/".$_FILES['file_documento']['name'])){
		 	       	move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta."/".$_FILES['file_documento']['name']); 
    	    	 	echo "<br>Fichero subido: ".$_FILES['file_documento']['name']; 
					?>
					<script>
						setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
					</script>
					<?php
					$flag=1;
				}else{
					?>
					<script>
						setTimeout("alert('El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe');",500);
					</script>
					<?php
				}
			}
		}
	}//Fin de la funcion subirArchivos($clave)
	
	//Esta funcion Elimina los archivos que forman parte de la documentacion de un vehiculo, siempre y cuando el proceso se haya cancelado o haya expirado la sesion sin haber terminado de Guardar
	function borrarArchivos($carpeta){
		foreach ($_SESSION["docTemporal"] as $ind => $doc){
			//Variable que obtiene el nombre del Archivo
			$nombreArchivo=$doc["nom_archivo"];
			//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
			@unlink("documentos/".$carpeta."/".$nombreArchivo);
		}
	}//Fin de la funcion borrarArchivos()
	
	//Esta funcion Elimina los archivos que forman parte de la documentacion de un vehiculo, siempre y cuando el proceso se haya cancelado o haya expirado la sesion sin haber terminado de Guardar
	function borrarArchivosExtremo(){
		foreach ($_SESSION["docTemporal"] as $ind => $doc){
			//Variable que obtiene el nombre del Archivo
			$nombreArchivo=$doc["nom_archivo"];
			//Variable que indica la carpeta donde estan los archivos a eliminar
			$carpeta=$doc["carpeta"];
			//Pasamos la ruta completa, ya que esta funcion borra archivos desde la pagina salir.php
			@unlink("man/documentos/".$carpeta."/".$nombreArchivo);
		}
	}//Fin de la funcion borrarArchivos()
	
	
	//Funcion que se encarga de desplegar los bonos agregados
	function mostrarRefaccionesReg(){
		echo "<table cellpadding='5' width='700'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center' width='10%'>N&Uacute;MERO</td>
				<td class='nombres_columnas' align='center' width='30%'>NOMBRE DE REFACCI&Oacute;N</td>
			    <td class='nombres_columnas' align='center' width='60%'>DESCRIPCI&Oacute;N</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;

		foreach ($_SESSION['refacciones'] as $ind => $info) {
			echo "<tr>
					<td class='$nom_clase' width='10%' align='center'>".($cont)."</td>";
			foreach ($info as $key => $value) {
				switch($key){
					case "nom_refaccion":
						echo "<td class='$nom_clase' width='30%'>$value</td>";
					break;
					case "descripcion":
						echo "<td class='$nom_clase' align='center' width='60%'>$value</td>";
					break;	
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarRefaccionesReg()	

	//Esta funcion guarda las refacciones agregadas al equipo 
	function agregarRefacciones(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_mantenimiento");

		//Recorrer el arreglo que contiene las refacciones agregadas al equipo
		foreach($_SESSION['refacciones'] as $ind => $concepto){
			//Crear la Sentencia SQL para Alamcenar las refacciones agregadas del equipo
			$stm_sql= "INSERT INTO refacciones (equipos_id_equipo, nombre, descripcion)
			VALUES ('$concepto[id_equipo]', '$concepto[nom_refaccion]', '$concepto[descripcion]')";
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				$band=1;
			}
			if (!$rs){
				$band=0;
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['refacciones']);
			}
			if($band==1){
				//Guardar el registro de movimientos
				registrarOperacion("bd_mantenimiento",$concepto['id_equipo'],"AgregarRefacciones",$_SESSION['usr_reg']);
				$conn = conecta("bd_mantenimiento");																			
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}		
		}// Fin foreach($_SESSION['refacciones'] as $ind => $concepto)
		//liberar los datos del arreglo de sesion
		unset ($_SESSION['refacciones']);
	}//Fin de la function agregarRefacciones()
	?>