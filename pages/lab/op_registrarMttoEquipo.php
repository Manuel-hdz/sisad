<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Nadia Madahí López Hernández                        
	  * Fecha:21/Junio/2011                                      			
	  * Descripción: Este archivo permite generar las funciones para registrar el servicio de mantenimiento a los equipos de laboratorio
	  **/
	  
	  	  			
	//Funcion que se encarga de desplegar los equipos de laboratorio de acuerdo a los parametros de busqueda
	function buscarEquipoLab(){

		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultarNombreEquipo se mostraran los equipos por la Marca del mismo
		if(isset($_POST["sbt_consultarMarcaEquipo"])){ 
					
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM equipo_lab JOIN cronograma_servicios ON no_interno=equipo_lab_no_interno
				 		WHERE marca = '$_POST[cmb_marca]' AND equipo_lab.estado = 1 AND cronograma_servicios.estado = 0";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Equipos de Laboratorio Pendientes de Registrar Mantenimiento de la Marca <em><u>$_POST[cmb_marca]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Servicio de Mantenimiento Programado a los Equipos de la Marca <em><u>$_POST[cmb_marca]</u></em>";
		}	
		
		//Si viene sbt_consultarClaveEquipo la buqueda de los equipos sera por la clave o numero interno del equipo
		if(isset($_POST["sbt_consultarClaveEquipo"])){ 
		
			//Crear sentencia SQL			
			$sql_stm = "SELECT * FROM equipo_lab JOIN cronograma_servicios ON no_interno=equipo_lab_no_interno
						WHERE no_interno = $_POST[txt_claveEquipo] AND equipo_lab.estado!=0 AND cronograma_servicios.estado!=1";

					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Mantenimientos Pendientes de Registrar al Equipo con el Numero Interno <em><u>$_POST[txt_claveEquipo]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Servicio de Mantenimiento Programado al Equipo con Numero Interno <em><u>$_POST[txt_claveEquipo]</u></em>";
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5'  width='100%'>				
				<tr>
					<td colspan='6' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>N° INTERNO</td>
					<td class='nombres_columnas' align='center'>N° DE SERIE</td>					
					<td class='nombres_columnas' align='center'>INSTRUMENTO</td>
					<td class='nombres_columnas' align='center'>MARCA</td>
					<td class='nombres_columnas' align='center'>FECHA MANTENIMIENTO</td>
					<td class='nombres_columnas' align='center'>TIPO DE SERVICIO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'>
							<input type='radio' name='rdb_idServicio' value='$datos[id_servicio]' />
						</td>
						<td class='$nom_clase'>$datos[no_interno]</td>
						<td class='$nom_clase'>$datos[no_serie]</td>
						<td class='$nom_clase'>$datos[nombre]</td>												
						<td class='$nom_clase'>$datos[marca]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_mtto'],1)."</td>					
						<td class='$nom_clase'>$datos[tipo_servicio]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	
	//Funcion que se encarga de  la seleccionar el equipo al cual se le agregara información
	function obtenerDatosEquipo(){
		//Relizar la consulta con el id del equipo seleccionado para poder precargar los datos 
		//Conectar a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");

		//Crear sentencia SQL para obtener los datos de la
		$sql_stm ="SELECT id_servicio, no_interno, marca, no_serie, nombre, tipo_servicio, cronograma_servicios.fecha_mtto FROM equipo_lab JOIN cronograma_servicios ON equipo_lab_no_interno = no_interno  
					WHERE id_servicio = '$_POST[rdb_idServicio]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs))
			return $datos;
		else
			return $datos = array("id_servicio"=>"---","no_interno"=>"---","marca"=>"---","no_serie"=>"---","nombre"=>"---");
	}
	
	
	/*Funcion que guarda los registros del programa de mantenimiento a los equipos de laboratorio*/
	function guardarRegistroMtto(){
		
		include_once("../../includes/func_fechas.php");
	
		//Abrimos la Conexión a la bd de Laboratorio
		$conn = conecta("bd_laboratorio");

		//obtener datos del equipo de la SESSION de arreglo que este definido => datosEquiposLab/datosEquipoAlerta
		if(isset($_SESSION['datosEquiposLab'])){
			$idServicio = $_SESSION['datosEquiposLab']['id_servicio'];
			$no_interno = $_SESSION['datosEquiposLab']['no_interno'];
			$nombre = $_SESSION['datosEquiposLab']['nombre'];
			$tipoServicio = $_SESSION['datosEquiposLab']['tipo_servicio'];
			$fechaMttoProgramada = $_SESSION['datosEquiposLab']['fecha_mtto'];
			
		}
		else{
			$idServicio = $_SESSION['datosEquipoAlerta']['idServicio'];
			$no_interno = $_SESSION['datosEquipoAlerta']['idEquipo'];
			$nombre = $_SESSION['datosEquipoAlerta']['nombre'];
			$tipoServicio = $_SESSION['datosEquipoAlerta']['tipoServicio'];
			$fechaMttoProgramada = $_SESSION['datosEquipoAlerta']['fechaMtto'];
		}
		
		//Esta variables se encarga de verificar si la inserción de datos fue exitosa
		$status = 0;

		//Recorre el arreglo para Guardar el Detalle del Mtto. Realizado dentro de la Bitacora de Mtto
		foreach($_SESSION['datosRegistroMtto'] as $ind => $regMtto) {
		
			//Obtener la información de cada registro existente en la SESSION
			$fechaRegistro = modFecha($regMtto['fechaRegistro'],3);			
			$servicioMtto = strtoupper($regMtto['servicioMtto']);			
			$detalleServicio = strtoupper($regMtto['detalleServicio']);
			$encargadoMtto = strtoupper($regMtto['encargadoMtto']);
	
	
			//Creamos la sentencia SQL para guardar el agregar el Contacto del Aspirante en BD de recursos
			$stm_sql = "INSERT INTO bitacora_mtto (cronograma_servicios_id_servicio, fecha_registro, servicio, detalle_servicio, encargado_mtto) 
						VALUES ('$idServicio', '$fechaRegistro', '$servicioMtto', '$detalleServicio','$encargadoMtto')";
	
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
			
			if(!$rs){
				$status = 1;
				break;
			}
		}// fin de 	foreach($_SESSION['datosRegistroMtto'] as $ind => $regMtto)
		
		
		
		//Verificar que no existena errores en la seccion Anterior para proceder a guardar las imagenes
		if($status==0){ 
			//Cargar la Imagenes del Servicio de Mtto a los Equipos de Laboratorio
			$foto_info_antes = cargarImagen("txt_fotografiaAntes");//Cargar la Imagen de Antes
			$foto_info_despues = cargarImagen("txt_fotografiaDespues");//Cargar la Imagen de Despues
			
			//Crear la Sentencia para Insertar las Imagenes en la BD
			$stm_sql_imgs = "INSERT INTO memoria_fotografica_mtto (cronograma_servicios_id_servicio,foto_antes,mime_antes,foto_despues,mime_despues) 
							VALUES('$idServicio','$foto_info_antes[foto]','$foto_info_antes[type]','$foto_info_despues[foto]','$foto_info_despues[type]')";
			
			//Extrae el contenido de la foto original
			$fp = opendir("documentos/");//Abrir el archivo temporal el modo lectura'r' binaria'b'			
			//Borrar el Directorio indicado, que guarda temporalmente las fotos
			rmdir("documentos/temp");
			closedir($fp);//Cerrar el puntero al archivo abierto	
			
			//Ejecutar la Sentecia SQL
			$rs = mysql_query($stm_sql_imgs);
			
			if(!$rs)
				$status = 1;
		}
						

		
		//Confirmar que la inserción de datos fue realizada con exito.
		if($status==0){ 
			//Actualizar el registro en el Cronograma de Servicios para indicar que el Mtto. Programado fue Realizado
			$stm_sql2 = "UPDATE cronograma_servicios SET estado = 1 WHERE estado = 0 AND id_servicio = '$idServicio' AND fecha_mtto = '$fechaMttoProgramada' 
						AND equipo_lab_no_interno = '$no_interno'";
			
			$rs2 = mysql_query($stm_sql2);

			if($rs2){			
				//liberar los datos del arreglo de sesion
				if(isset($_SESSION['datosRegistroMtto']))
					unset($_SESSION['datosRegistroMtto']);			
				if(isset($_SESSION['datosEquiposLab']))	
					unset($_SESSION['datosEquiposLab']);								
				if(isset($_SESSION['datosEquipoAlerta']))
					unset($_SESSION['datosEquipoAlerta']);									
				
				
				//Guardar la operacion realizada
				registrarOperacion("bd_laboratorio",$nombre,"RegistrarMttoEquipo",$_SESSION['usr_reg']);															
				//Redireccionar a la pagina de exito en cxaso de que el proceso haya trascurrido de forma correcta
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}//Cierre if($rs2)
			else{
				//liberar los datos del arreglo de sesion
				if(isset($_SESSION['datosRegistroMtto']))
					unset($_SESSION['datosRegistroMtto']);			
				if(isset($_SESSION['datosEquiposLab']))	
					unset($_SESSION['datosEquiposLab']);			
				if(isset($_SESSION['datosEquipoAlerta']))
					unset($_SESSION['datosEquipoAlerta']);
				
				
				//Obtener el Error y redireccionar a la Pagina de Error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			
				//Cerrar la conexion con la BD
				mysql_close($conn);
			}			
		}//Cierre if($status==0)
		else{
			//liberar los datos del arreglo de sesion
			if(isset($_SESSION['datosRegistroMtto']))
				unset($_SESSION['datosRegistroMtto']);			
			if(isset($_SESSION['datosEquiposLab']))	
				unset($_SESSION['datosEquiposLab']);			
			if(isset($_SESSION['datosEquipoAlerta']))
				unset($_SESSION['datosEquipoAlerta']);
			
			
			//Obtener el Error y redireccionar a la Pagina de Error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	}//Cierre guardarRegistroMtto()
	
	
	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenada en la BD de Laboratorio
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
			
			//Se Opto por no Redimensionar la Imagen ya que al tartar de hacer esta nueva funcion donde primero se redimensionara y luego se cargara a la BD no se puede proque
			//la funcion REDINMENSIONAR movia el archivo a otro lado antes de cargarloa  al BD.
			
			//Cargar y Redimensionar la Imagen en el Directorio "../lab/documentos/temp"
			$archivoRedimensionado = cargarFotosMtto($nomInputFile);									
			
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
	
	
	//funcion que carga una imagen a un Directorio Temporal en la carpeta de "lab/documentos/temp"
	function cargarFotosMtto($nomInputFile){		
		//Esta variable Indica si la Imagen fue guardada en el directorio indicado
		$estado = 0;
		
		//Crear la variabe que sera la ruta de almacenamiento
		$Ruta="";
		//Variable que Alamcenara la Carpeta donde sera guardada la imagen redimensionada
		$carpeta="";
		
		//Abrir un Gestor de Directorios
		$dir = opendir($Ruta); 
		//verificar si el archivo ha sido almacenado en la carpeta temporal
		if (is_uploaded_file($_FILES[$nomInputFile]['tmp_name'])) { 
			//crear el nombre de la carpeta contenedora de la fotografia cargada
			$carpeta="documentos/temp";
			
			//veririfcar si el nombre de la carpeta exite de lo contrario crearla
			if (!file_exists($carpeta."/"))
				mkdir($carpeta."/", 0777);
						
			//Mover la fotografia de la carpteta temporal a la que le hemos indicado					
			move_uploaded_file($_FILES[$nomInputFile]['tmp_name'], $carpeta."/".$_FILES[$nomInputFile]['name']);
			//llamar la funcion que se encarga de reducir el peso de la fotografia 
			redimensionarFoto($carpeta."/".$_FILES[$nomInputFile]['name'],$_FILES[$nomInputFile]['name'],$carpeta."/",100,100);
		}
				
		return $carpeta;

	}//FIN 	function cargarImagen()
	
	/* Función que no s sirve para mostrar el registro y programacion de los mantenimientos */
	function mostrarRegistroMtto(){
		echo "<table cellpadding='5' width='100%'>      			
			<tr>						
				<td colspan='5' align='center' class='titulo_etiqueta'>Registros de Servicios de Mantenimiento para los Equipos de Laboratorio Programados</td>
			</tr>
			<tr>
				<td class='nombres_columnas' align='center'>N° REGISTRO</td>
        		<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
			    <td class='nombres_columnas' align='center'>TIPO DE SERVICIO</td>								
			    <td class='nombres_columnas' align='center'>DETALLE DEL SERVICIO</td>				
			    <td class='nombres_columnas' align='center'>ENCARGADO MTTO</td>				
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;	
		
		foreach($_SESSION['datosRegistroMtto'] as $ind => $regMtto) {
			//Desplegar el nombre de los Contactos 
			echo "
				<tr>
					<td class='$nom_clase'>$cont</td>
					<td class='$nom_clase'align='center'>$regMtto[fechaRegistro]</td>
					<td class='$nom_clase'align='center'>$regMtto[servicioMtto]</td>					
					<td class='$nom_clase'align='center'>$regMtto[detalleServicio]</td>	
					<td class='$nom_clase'align='center'>$regMtto[encargadoMtto]</td>																								
				</tr>";			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}
	
		
?>	