<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:05/Febrero/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de donde se Generan los Tipos de Permisos
	**/
/*
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////SECCION PARA LA PAGINA DE PERMISOS PELIGROSOS-//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

//Verificamos si Viene el boton GENERAR Permiso
if(isset($_POST["sbt_guardar"])){
	registrarPermisoPeligroso();
}


//Verificamos si Viene el boton GENERAR Permiso
if(isset($_POST["sbt_generar"])){
	registrarPermisoFlama();
}

//Verificamos si Viene el boton GENERAR Permiso ntro de la seleccion de permisos de la pagina en la cual no s encontramos
if(isset($_POST["sbt_continuar"])){
	registrarPermisoAltura();
}
	
	
	//Funcion para guardar la informacion del Permiso
	function registrarPermisoPeligroso(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de Seguridad
		$conn = conecta("bd_seguridad");		
		//Declarar las variables que seran utilizadas en el Formulario de Registrar Permiso Peligoroso						
		$idPermiso = strtoupper($_POST['txt_tipoPermiso']);
		$idPermisoPel = strtoupper($_POST['txt_idPermisoPel']);
		
		$nomSol = strtoupper($_POST['txt_nomSolicitante']);
		$nomSup = strtoupper($_POST['txt_nomSupervisor']);
		$nomResp = strtoupper($_POST['txt_nomResponsable']);
		$nomCont = strtoupper($_POST['txt_nomContratista']);
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$trabEspecifico = strtoupper($_POST['txa_trabEspecifico']);
		$encargadoTrab = strtoupper($_POST['txt_encargadoTrab']);
		$funResp = strtoupper($_POST['txt_funResponsable']);
		$supervisor = strtoupper($_POST['txt_supervisor']);
		$desTrab = strtoupper($_POST['txa_desTrabajo']);
		$trabRealizar = strtoupper($_POST['txa_trabRealizar']);
		$horaIni = ($_POST['txt_horaIni']);
		$horaFin = ($_POST['txt_horaFin']);	
		$merIni = ($_POST['cmb_meridiano1']);
		$merFin = ($_POST['cmb_meridiano2']);
		$operador = strtoupper($_POST['txt_operador']);
		$supObra = strtoupper($_POST['txt_supervisorObra']);
		$aceptacion = strtoupper($_POST['txt_aceptacion']);
		$tipoTrabajo = ($_POST['rdb_tipoTrabajo']);
		//$tipoTrabajo2 = ($_POST['hdn_tipoTrabajo']);
		
				
		//Obtener el ID del tipo de Permiso de ACuerdo a la selecicon de opciones.
		$idPermisoPel = obtenerIdPermisoPeligroso();

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	
		//Crear la Sentencia SQL para Alamcenar los datos del Pemriso
		$stm_sql= "INSERT INTO permisos_trabajos (id_permiso_trab, permisos_secundarios_id_permiso_secundario, folio_permiso, tipo_permiso, lugar_trabajo,
			riesgos_trabajo, nom_solicitante, nom_supervisor, nom_responsable, nom_contratista, descripcion_trabajo, trabajo_realizar, fecha_ini, fecha_fin, 
			horario_ini, meridiano_ini, horario_fin, meridiano_fin, trabajo_especifico, firma_responsable, funcionario_res, supervisor, supervisor_obra, 
			operador, aceptacion, fecha_expiracion, hora_expiracion)
		VALUES 
			('$idPermisoPel', '$tipoTrabajo ', 'N/A', '$idPermiso', 'N/A', 'N/A', '$nomSol', '$nomSup', '$nomResp', '$nomCont', '$desTrab',  '$trabRealizar',
			 '$fechaIni', '$fechaFin', '$horaIni', '$merIni', '$horaFin', '$merFin', '$trabEspecifico', '$encargadoTrab', '$funResp', '$supervisor', 
			 '$supObra', '$operador', '$aceptacion', '0000-00-00', 'N/A')";
			     
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
			if ($rs){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPermisoPel,"PermisoPeligroso",$_SESSION['usr_reg']);	
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
			echo "<meta http-equiv='refresh' content='0;url=frm_generacionPermisos.php?id_perPel=$idPermisoPel'>";
		}
		else{
			$error = mysql_error();
			//echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarPermisoPeligroso()	
	 


	//Esta funcion genera la Clave del Pemiso de Trabajos Peligrosos
	function obtenerIdPermisoPeligroso(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las  letras en la Id del tipo de Permiso que sa va registrar.
		$id_cadena = "PTP";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener los registros de los permisos del mes y año en curso 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de permiso registrado
		$stm_sql = "SELECT COUNT(id_permiso_trab) AS cant FROM permisos_trabajos WHERE id_permiso_trab LIKE 'PTP$mes$anio%'";
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
	}//Fin de la Funcion obtenerIdPermisoPeligroso()			

	
/*
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////SECCION PARA LA PAGINA DE PERMISOS CON FLAMA ABIERTA///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

		//Esta funcion genera la Clave del Pemiso de Flama Abierta
	function obtenerIdPermisoFlama(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las  letras en la Id del tipo de Pemriso que sa va registrar.
		$id_cadena = "PTF";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener los registros de los permisos del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de permiso registrados
		$stm_sql = "SELECT COUNT(id_permiso_trab) AS cant FROM permisos_trabajos WHERE id_permiso_trab LIKE 'PTF$mes$anio%'";
		$rs_idFlama = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs_idFlama)){
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
	}//Fin de la Funcion obtenerIdPermisoFlama()	
	
	
	
	function registrarPermisoFlama(){
		//Extraer los datos de la sesion
		$conn = conecta('bd_seguridad');

		//Declarar las variables que seran utilizadas en el Formulario de Registrar Permiso Flama						
		$txt_tipoPermiso = "";
		
		//Recuperar los datos de la SESSION Para verificar el Tipo de Permiso que se esta registrando.
		if(isset($_SESSION['seleccionPermisos'])){
			$idTipoPermiso =  $_SESSION['seleccionPermisos']['txt_tipoPermiso'];
		}
		
		//Recuperar la informacion del post
		$idPermiso = strtoupper($_POST['txt_tipoPermiso']);
		
		//$idPerFlama =($_POST['txt_idPermisoFlama']);
		$folioPerFlama = ($_POST['txt_folioPermiso']);
		$encargadoTrab = strtoupper($_POST['txt_encargadoTrab']);
		$trabEspecifico = strtoupper($_POST['txa_trabEspecifico']);
		$supObraContratista = strtoupper($_POST['txt_supObra']);
		$fechaExp = modFecha($_POST['txt_fechaExp'],3);
		$fechaReg = modFecha($_POST['txt_fechaReg'],3);
		$EmpContratista = strtoupper($_POST['txt_nomEmpContratista']);
		$hrsExpiracion = ($_POST['txt_horaIni']);
		$funcionarioRes = strtoupper($_POST['txt_funResponsable']);
		$lugTrab = strtoupper($_POST['txt_lugarTrabajo']);
		
		//Obtener el ID del tipo de Permiso de ACuerdo a la selecicon de opciones.
		$idPerFlama = obtenerIdPermisoFlama();
		//$tipoTrabajo = ($_POST['rdb_tipoPermiso']);

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	
			//Cargar la Imagenes que evidencien el tipo de permiso
			$foto_info_1 = cargarImagen("txt_foto1");//Cargar la Imagen 1
			$foto_info_2 = cargarImagen("txt_foto2");//Cargar la Imagen 2
	
		$stm_sql = "INSERT INTO permisos_trabajos (id_permiso_trab, permisos_secundarios_id_permiso_secundario,
			folio_permiso, lugar_trabajo, riesgos_trabajo, tipo_permiso, nom_solicitante, nom_supervisor, nom_responsable, nom_contratista, 
			descripcion_trabajo, trabajo_realizar, fecha_ini, fecha_fin, horario_ini, meridiano_ini, horario_fin, meridiano_fin, 
			trabajo_especifico, firma_responsable, funcionario_res, supervisor, supervisor_obra, operador, 
			aceptacion, fecha_expiracion, hora_expiracion, evidencia1, mime1, evidencia2, mime2)
		VALUES 
			('$idPerFlama', 'N/A', '$folioPerFlama', '$lugTrab', 'N/A', '$idPermiso', 'N/A', 'N/A', 'N/A', '$EmpContratista', 'N/A', 'N/A', 
			'$fechaReg', '0000-00-00', 'N/A', 'N/A', 'N/A', 'N/A', '$trabEspecifico',
			'$encargadoTrab', '$funcionarioRes', 'N/A', '$supObraContratista', 'N/A', 'N/A', '$fechaExp', '$hrsExpiracion', 
			'$foto_info_1[foto]', '$foto_info_1[type]', '$foto_info_2[foto]', '$foto_info_2[type]')";
					
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPerFlama,"PermisoFlamaAbierta",$_SESSION['usr_reg']);																	
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			//echo "<meta http-equiv='refresh' content='0;url=frm_generacionPermisos.php?id_perPel=$idPermisoPel'>";
			
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarPermisoFlama()	
	
	
	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenada en la BD de Seguridad
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
			
			//Cargar y Redimensionar la Imagen en el Directorio "../seg/documentos/temp"
			$archivoRedimensionado = cargarFotosPerFlama($nomInputFile);									
			
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
	
	//funcion que carga una imagen a un Directorio Temporal en la carpeta de "seg/documentos/temp"
	function cargarFotosPerFlama($nomInputFile){		
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
	
	
	
/*
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////SECCION PARA LA PAGINA DE PERMISOS DE ALTURA///////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/
	
	//Esta funcion genera la Clave del Pemiso de Trabajos Alturas
	function obtenerIdPermisoAlturas(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las  letras en la Id del tipo de Permiso que sa va registrar.
		$id_cadena = "PTA";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener los registros de los permisos del mes y año en curso 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de permiso registrado
		$stm_sql = "SELECT COUNT(id_permiso_trab) AS cant FROM permisos_trabajos WHERE id_permiso_trab LIKE 'PTA$mes$anio%'";
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
	}//Fin de la Funcion obtenerIdPermisoPeligroso()			


	function registrarPermisoAltura(){
		//Extraer los datos de la sesion
		$conn = conecta('bd_seguridad');
		
		//Recuperar la informacion del post
		$idPermiso = strtoupper($_POST['txt_tipoPermiso']);
		$idPerAltura = strtoupper($_POST['txt_idPermisoAlt']);
		$fechaReg = modFecha($_POST['txt_fechaReg'],3);		
		$nomTrabajador = strtoupper($_POST['txt_nomTrabajador']);
		$autorizaTrab = strtoupper($_POST['txt_nomAutoriza']);
		$areaOperativa = strtoupper($_POST['txt_liderOper']);
		$trabRealizar = strtoupper($_POST['txa_trabRealizar']);
		//$tipoPermiso = ($_POST['txt_tipoPermiso']);
		$lugarTrab = strtoupper($_POST['txt_lugar']);
		$descripcionTrab = strtoupper($_POST['txa_desTrabajo']);
		$riesgosTrab = strtoupper($_POST['txa_riesgosTrab']);
		
		//Obtener el ID del tipo de Permiso de ACuerdo a la selecicon de opciones.
		$idPerAltura = obtenerIdPermisoAlturas();

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
			$stm_sqlPA = "INSERT INTO permisos_trabajos (id_permiso_trab, permisos_secundarios_id_permiso_secundario, folio_permiso, tipo_permiso, lugar_trabajo, 
					riesgos_trabajo, nom_solicitante, nom_supervisor, nom_responsable, nom_contratista, descripcion_trabajo, trabajo_realizar, fecha_ini, 
					fecha_fin, horario_ini, meridiano_ini, horario_fin, meridiano_fin, trabajo_especifico, firma_responsable, funcionario_res, supervisor, 
					supervisor_obra, operador, aceptacion, fecha_expiracion, hora_expiracion)
				VALUES 
					('$idPerAltura', 'CDS005', 'N/A', '$idPermiso', '$lugarTrab', '$riesgosTrab', '$nomTrabajador', '$areaOperativa', 
					'$autorizaTrab', 'N/A', '$descripcionTrab',  '$trabRealizar',
					 '$fechaReg', 'N/A', '00:00:00', 'N/A', '00:00:00', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 
					 'N/A', 'N/A', 'N/A', '0000-00-00', 'N/A')";
		
		//Ejecutar laS Sentencias previamente Creadas
		$rs_PA=mysql_query($stm_sqlPA);
		
		//Verificar Resultado
		if ($rs_PA){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPerAltura,"PermisoAlturas",$_SESSION['usr_reg']);																	
			//echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			echo "<meta http-equiv='refresh' content='0;url=frm_generacionPermisos2.php?id_perAlt=$idPerAltura'>";	
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarPermisoAltura()	


	//Función que permite mostrar las Condiciones de Seguridad que se deben de Registrar para el Permiso de Alturas
	function mostrarCondicionesSeguridad(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT nom_permiso, num_actividad, actividad, permisos_secundarios_id_permiso_secundario FROM pasos_permiso JOIN permisos_secundarios
							ON id_permiso_secundario = permisos_secundarios_id_permiso_secundario WHERE nom_permiso = 'CONDICIONES DE SEGURIDAD'";
									
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>";
			echo "	
					<tr>
						<td rowspan='3'class='nombres_columnas' align='center'>N° CONDICIÓN</td>
						<td colspan='2' class='nombres_columnas' align='center'>REVISAR</td>
						<td class='nombres_columnas' align='center'>NOTA: LA PERSONA DEBE PESAR UN M&Iacute;NIMO DE 55 KG. Y UN M&Aacute;XIMO 140</td>
						<td rowspan ='3' class='nombres_columnas' align='center'>BORRAR</td>

					</tr>			
					<tr>
						<td colspan='2' class='nombres_columnas' align='center'>¿SE CUMPLEN?</td>
						<td rowspan='2' class='nombres_columnas' align='center'>CONDICIONES DE SEGURIDAD</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SI</td>
						<td class='nombres_columnas' align='center'>NO</td>						

					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								$cont
								
							</td>
							<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_$cont' name='rdb_$cont' value='SI' />
							</td>	
							<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_$cont' name='rdb_$cont' value='NO' />
							</td>										
							<td class='$nom_clase' align='left'>$datos[actividad]</td>";?>
							<td class="<?php echo $nom_clase;?>" align="center"	>
								<img src="../../images/borrar.png" width="30" height="25" border="0" title="Borrar Registro" 
								onclick="location.href='verComplementoPermisoAlturas.php?noAct=<?php 
								echo $datos['num_actividad'];?>&clavePermiso=<?php echo $datos['permisos_secundarios_id_permiso_secundario'];?>'"/>
							</td><?php
						echo "</tr>";			
						$clave = $datos['permisos_secundarios_id_permiso_secundario'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			
			}while($datos=mysql_fetch_array($rs));?> 	
			<input type="hidden" name="hdn_totalCondiciones" id="hdn_totalCondiciones" value="<?php echo $cont-1; ?>"/>
			<input type="hidden" name="hdn_clave" id="hdn_clave" value="<?php echo $clave; ?>"/><?php
			echo "</table>";	
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> Agregrar Condiciones de Seguridad </label>";
			 }?><?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
							
	
	//Funcion que elimina condiciones de seguridad del permiso de alturas
	function eliminarActividad($noAct, $clavePermiso){
		//Incluir el archivo de coneccion
		include_once("../../includes/conexion.inc");

		//Conectarse a la BD de seguridad
		$conn= conecta("bd_seguridad");
	
		//Crear la sentencia que elimine las actividades que el usuario deseee
		$sql_stm = "DELETE FROM pasos_permiso WHERE permisos_secundarios_id_permiso_secundario = '$clavePermiso' AND num_actividad = '$noAct'";
		//Ejecutar la sentencia previamente creada donde se eliminan los registros de las activiaddes en la BD de seguridad en la tabla de pasos_permiso
		$rs = mysql_query($sql_stm);
		//Cerrar la coneccion a la BD
		mysql_close($conn);
	
	}

	//COmporbamos: Si viene el bototn de sbt_guardarActividad, que se encuentra dentro de la ventana emergente guardamos los nuevas actividades o condiciones de seg, dentro de la 		DB
		if(isset($_POST['sbt_guardarActividad'])){
			$clave=$_POST['hdn_clave'];
			regCondSeguridad($clave);
		}	

		function regCondSeguridad($clavePermiso){
			//Importamos archivo para realizar la conexion con la BD
			include_once("../../includes/conexion.inc");

			//Obtener el ID de actividad correspondiente de acuerdo y en consecutivo de los actividades que se vallan guandando
			$noAct = obtenerIdActividad($clavePermiso);
			$actividad = strtoupper($_POST['txt_actividades']);
			
			//Conectarse a la BD de seguridad		
			$conn = conecta("bd_seguridad");
			//Crear la sentencia
			$sql_stm = "INSERT  INTO pasos_permiso(permisos_secundarios_id_permiso_secundario,  num_actividad, actividad) VALUES ('$clavePermiso', '$noAct', '$actividad')";
			 
			$rs = mysql_query($sql_stm);
			if($rs){
			unset($_POST);?>
				<script language="javascript" type="text/javascript">
					window.close();
				</script><?php 
			}
		
			mysql_close($conn);
		
		}
		
		
		//Funcion que nos permite obntener el id del detalle del registro 
	function obtenerIdActividad($clave){
	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
	
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
			
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(num_actividad)+1 AS cant FROM pasos_permiso WHERE permisos_secundarios_id_permiso_secundario = '$clave'";
		
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos['cant'];
		}
			
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
	

	
	
	
	if(isset($_POST['sbt_guardarDetalleAct'])){	
		guardarDetalleActivida();	
	}


	//Funcion que permite almacenar las respuestas a las actividades segun el usuario
	function guardarDetalleActivida(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");		
		//Obtenermos el ID del Tipo de Permiso
		$idPermisoTrab = obtenerIdPermisoAlturas();
		
		//Realzamos la coneccion a la BD
		$conn = conecta("bd_seguridad");	
		
		//Crear una sentencia para verificar si existen datos almacenados en la BD en la tabal de revision_cs
		$stm_sqlCon = "SELECT COUNT(*) AS cant FROM revision_cs WHERE permisos_trabajos_id_permiso_trab = '$idPermisoTrab' 
			AND permisos_secundarios_id_permiso_secundario = '$_POST[hdn_clave]'"; 
		//Traeara la cantidad de registros que existen al momento de ejecutar la sentencia $sql_stmCon 
		$rsCons = mysql_query($stm_sqlCon);
		if($datosCons = mysql_fetch_array($rsCons)){
			$cantReg = $datosCons['cant'];
		}
		else{
			$cantReg=0;
		}
		
		if($cantReg!=0){
			//Creamos la sentencia para eliminar el regsitro de la BD en caso de que el usuario asi lo deseee
			$stm_sqlDel = "DELETE FROM revision_cs WHERE permisos_trabajos_id_permiso_trab = '$idPermisoTrab' 
				AND permisos_secundarios_id_permiso_secundario = '$_POST[hdn_clave]'"; 
			//Ejecutamos le sentencia para eliminar los regiostros de la BD
			$rsDel = mysql_query($stm_sqlDel);
		}
		
		//Ciclo que permite recorrer el POST para crear las sentencias para la insercion de los datos
		foreach($_POST as $key=> $value){
			//Verificamos que los valores NO sean diferentes a los radios
			if($key!="sbt_guardarDetalleAct"&&$key!="hdn_clave"&&$key!="hdn_totalCondiciones"){
				//Seccionamos la clave del POST para obtener el numero de actividad
				$secRadioAct = split('_',$key);
				//Almacenamos en la variable $numActividad el valor de la clave del radio seccionada
				$numActividad = $secRadioAct[1];
				
				//Obtenemos la actividad de la tabla pasos permiso para guardarla en la tabla revision_cs
				$actividad = obtenerDato("bd_seguridad", "pasos_permiso", "actividad", "num_actividad", $numActividad);
				//Creamos la sentencia sql para almacenar las actividades 
				$stm_sql = "INSERT INTO revision_cs(permisos_trabajos_id_permiso_trab, permisos_secundarios_id_permiso_secundario, num_actividad, respuesta, actividad) 
					VALUES ('$idPermisoTrab', '$_POST[hdn_clave]', '$numActividad', '$value', '$actividad')";
				//Ejecutamos la sentencia	
				$rs = mysql_query($stm_sql);
			}				
		}	
		mysql_close($conn);
	}
		
		
?>	