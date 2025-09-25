<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Nadia Madahí López Hernandez
	  * Fecha: 06 Abril de 2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de RegistrarAspirante en la sección de Bolsa de Trabajo en la BD
	**/	
	
	
	/*********************************************************************************************
	 ****************ESTE CODIGO SE EMPLEA EN LA PAGINA frm_registrarAspirante.php****************
	 *********************************************************************************************/
	//Variable de Control, para determinar cuando se debe mostrar el Formulario y cuando no
	$ctrl_vistaForm = 0;//0 siginifica que debe mostrar y 1 significa que no debe mostrarse
	
	//Cuando el usuario de guardar, los datos seran tomados del $_POST para almacenarlos en la BD y los quitamos de la SESSION
	if(isset($_POST['sbt_guardarAspirante'])){
		registrarAspirante();		
		$ctrl_vistaForm = 1;		
		//Quitar los datos de la SESSION una vez que han sido guardados
		unset($_SESSION['datosAspirante']);
		unset($_SESSION['datosContactoAspirante']);
		unset($_SESSION['datosPuestoAspirante']);
	}		
	
	/*Si existe el boton sbt_agregarAreaPuesto en el POST, entonces cargamos los datos del aspirante a la SESSION*/
	if(isset($_POST['sbt_registrarAreaPuesto'])){
		$_SESSION['datosAspirante'] = array("nombre"=>strtoupper($_POST['txt_nombre']),
											"apePat"=>strtoupper($_POST['txt_apePat']),
											"apeMat"=>strtoupper($_POST['txt_apeMat']),
											"curp"=>strtoupper($_POST['txt_curp']),
											"edad"=>$_POST['txt_edad'],
											"edoCivil"=>strtoupper($_POST['txt_edoCivil']),
											"experiencia"=>strtoupper($_POST['txa_experiencia']),
											"lugarNac"=>strtoupper($_POST['txt_lugarNac']),
											"nacionalidad"=>strtoupper($_POST['txt_nacionalidad']),
											"tel"=>$_POST['txt_tel'],
											"telRef"=>$_POST['txt_telRef'], 
											"observaciones"=>strtoupper($_POST['txa_observaciones']));					
		$ctrl_vistaForm = 1;		
		
		//Redireccionar al formulario donde se ingresan el Area y el Puesto recomendados para el aspirante que se esta registrando
		echo "<meta http-equiv='refresh' content='0;url=frm_puestoAspirante.php'>";
	}
	
	//Declarar las variables que seran utilizadas en el Formulario de Registrar Aspirante						
	$txt_nombre = "";
	$txt_apePat = "";
	$txt_apeMat = "";
	$txt_curp = "";
	$txt_edad = "";
	$txt_edoCivil = "";
	$txa_experiencia = "";
	$txt_lugarNac = "";
	$txt_nacionalidad = "";
	$txt_tel = "";
	$txt_telRef = "";
	$txa_observaciones = "";
	
	//Recupera los datos de la SESSION del Aspirante para ser mostrados nuevamente en el formulario cuando se accesa a esta pagina desde frm_contactoAspirante
	if(isset($_SESSION['datosAspirante']) && isset($_SESSION['datosContactoAspirante']) && isset($_SESSION['datosPuestoAspirante'])){
		$txt_nombre = $_SESSION['datosAspirante']["nombre"];
		$txt_apePat = $_SESSION['datosAspirante']["apePat"];
		$txt_apeMat = $_SESSION['datosAspirante']["apeMat"];
		$txt_curp = $_SESSION['datosAspirante']["curp"];
		$txt_edad = $_SESSION['datosAspirante']["edad"];
		$txt_edoCivil = $_SESSION['datosAspirante']["edoCivil"];
		$txa_experiencia = $_SESSION['datosAspirante']["experiencia"];
		$txt_lugarNac = $_SESSION['datosAspirante']["lugarNac"];
		$txt_nacionalidad = $_SESSION['datosAspirante']["nacionalidad"];
		$txt_tel = $_SESSION['datosAspirante']["tel"];
		$txt_telRef = $_SESSION['datosAspirante']["telRef"];
		$txa_observaciones = $_SESSION['datosAspirante']["observaciones"];
		//$orgDatos = $_SESSION['datosAspirante']["orgDatos"];																			
	}	
	
	
	/******************************************************************************************
	 ****************ESTE CODIGO SE EMPLEA EN LA PAGINA frm_puestoAspirante.php****************
	 ******************************************************************************************/
	 $msgAreaPuesto = "";
	//Agregar a la SESSION los datos de los Puestos a los que esta siendo Asociado el Aspirante
	if(isset($_POST['sbt_registrarPuesto'])){
		//Obtener el Area, ya sea de comboBox o de la Caja de Texto
		if(isset($_POST['cmb_area']))
			$area = $_POST['cmb_area'];
		else if(isset($_POST['txt_areaRecomendada']))
			$area = strtoupper($_POST['txt_areaRecomendada']);
			
		//Obtener el Puesto, ya sea de comboBox o de la Caja de Texto
		if(isset($_POST['cmb_puesto']))
			$puesto = $_POST['cmb_puesto'];
		else if(isset($_POST['txt_puestoRecomendado']))
			$puesto = strtoupper($_POST['txt_puestoRecomendado']);
						
		//Si ya esta definido en arreglo en la SESSION, agregar los nuevos registros
		if(isset($_SESSION['datosPuestoAspirante'])){
			$ctrlRegDuplicado = 0;//Siginifica que no existe en la SESSION
			//Verificar Que el registro no haya sido agregado previamente, recorrer cada registro del arreglo
			foreach($_SESSION['datosPuestoAspirante'] as $indice => $arrAreaPuesto){
				//Comparar cada area y puesto para ver que no se repitan
				if($arrAreaPuesto['areaRecomendada']==$area && $arrAreaPuesto['puestoRecomendado']==$puesto){
					$ctrlRegDuplicado = 1;//Significa que esta duplicado
				}
			}
			
			if($ctrlRegDuplicado==0)
				$_SESSION['datosPuestoAspirante'][] = array("areaRecomendada"=>$area,"puestoRecomendado"=>$puesto);
			else
				$msgAreaPuesto = "El Puesto $puesto Ya Fue Registrado en el &Aacute;rea $area";
			//$_SESSION['datosPuestoAspirante'][1] = [areaRecomendada]=>"Administracion", [puestoRecomendado]=>"Pagos"	
		}
		else{//Definir el Arreglo y agregar el Primer registro
			$_SESSION['datosPuestoAspirante'] = array(array("areaRecomendada"=>$area,"puestoRecomendado"=>$puesto));
			//$_SESSION['datosPuestoAspirante'][0] = [areaRecomendada]=>"Sistemas", [puestoRecomendado]=>"Desarrollo"	
		}																												
	}		
		
	
	//Redireccionar a la pagina de registrar Aspirante y terminar el registro
	if(isset($_POST['sbt_registrarContactosAspirante'])){
		echo "<meta http-equiv='refresh' content='0;url=frm_contactoAspirante.php'>";
	}
		

	/********************************************************************************************
	 ****************ESTE CODIGO SE EMPLEA EN LA PAGINA frm_contactoAspirante.php****************
	 ********************************************************************************************/
	 $msgContactoAspirante = "";
	//Agregar los Datos de los Contactos del Aspirante a la SESSION
	if(isset($_POST['sbt_registrarContacto'])){
		//Si el Arreglo datosContactoAspirante ya esta registrado en la SESSION, agregar un nuevo registro(datos de un contacto)
		if(isset($_SESSION['datosContactoAspirante'])){
		/*Aqui comienza la modificacion del contacto para No agregar un registro repetido*/
			$ctrlRegDuplicadoCont = 0;//Siginifica que no existe en la SESSION
			//Verificar Que el registro no haya sido agregado previamente, recorrer cada registro del arreglo
			foreach($_SESSION['datosContactoAspirante'] as $indice => $arrContactosAspirante){
				//Comparar cada nombre del registro para ver que no se repitan
				if($arrContactosAspirante['nombre']==$txt_nombreCont){
					$ctrlRegDuplicadoCont = 1;//Significa que esta duplicado
			
				}
			}//txt_nombreCont
			if($ctrlRegDuplicadoCont==0)
		
			$_SESSION['datosContactoAspirante'][] = array("nombre"=>strtoupper($_POST['txt_nombreCont']),
														  "calle"=>strtoupper($_POST['txt_calle']),
														  "numExt"=>strtoupper($_POST['txt_numExt']),
														  "numInt"=>strtoupper($_POST['txt_numInt']),
														  "colonia"=>strtoupper($_POST['txt_colonia']),
														  "estado"=>strtoupper($_POST['txt_estado']),
														  "pais"=>strtoupper($_POST['txt_pais']),
														  "telefono"=>$_POST['txt_tel']);
			else
				$msgContactoAspirante = "El Contacto $txt_nombreCont Ya Fue Registrado anteriormente";		
		}	
		else{//Guardar el primer contacto en el arreglo datosContactoAspirante definido en la SESSION
			$_SESSION['datosContactoAspirante'] = array(array("nombre"=>strtoupper($_POST['txt_nombreCont']),
															  "calle"=>strtoupper($_POST['txt_calle']),
														  	  "numExt"=>strtoupper($_POST['txt_numExt']),
														  	  "numInt"=>strtoupper($_POST['txt_numInt']),
														  	  "colonia"=>strtoupper($_POST['txt_colonia']),
														  	  "estado"=>strtoupper($_POST['txt_estado']),
														  	  "pais"=>strtoupper($_POST['txt_pais']),
														  	  "telefono"=>$_POST['txt_tel']));
		}										   				
	}
	
	//Redireccionar a la pagina de registrar Aspirante y terminar el registro
	if(isset($_POST['sbt_finalizarRegistroContacto'])){
		echo "<meta http-equiv='refresh' content='0;url=frm_registrarAspirante.php'>";
	}
	
				
	/*********************************************************************************************
	 *********************************DECLARACION DE FUNCIONES************************************
	 *********************************************************************************************/	
	//Esta función se encarga de generar el Folio de el registro que se le hace al aspirante para guardara la inforamción en la BD
	function obtenerFolioAspirante(){
		//Realizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");
		
		//Definir las  letras del Folio del Aspirante que en este caso seria BTR = Bolsa de Trabajo
		$id_cadena = "BTR";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener loa aspirantes del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Aspirantes registrados
		$stm_sql = "SELECT MAX(folio_aspirante) AS folio_aspirante FROM bolsa_trabajo WHERE folio_aspirante LIKE 'BTR$mes$anio%'";								
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$folio = intval(substr($datos['folio_aspirante'],-3));												
			$cant = $folio + 1;
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
	}//Fin de la Funcion obtenerFolioAspirante()	

			

	function registrarAspirante(){
		//Abrimos la Conexión a la bd de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Nos traemos la información que se va agregar a a la BD desde el vector $_POST[] ya que esta información vienen desde el formulario frm_registrarAspirante en el $_POST[]
		$folioAspirante = $_POST['txt_folioAspirante'];
		$nombre = $_POST['txt_nombre'];
		$apePat = $_POST['txt_apePat'];
		$apeMat = $_POST['txt_apeMat'];
		$curp = $_POST['txt_curp'];
		$edoCivil = $_POST['txt_edoCivil'];
		$experiencia = $_POST['txa_experiencia'];
		$lugarNac = $_POST['txt_lugarNac'];
		$nacionalidad = $_POST['txt_nacionalidad'];
		$observaciones = $_POST['txa_observaciones'];		
		//Por el alcance de las variables se tiene que hacer referencia a estas variables con el vector $_POST[]   aunque ya vengan en el POST
		$edad = $_POST['txt_edad'];
		$fechaSolicitud = $_POST['txt_fechaSolicitud'];	
		$tel = $_POST['txt_tel'];
		$telRef = $_POST['txt_telRef'];				
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		 $fecha = modFecha($_POST['txt_fechaSolicitud'],3);
		
		//Creamos la sentencia SQL para guardar el resgistro del Aspirante en BD de recursos
		$stm_sql = "INSERT INTO bolsa_trabajo 
		(folio_aspirante, nombre, ap_paterno, ap_materno, curp, edad, estado_civil, experiencia_laboral, fecha_solicitud, lugar_nac, nacionalidad, telefono, tel_referencia, observaciones) 
		VALUES ('$folioAspirante', '$nombre', '$apePat', '$apeMat', '$curp', $edad, '$edoCivil', '$experiencia', '$fecha', '$lugarNac', 
		'$nacionalidad', '$tel', '$telRef', '$observaciones')";
		
		//Ejecutar la sentencia previamente creada
		$datos = mysql_query($stm_sql);
	
		//Confirmar que la inserción de datos fue realizada con exito.
		if($datos){ 									
			//Guardar las Areas y Pueslos asociados al Aspirante
			registrarAreaPuesto($folioAspirante);
			registrarOperacion("bd_recursos",$folioAspirante,"RegistrarAspiranteBolsaTrabajo",$_SESSION['usr_reg']);					
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
				
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
		}	
	}


	/*Esta funcion se encarga de guardar las Areas y Puestos Recomendados para el Aspirante*/
	function registrarAreaPuesto($folioAspirante){			
		
		//Variable para el manejo de los errores
		$ctrl_errores = 0;
		
		//Guardar cada Area y Puesto Asociados al Aspirante 
		foreach($_SESSION['datosPuestoAspirante'] as $key => $regAreaPuesto){
			
			//Creamos la sentencia SQL para guardar los puestos recomendados por el administrador de RH en la BD de Recursos					
			$stm_sql= "INSERT INTO area_puesto (bolsa_trabajo_folio_aspirante, area, puesto) VALUES('$folioAspirante', '$regAreaPuesto[areaRecomendada]', '$regAreaPuesto[puestoRecomendado]')";			
			
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
		
			//Confirmar que la inserción de datos fue realizada con exito.
			if(!$rs){
				$ctrl_errores = 1;
				break;
			}
		}
		//Evaluar el resultado de la Insersion de las Areas y Puestos
		if($ctrl_errores==0){
			registrarContacto($folioAspirante);
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
	}
	
	
	function registrarContacto($folioAspirante){	

		//Variable para el manejo de los errores
		$ctrl_errores = 0;
		
		//Guardar cada Area y Puesto Asociados al Aspirante 
		foreach($_SESSION['datosContactoAspirante'] as $key => $regContacto){		
									
			//Creamos la sentencia SQL para guardar el Contacto del Aspirante en BD de recursos
			$stm_sql = "INSERT INTO contacto (bolsa_trabajo_folio_aspirante, nom_contacto, calle, num_ext, num_int, colonia, estado, pais, telefono) 
			VALUES ('$folioAspirante', '$regContacto[nombre]', '$regContacto[calle]', '$regContacto[numExt]', '$regContacto[numInt]', '$regContacto[colonia]', '$regContacto[estado]', '$regContacto[pais]', '$regContacto[telefono]')";
			
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);
		
			//Confirmar que la inserción de datos fue realizada con exito.
			if(!$rs){
				$ctrl_errores = 1;
				break;
			}
		}
				
		
		//Evaluar el resultado de la Insersion de las Areas y Puestos
		if($ctrl_errores==0){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
				
	}		
	
	
	/*Esta función muestra las Areas y Puestos Recomendados para el Aspirante*/
	function mostrarPuestosAspirante(){
		echo "<table cellpadding='5' width='40%'>";
		echo "<caption class='titulo_etiqueta'>Puestos Recomendados para el Aspirante</caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center' width='10%'>NO.</td>
        		<td class='nombres_columnas' align='center' width='30%'>&Aacute;REA</td>
			    <td class='nombres_columnas' align='center' width='60%'>PUESTO</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		//Extraer cada registro(arreglo) del arreglo que contiene las Areas y Puestos recomendados para el Aspirante
		foreach ($_SESSION['datosPuestoAspirante'] as $ind => $regAreaPuesto) {																
			//Desplegar las Areas y Puestos del Aspirante
			echo "
				<tr>
					<td class='$nom_clase'>$cont</td>
					<td class='$nom_clase'align='left'>$regAreaPuesto[areaRecomendada]</td>
					<td class='$nom_clase'align='left'>$regAreaPuesto[puestoRecomendado]</td>
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
	
	
	/*Esta función muestra los Contactos que tendran Registrados los Aspirante*/
	function mostrarContactosAspirante(){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption class='titulo_etiqueta'>Contactos Registrados del Aspirante</caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NO.</td>
        		<td class='nombres_columnas' align='center'>NOMBRE</td>
			    <td class='nombres_columnas' align='center'>CALLE</td>
        		<td class='nombres_columnas' align='center'>NUM. EXTERIOR</td>
			    <td class='nombres_columnas' align='center'>NUM. INTERIOR</td>				
        		<td class='nombres_columnas' align='center'>COLONIA</td>
			    <td class='nombres_columnas' align='center'>ESTADO</td>				
        		<td class='nombres_columnas' align='center'>PAIS</td>
			    <td class='nombres_columnas' align='center'>TELEFONO</td>				
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;	
		
		foreach($_SESSION['datosContactoAspirante'] as $ind => $regContactosAspirante) {
			//Desplegar el nombre de los Contactos 
			echo "
				<tr>
					<td class='$nom_clase'>$cont</td>
					<td class='$nom_clase'align='left'>$regContactosAspirante[nombre]</td>
					<td class='$nom_clase'align='left'>$regContactosAspirante[calle]</td>
					<td class='$nom_clase'align='center'>$regContactosAspirante[numExt]</td>
					<td class='$nom_clase'align='center'>$regContactosAspirante[numInt]</td>	
					<td class='$nom_clase'align='left'>$regContactosAspirante[colonia]</td>
					<td class='$nom_clase'align='left'>$regContactosAspirante[estado]</td>
					<td class='$nom_clase'align='left'>$regContactosAspirante[pais]</td>
					<td class='$nom_clase'align='left'>$regContactosAspirante[telefono]</td>										
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