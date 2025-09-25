<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 01/Abril/2011
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con los formularios de AgregarEmpleado en la BD
	**/

	//Verificar que se haya presionado el boton agregar desde la pagina frm_agregarEmpleado2.php
	if (isset($_POST["sbt_agregarEmpleado"])){
		agregarEmpleado();
	}

	//Esta funcion Agrega el Equipo en la Base de Datos
	function agregarEmpleado(){
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");		

		//Abrir la SESSION para recuperar el valor de las Variables
		session_start();
		//Recoger los datos Personales de la SESSION
		$rfc=strtoupper($_SESSION["datosPersonales"]["rfc"]);
		$nombre=strtoupper($_SESSION["datosPersonales"]["nombre"]); 
		$apPat=strtoupper($_SESSION["datosPersonales"]["apPat"]);
		$apMat=strtoupper($_SESSION["datosPersonales"]["apMat"]);
		$sangre=strtoupper($_SESSION["datosPersonales"]["sangre"]);
		$curp=strtoupper($_SESSION["datosPersonales"]["curp"]);
		$calle=strtoupper($_SESSION["datosPersonales"]["calle"]);
		$num_ext=strtoupper($_SESSION["datosPersonales"]["num_ext"]);
		$num_int=strtoupper($_SESSION["datosPersonales"]["num_int"]);
		$codigoPost=$_SESSION["datosPersonales"]["cp"];
		$fecha=modFecha($_SESSION["datosPersonales"]["fecha"],3);
		$col=strtoupper($_SESSION["datosPersonales"]["col"]);
		$estado=strtoupper($_SESSION["datosPersonales"]["estado"]);
		$pais=strtoupper($_SESSION["datosPersonales"]["pais"]);
		$nac=strtoupper($_SESSION["datosPersonales"]["nac"]);
		$nss=strtoupper($_SESSION["datosPersonales"]["nss"]);
		$edoCivil=strtoupper($_SESSION["datosPersonales"]["edoCivil"]);
		$obs=strtoupper($_SESSION["datosPersonales"]["obs"]);
		$id_bolsa=$_SESSION["datosPersonales"]["id_bolsa"];
		$contactoAcc=strtoupper($_SESSION["datosPersonales"]["contactoAcc"]);
		$telCasa=$_SESSION["datosPersonales"]["telCasa"];
		$celular=$_SESSION["datosPersonales"]["celular"];
		$mun_loc=strtoupper($_SESSION["datosPersonales"]["mun_loc"]);
		$telTrabajador=$_SESSION["datosPersonales"]["telTrabajador"];
		$lugarNac=strtoupper($_SESSION["datosPersonales"]["lugarNac"]);
		//Datos Formato DC-4
		$discapacidad=$_SESSION["datosPersonales"]["discapacidad"];
		$hijosDepEco=$_SESSION["datosPersonales"]["hijosDepEco"];
		//Datos Academicos
		$nivEstudios=$_SESSION["datosPersonales"]["nivEstudios"];
		$titulo=$_SESSION["datosPersonales"]["titulo"];
		$carrera=strtoupper($_SESSION["datosPersonales"]["carrera"]);
		$tipoEscuela=$_SESSION["datosPersonales"]["tipoEscuela"];
		//Control de costos
		$cmb_con_cos=$_SESSION["datosPersonales"]["control_cos"];
		$cmb_cuenta=$_SESSION["datosPersonales"]["cuentas"];
		//Datos de alimentos
		$derechoAlimento=$_SESSION["datosPersonales"]["alimento"];
		
		//Recoger los datos laborales del POST
		$cve_empresa=strtoupper($_POST["txt_cveEmp"]);
		$cve_area=strtoupper($_POST["txt_cveArea"]);
		$jornada=strtoupper($_POST["txt_jornada"]);
		$num_cta=strtoupper($_POST["txt_numCta"]);
		$sueldo=$_POST["txt_sueldo"];
		if($sueldo == "")
			$sueldo = 0;
		$ocEsp=strtoupper($_POST["txt_ocEsp"]);
		if (strlen($sueldo)>6)
			$sueldo=str_replace(",","",$sueldo);
		//Verificamos si viene el combo Activo de AREA para preparar la Sentencia SQL
		/*if (isset($_POST["cmb_area"])){
			$depto=split(";",$_POST["cmb_area"]);
			$area=$depto[1];
			$id_depto=$depto[0];
			$nuevo="no";
		}
		else{
			$area=strtoupper($_POST["txt_nuevaArea"]);
			$id_depto=obtenerIdDepto();
			$nuevo="si";
		}*/
		$area = obtenerNombreCentroCostos($cmb_con_cos);
		$id_depto=obtenerIdDepto($area,1);
		$nuevo="no";
		if ($id_depto==null){
			$id_depto=obtenerIdDepto($area,2);
			$nuevo="si";
		}
		if(isset($cmb_cuenta)){
			$clave_area = obtenerIdArea($area,1);
			if($clave_area==null){
				$clave_area = obtenerIdArea($area,2);
			}
			$id_depto=obtenerIdDepto2($cmb_cuenta,$clave_area);
		}
		//Verificamos si viene el combo Activo de PUESTO para preparar la Sentencia SQL
		if (isset($_POST["cmb_puesto"]))
			$puesto=$_POST["cmb_puesto"];
		else
			$puesto=strtoupper($_POST["txt_nuevoPuesto"]);
		
		//Cargar la imagen en el caso de que se haya cargado una y el tipo para ser almacenados en la BD
		if(isset($_FILES["foto"])) 
			$foto_info = cargarImagen("foto"); 
		else 
			$foto_info = array("foto"=>"", "type"=>"");
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Creamos la sentencia SQL para insertar los datos en Empleados
		$stm_sql="INSERT INTO empleados (rfc_empleado,bolsa_trabajo_folio_aspirante,curp,id_empleados_area,id_empleados_empresa,nombre,ape_pat,ape_mat,
		sueldo_diario,tipo_sangre,no_ss,fecha_ingreso,puesto,no_cta,area,jornada,calle,num_ext,num_int,colonia,cp,localidad,estado,pais,nacionalidad,telefono,fotografia,
		mime,edo_civil,observaciones,nom_accidente,tel_accidente,cel_accidente,id_depto,discapacidad,hijos_dep_eco,nivel_estudio,titulo,carrera,tipo_escuela,oc_esp,lugar_nacimiento,id_control_costos,id_cuentas,derecho_alimentos)
		VALUES ('$rfc','$id_bolsa','$curp','$cve_area','$cve_empresa','$nombre','$apPat','$apMat',
		'$sueldo','$sangre','$nss','$fecha','$puesto','$num_cta','$area','$jornada','$calle','$num_ext','$num_int','$col',$codigoPost,'$mun_loc','$estado','$pais','$nac','$telTrabajador','$foto_info[foto]',
		'$foto_info[type]','$edoCivil','$obs','$contactoAcc','$telCasa','$celular','$id_depto','$discapacidad','$hijosDepEco','$nivEstudios','$titulo','$carrera','$tipoEscuela','$ocEsp','$lugarNac','$cmb_con_cos','$cmb_cuenta','$derechoAlimento')";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($rs){
			//Borrar el arreglo de datosPersonales de la Session
			if (isset($_SESSION["datosPersonales"])){
				unset($_SESSION["datosPersonales"]);
			}
			
				if($foto_info["foto"]!=""){ 
					//Extrae el contenido de la foto original
					$fp = opendir("documentos/");//Abrir el archivo temporal el modo lectura'r' binaria'b'
						
					rmdir("documentos/temp");
					closedir($fp);//Cerrar el puntero al archivo abierto	
				}
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			
			//Ingresar al Trabajador en el Ibix para el HandPunch
			insertarHandPunch($nuevo,$area,$id_depto,$cve_empresa,$nombre,$apPat,$apMat,$fecha,$puesto,$curp,$rfc,$nss,$sangre);
			
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc","AgregarEmpleado",$_SESSION['usr_reg']);	
				
			//Registrar los datos del Empleado recien registrado en la tabla de Alertas de Almacen, para notificar al Almacenista que hay un nuevo empleado
			$idAlerta = obtenerIdAlerta();
			$fechaActual = date("Y-m-d");
			$conn_alm = conecta("bd_almacen");
			$rs = mysql_query("INSERT INTO alertas (id_alerta,rfc_empleado,estado,fecha_generacion,origen) 
			VALUES('$idAlerta','$rfc',1,'$fechaActual','RH')");
			mysql_close($conn_alm);
				
			//Si los datos fueron agregados correctamente, se redirecciona a otra pagina que muestra el resultado de los datos agregados
			echo "<meta http-equiv='refresh' content='0;url=frm_empleadoAgregado.php?rfc=$rfc'>";
		}
		else{
			//Obtenemos el error que se haya generado
			$error=mysql_error();
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	}//Fin de la funcion para registrar el empleado
	
	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenado en la tabla de Empleados en el campo fotografia que es de tipo longblob
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
			//Cargar y Redimensionar la Imagen en el Directorio "../rec/documentos/temp"
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
			//Regresar la Info vacia de la foto en el caso de que no sea una imagen valida o exceda 10Mb 
			return $foto_info = array("foto"=>"","type"=>"");
		}
	}
	
	//funcion que carga una imagen a un Directorio Temporal en la carpeta de "rec/documentos/temp"
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
	
	
	//Esta funcion permite registrar los Beneficiarios del Empleado en que se este trabajando
	function registrarBeneficiarios($rfc_persona,$beneficiarios){
		//Realizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");
		$band=0;
		$error="";
		//Registrar todos los Beneficiarios dados de alta en el arreglo $beneficiarios
		foreach ($_SESSION["beneficiarios"] as $ind => $ben){
			//Crear la sentencia para realizar el registro de los Beneficiarios
			$stm_sql = "INSERT INTO beneficiarios (empleados_rfc_empleado,nombre,parentesco,edad,porcentaje)
			VALUES('$rfc_persona','$ben[nombre]','$ben[parentesco]','$ben[edad]','$ben[porcentaje]')";
			//Ejecutar la sentencia previamente creada para agregar cada Beneficiario a la tabla de beneficiarios
			$rs = mysql_query($stm_sql);
			if(!$rs){
				echo $error=mysql_error();
				$band = 1;
			}
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		if ($band==1){
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		else{
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc_persona","AgregarBeneficiariosEmpleado",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}
	
	//Esta funcion permite registrar los Becarios del Empleado con quien se este trabajando
	function registrarBecarios($rfc_persona,$becarios){
		//Realizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");
		$band=0;
		$error="";
		
		//Registrar todos los Becarios dados de alta en el arreglo $becarios
		foreach ($_SESSION["becarios"] as $ind => $bec){
			//Crear la sentencia para realizar el registro de los Becarios
			$stm_sql = "INSERT INTO becas (empleados_rfc_empleado,nom_becario,parentesco,grado_estudio,promedio,cantidad)
			VALUES('$rfc_persona','$bec[nombre]','$bec[parentesco]','$bec[grado_estudio]','$bec[promedio]','$bec[cantidad]')";
			//Ejecutar la sentencia previamente creada para agregar cada Becario a la tabla de becas
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$error=mysql_error();
				$band = 1;
			}
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		if ($band==1){
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";		
		}
		else{
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			//Registrar la Operacion en la Bit�cora de Movimientos
			registrarOperacion("bd_recursos","$rfc_persona","AgregarBecariosEmpleado",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}

	//Genera la Id de la Alerta que ser� registrada en la tabla de alertas
	function obtenerIdAlerta(){		
		//Conectarse a la BD de Almac�n
		$conn = conecta("bd_almacen");
		
		//Definir las tres letras en la Id de la Alerta
		$id_cadena = "ALR";
		
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el a�o actual para ser agregados en la consulta y asi obtener las entradas del mes y a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		$stm_sql = "SELECT MAX(id_alerta) AS clave FROM alertas WHERE id_alerta LIKE 'ALR$mes$anio%'";
		//Ejecutar Alerta		
		$rs = mysql_query($stm_sql);		
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}		
		
		//Cerrar la conexion con la BD de Almacen
		return $id_cadena;
		mysql_close($conn);
	}//Fin de la Funcion obtenerIdAlerta()
	
	//Funcion que obtiene un Id para los deptos
	function obtenerIdDepto($id_cc,$op){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		if($op == 1)
			$stm_sql = "SELECT id_depto FROM empleados WHERE area = '$id_cc'";
		if($op == 2)
			$stm_sql = "SELECT MAX(id_depto)+1 AS claveDepto FROM empleados";
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";
	}//fin de obtenerIdDepto()
	
	//Funcion que obtiene un Id para los deptos
	function obtenerIdDepto2($id_c,$id_ar){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		
		$stm_sql = "SELECT id_depto FROM empleados WHERE id_empleados_area = '$id_ar' AND id_cuentas = '$id_c'";
		
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			mysql_close($conn);
			return $datos[0];
		}
		else{
			$stm_sql = "SELECT COALESCE( MAX( id_depto ) , 0 ) +1 AS claveDepto FROM empleados";
			$rs = mysql_query($stm_sql);
			if($datos = mysql_fetch_array($rs)){
				mysql_close($conn);
				return $datos[0];
			}
		}
	}//fin de obtenerIdDepto2()
	
	//Funcion que obtiene un ID para las areas
	function obtenerIdArea($id_cc,$op){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		if($op == 1)
			$stm_sql = "SELECT id_empleados_area FROM empleados WHERE area = '$id_cc'";
		if($op == 2)
			$stm_sql = "SELECT MAX(id_empleados_area)+1 AS num_areas FROM empleados";
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
		if($datos = mysql_fetch_array($rs))
			return $datos[0];
		else
			return null;
	}//fin de obtenerIdArea($id_cc,$op)
	
	//Funcion que obtiene el nombre de un centro de costos
	function obtenerNombreCentroCostos($id_cc){
		//Conectarse a la BD de Recursos
		$conn = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM control_costos
					WHERE id_control_costos =  '$id_cc'";
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
		if($datos = mysql_fetch_array($rs))		
			return $datos[0];
		else
			return "";
	}//fin de obtenerNombreCentroCostos($id_cc)
	
	//Funcion que ingresa al trabajador en la BD de Access para el Ibix
	function insertarHandPunch($nuevo,$area,$id_depto,$cve_empresa,$nombre,$apPat,$apMat,$fecha,$puesto,$curp,$rfc,$nss,$sangre){
		//Usuario
		$user="Administrador";
		//Password
		$password="libre";
		//Ubicacion de la Base de Datos
		$mdbFilename=realpath("handpunch\IBIXConnect.mdb");
		//Conexion a la Base de Datos
		$db_connstr="Driver={Microsoft Access Driver (*.mdb)}; Dbq=$mdbFilename"; 
		$conn=odbc_connect($db_connstr, $user, $password);
		
		//Capturar la clave de empleado para preparar el NIP del trabajador para el HandPunch
		$nip=$cve_empresa;
		$longNip=strlen($nip);
		if ($longNip==1)
			$nip="000".$nip;
		if ($longNip==2)
			$nip="00".$nip;
		if ($longNip==3)
			$nip="0".$nip;
		//Concatenar el nip con la Clave del Trabajador
		$idTrabajador="0100".$nip;
		
		if ($nuevo=="si"){
			//Preparar la sentencia Access para Ingresar al Departamento
			$stm_acc="INSERT INTO tblDepto (Emp,Depto,Nombre) VALUES ('1','$id_depto','$area')";
			//Ejecutar la sentencia
			$rs=odbc_exec($conn,$stm_acc);
		}
		
		//Preparar la sentencia Access para Ingresar al Trabajador
		$stm_acc="INSERT INTO tblTrabajador (Emp,Trabajador,NIP,Clave,Nombre,Depto,FechaNac,FechaAlta,OpcionTeclado,		ChecadaLibre,HuellaDigital,Puesto,Curp,Rfc,Imss,TipoSangre,SemanaActiva,Activo,Opciones,NumRegistro,AplicaPP,AplicaTExt,AplicaExpIncidencias,AplicaExpConsComedor) VALUES ('1','$cve_empresa','$nip','$idTrabajador','$nombre $apPat $apMat','$id_depto','1999-11-30','$fecha','0','1','0','$puesto','$curp','$rfc','$nss','$sangre','0','1','0','$cve_empresa','0','1','1','1');";
		//Ejecutar la sentencia
		$rs=odbc_exec($conn,$stm_acc);
		//Cerrar la conexion con la BD
		odbc_close($conn);
	}//Fin de function insertarHandPunch
?>