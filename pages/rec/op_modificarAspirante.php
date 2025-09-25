<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 13/Abril/2011
	  * Descripción: Este archivo permite mostrar los Aspirantes para ser modificados.
	**/
	
	
	
	/*********************************************************************************************
	 ****************ESTE CODIGO SE EMPLEA EN LA PAGINA frm_modificarAspirante.php****************
	 *********************************************************************************************/
	 //Cuando el usuario de guardar, los datos seran tomados del $_POST para almacenarlos en la BD y los quitamos de la SESSION
	if(isset($_POST['sbt_modificarAspirante'])){
		registrarAspirante();			
		//Quitar los datos de la SESSION una vez que han sido guardados
		unset($_SESSION['datosAspirante']);
		unset($_SESSION['datosContactoAspirante']);
		unset($_SESSION['datosPuestoAspirante']);
	}	
	 		
	//Declarar las variables que sern necesarias para mostrar los datos del Aspirante
	$msj_resultados = "";
	$txt_folioAspirante = "";
	$txt_nombre = "";
	$txt_apePat = "";
	$txt_apeMat = "";
	$txt_curp = "";
	$txt_edad = "";
	$txt_edoCivil = "";
	$txa_experiencia = "";
	$txt_fechaSolicitud = "";
	$txt_lugarNac = "";
	$txt_nacionalidad = "";
	$txt_tel = "";
	$txt_telRef = "";	
	$txa_observaciones = "";
	
	
	//Si el usuario quiere modificar un aspirante, hacer la consulta en la BD y extraer los datos para mostrarlos en el Formulario de Modificar Aspirante
	if(isset($_POST['sbt_consultar'])){
		//Quitar los Datos de la SESSION cuando se vuleve a consultar un Aspirante antes de Guardar el anterior
		if(isset($_SESSION['datosAspirante'])) unset($_SESSION['datosAspirante']);
		if(isset($_SESSION['datosContactoAspirante'])) unset($_SESSION['datosContactoAspirante']);
		if(isset($_SESSION['datosPuestoAspirante'])) unset($_SESSION['datosPuestoAspirante']);		
		
		//Obtener los datos del Aspirante en el Arreglo datosAspirante
		$datosAspirante = buscarAspirantes($_POST['txt_nombreAspirante']);

		if($datosAspirante!="Error"){//Cargar los datos del Aspirante en las variables para ser mostrados en el formulario		
			$txt_folioAspirante = $datosAspirante['folio_aspirante'];
			$txt_nombre = $datosAspirante['nombre'];
			$txt_apePat = $datosAspirante['ap_paterno'];
			$txt_apeMat = $datosAspirante['ap_materno'];
			$txt_curp = $datosAspirante['curp'];
			$txt_edad = $datosAspirante['edad'];
			$txt_edoCivil = $datosAspirante['estado_civil'];
			$txa_experiencia = $datosAspirante['experiencia_laboral'];
			$txt_fechaSolicitud = modFecha($datosAspirante['fecha_solicitud'],1);
			$txt_lugarNac = $datosAspirante['lugar_nac'];
			$txt_nacionalidad = $datosAspirante['nacionalidad'];
			$txt_tel = $datosAspirante['telefono'];
			$txt_telRef = $datosAspirante['tel_referencia'];			
			$txa_observaciones = $datosAspirante['observaciones'];
			
			//Cargar los datos de las Areas y Puestos del Aspirante a la SESSION
			cargarAreaPuesto($txt_folioAspirante);
													
			//Cargar los datos de los Contactos del Aspirante a la SESSION							
			cargarContactos($txt_folioAspirante);											  		
			
		}
		else		
			$msj_resultados = "No se Encontr&oacute; Ning&uacute;n Registro con el Nombre <em><u>$_POST[txt_nombreAspirante]</u></em>";
		
	}
	
	
	//Recupera los datos de la SESSION del Aspirante para ser mostrados nuevamente en el formulario cuando se accesa a esta pagina desde frm_contactoAspirante
	if(isset($_SESSION['datosAspirante']) && isset($_SESSION['datosContactoAspirante']) && isset($_SESSION['datosPuestoAspirante'])){
		$txt_folioAspirante = $_SESSION['datosAspirante']["folio"];
		$txt_nombre = $_SESSION['datosAspirante']["nombre"];
		$txt_apePat = $_SESSION['datosAspirante']["apePat"];
		$txt_apeMat = $_SESSION['datosAspirante']["apeMat"];
		$txt_curp = $_SESSION['datosAspirante']["curp"];
		$txt_edad = $_SESSION['datosAspirante']["edad"];
		$txt_edoCivil = $_SESSION['datosAspirante']["edoCivil"];
		$txa_experiencia = $_SESSION['datosAspirante']["experiencia"];
		$txt_fechaSolicitud = $_SESSION['datosAspirante']["fecha"];
		$txt_lugarNac = $_SESSION['datosAspirante']["lugarNac"];
		$txt_nacionalidad = $_SESSION['datosAspirante']["nacionalidad"];
		$txt_tel = $_SESSION['datosAspirante']["tel"];
		$txt_telRef = $_SESSION['datosAspirante']["telRef"];
		$txa_observaciones = $_SESSION['datosAspirante']["observaciones"];
		//$orgDatos = $_SESSION['datosAspirante']["orgDatos"];																			
	}	

		
	/*Si existe el boton sbt_modificarAreaPuesto en el POST, entonces cargamos los datos del aspirante a la SESSION*/
	if(isset($_POST['sbt_modificarAreaPuesto'])){
		$_SESSION['datosAspirante'] = array("folio"=>strtoupper($_POST['txt_folioAspirante']),
											"nombre"=>strtoupper($_POST['txt_nombre']),
											"apePat"=>strtoupper($_POST['txt_apePat']),
											"apeMat"=>strtoupper($_POST['txt_apeMat']),
											"curp"=>strtoupper($_POST['txt_curp']),
											"edad"=>$_POST['txt_edad'],
											"edoCivil"=>strtoupper($_POST['txt_edoCivil']),
											"experiencia"=>strtoupper($_POST['txa_experiencia']),
											"fecha"=>strtoupper($_POST['txt_fechaSolicitud']),
											"lugarNac"=>strtoupper($_POST['txt_lugarNac']),
											"nacionalidad"=>strtoupper($_POST['txt_nacionalidad']),
											"tel"=>$_POST['txt_tel'],
											"telRef"=>$_POST['txt_telRef'], 
											"observaciones"=>strtoupper($_POST['txa_observaciones']));							
		
		//Redireccionar al formulario donde se ingresan el Area y el Puesto recomendados para el aspirante que se esta registrando
		echo "<meta http-equiv='refresh' content='0;url=frm_modificarPuestoAspirante.php'>";
	}	
	
	
	/*****************************************************************************************************
	 ****************ESTE CODIGO SE EMPLEA EN LA PAGINA frm_modificarContactoAspirante.php****************
	 *****************************************************************************************************/
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
		
		//Verificar que cuando se agregue un registro duplicado se le notifique al usuario
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
			//$_SESSION['datosPuestoAspirante'][1] = [areaRecomendada]=>"Administracion", [puestoRecomendado]=>"Pagos"
			else
				$msgAreaPuesto = "El Puesto $puesto Ya Fue Registrado en el &Aacute;rea $area";	
		}
		else{//Definir el Arreglo y agregar el Primer registro
			$_SESSION['datosPuestoAspirante'] = array(array("areaRecomendada"=>$area,"puestoRecomendado"=>$puesto));
			//$_SESSION['datosPuestoAspirante'][0] = [areaRecomendada]=>"Sistemas", [puestoRecomendado]=>"Desarrollo"	
		}																												
	}
	
	//Si esta el numero de registro en la URL, entonces procedemos a borrarlo de la SESSION
	if(isset($_GET['noRegistro'])){
		unset($_SESSION['datosPuestoAspirante'][$_GET['noRegistro']]);//Borrar el Registro del Arreglo de SESSION
		$_SESSION['datosPuestoAspirante'] = array_values($_SESSION['datosPuestoAspirante']);//Rectificar los indices
		//Si el arreglo de SESSION se queda Vacio, quitarlo de la SESSION
		if(count($_SESSION['datosPuestoAspirante'])==0) 
			unset($_SESSION['datosPuestoAspirante']);
	}
						
	
	//Redireccionar a la pagina de registrar Aspirante y terminar el registro
	if(isset($_POST['sbt_modificarContactoAspirante'])){
		echo "<meta http-equiv='refresh' content='0;url=frm_modificarContactoAspirante.php'>";
	}
	
	
	/*****************************************************************************************************
	 ****************ESTE CODIGO SE EMPLEA EN LA PAGINA frm_modificarContactoAspirante.php****************
	 *****************************************************************************************************/
	 $msgContactoAspirante = "";
	//Agregar los Datos de los Contactos del Aspirante a la SESSION
	if(isset($_POST['sbt_registrarContactoAspirante'])){
		//Si el Arreglo datosContactoAspirante ya esta registrado en la SESSION, agregar un nuevo registro(datos de un contacto)
		if(isset($_SESSION['datosContactoAspirante'])){
		
		/*Aqui comienza la modificacion del contacto para No agregar un registro repetido*/
			$ctrlRegDuplicados = 0;//Siginifica que no existe en la SESSION
			//Verificar Que el registro no haya sido agregado previamente, recorrer cada registro del arreglo
			foreach($_SESSION['datosContactoAspirante'] as $indice => $arrContacto){
				//Comparar cada nombre del registro para ver que no se repitan
				if($arrContacto['nombreCont']==$txt_nombreCont){
					$ctrlRegDuplicados = 1;//Significa que esta duplicado
			
				}
			}
			if($ctrlRegDuplicados==0)

			$_SESSION['datosContactoAspirante'][] = array("nombreCont"=>strtoupper($_POST['txt_nombreCont']),
														  "calle"=>strtoupper($_POST['txt_calle']),
														  "numExt"=>strtoupper($_POST['txt_numExt']),
														  "numInt"=>strtoupper($_POST['txt_numInt']),
														  "colonia"=>strtoupper($_POST['txt_colonia']),
														  "estado"=>strtoupper($_POST['txt_estado']),
														  "pais"=>strtoupper($_POST['txt_pais']),
														  "tel"=>$_POST['txt_tel']);
			else
				$msgContactoAspirante = "El Contacto $txt_nombreCont Ya Fue Registrado anteriormente";	
		}	
		else{//Guardar el primer contacto en el arreglo datosContactoAspirante definido en la SESSION
			$_SESSION['datosContactoAspirante'] = array(array("nombreCont"=>strtoupper($_POST['txt_nombreCont']),
															  "calle"=>strtoupper($_POST['txt_calle']),
														  	  "numExt"=>strtoupper($_POST['txt_numExt']),
														  	  "numInt"=>strtoupper($_POST['txt_numInt']),
														  	  "colonia"=>strtoupper($_POST['txt_colonia']),
														  	  "estado"=>strtoupper($_POST['txt_estado']),
														  	  "pais"=>strtoupper($_POST['txt_pais']),
														  	  "tel"=>$_POST['txt_tel']));
		}					
		
	}	
	
	//Si esta el numero de registro en la URL, entonces procedemos a borrarlo de la SESSION
	if(isset($_GET['noRegContacto'])){
		unset($_SESSION['datosContactoAspirante'][$_GET['noRegContacto']]);//Borrar el Registro del Arreglo de SESSION
		$_SESSION['datosContactoAspirante'] = array_values($_SESSION['datosContactoAspirante']);//Rectificar los indices
		//Si el arreglo de SESSION se queda Vacio, quitarlo de la SESSION
		if(count($_SESSION['datosContactoAspirante'])==0) 
			unset($_SESSION['datosContactoAspirante']);
	}
		
	
	if(isset($_POST['sbt_finalizarModificacionContacto'])){		
		//Redireccionar al formulario donde se ingresan el Area y el Puesto recomendados para el aspirante que se esta registrando
		echo "<meta http-equiv='refresh' content='0;url=frm_modificarAspirante.php'>";
	}
	
	
	
	/******************************************************************************************************
	 *************************************DECLARACION DE FUNCIONES*****************************************
	 ******************************************************************************************************/	
	/*Esta funcion consulta los datos del Aspirante en la Base de Datos y los carga al formulario*/	
	function buscarAspirantes($nomAspirante){
		//Realizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");

		//Crear sentencia SQL
		$stm_sql = "SELECT * FROM bolsa_trabajo JOIN area_puesto ON folio_aspirante=bolsa_trabajo_folio_aspirante WHERE CONCAT(nombre,' ',ap_paterno,' ',ap_materno) = '$nomAspirante'";
		//Ejecutar la sentencia creada
		$rs = mysql_query($stm_sql);
		//Si se obtiene un resultado de la busqueda, cargar los datos a las variables para ser mostradas en el formulario de Modificar Aspirante
		if($datos_aspirante=mysql_fetch_array($rs)){
			return $datos_aspirante;
		}
		else{
			return "Error";
		}
		mysql_close($conn);
	}
	
	
	//Funcion que cargara los datos del área y Puesto del Aspirante 
	function cargarAreaPuesto($txt_folioAspirante){
		//Relaizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");
		
		//Crear la sentencia SQL
		$stm_sql = "SELECT * FROM area_puesto WHERE  bolsa_trabajo_folio_aspirante = '$txt_folioAspirante' ";
		//Ejecutar la sentencia creada
		$rs = mysql_query($stm_sql);
		//Si se obtiene un resultado de la busqueda, cargar los datos a las variables para ser mostradas en el formulario de Modificar Área y Puesto
		if($datos_puesto=mysql_fetch_array($rs)){
			do{
				//Si ya esta definido en arreglo en la SESSION, agregar los nuevos registros
				if(isset($_SESSION['datosPuestoAspirante'])){
					$_SESSION['datosPuestoAspirante'][] = array("areaRecomendada"=>$datos_puesto['area'],"puestoRecomendado"=>$datos_puesto['puesto']);
				}
				else{//Definir el Arreglo y agregar el Primer registro
					$_SESSION['datosPuestoAspirante'] = array(array("areaRecomendada"=>$datos_puesto['area'],"puestoRecomendado"=>$datos_puesto['puesto']));
				}
			}while($datos_puesto=mysql_fetch_array($rs));
		}
		
		mysql_close($conn);			
	}
	
	
	/*Esta función muestra las Areas y Puestos Recomendados para el Aspirante*/
	function mostrarPuestosAspirante(){
		echo "<table cellpadding='5'>";
		echo "<caption class='titulo_etiqueta'>Puestos Recomendados para el Aspirante</caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center' >NO.</td>
        		<td class='nombres_columnas' align='center' >&Aacute;REA</td>
			    <td class='nombres_columnas' align='center' >PUESTO</td>
			    <td class='nombres_columnas' align='center' >ELIMINAR</td>
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
					<td class='$nom_clase'align='left'>$regAreaPuesto[puestoRecomendado]</td>";?>				
					<td class="<?php echo $nom_clase; ?>" align="center">
						<input type="image" src="../../images/borrar.png" width="30" height="25" border="0" title="Borrar Registro" title="Eliminar Área y Puesto del Aspirante" 
						onClick="location.href='frm_modificarPuestoAspirante.php?noRegistro=<?php echo $cont-1; ?>';" />
					</td>
				</tr><?php									
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";		
	}
	
	
	/*Esta funcion carga los datos de los contactos asociados al Aspirante*/
	function cargarContactos($txt_folioAspirante){
		//Realizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");
		
		//Crear la sentencia SQL
		$stm_sql = "SELECT * FROM contacto WHERE bolsa_trabajo_folio_aspirante = '$txt_folioAspirante' ";
		
		//Ejecutar la sentencia creada
		$rs = mysql_query($stm_sql);
		//Si se obtiene un resultado de la busqueda, cargar los datos a las variables para ser mostradas en el formulario de Modificar Contacto
		if($datos_contacto=mysql_fetch_array($rs)){
			do{
				//Si ya esta definido en arreglo en la SESSION, agregar los nuevos registros
				if(isset($_SESSION['datosContactoAspirante'])){
					$_SESSION['datosContactoAspirante'][] = array("nombreCont"=>$datos_contacto['nom_contacto'],
																"calle"=>$datos_contacto['calle'],
																"numExt"=>$datos_contacto['num_ext'],
																"numInt"=>$datos_contacto['num_int'],
																"colonia"=>$datos_contacto['colonia'],
																"estado"=>$datos_contacto['estado'],
																"pais"=>$datos_contacto['pais'],
																"tel"=>$datos_contacto['telefono']);
				}
				else{//Definir el Arreglo y agregar el Primer registro
					$_SESSION['datosContactoAspirante'] = array(array("nombreCont"=>$datos_contacto['nom_contacto'],
																	"calle"=>$datos_contacto['calle'],
																	"numExt"=>$datos_contacto['num_ext'],
																	"numInt"=>$datos_contacto['num_int'],
																	"colonia"=>$datos_contacto['colonia'],
																	"estado"=>$datos_contacto['estado'],
																	"pais"=>$datos_contacto['pais'],
																	"tel"=>$datos_contacto['telefono']));
				}
			}while($datos_contacto=mysql_fetch_array($rs));
		}
		
		mysql_close($conn);			
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
				<td class='nombres_columnas' align='center'>ELIMINAR</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;	
		
		foreach($_SESSION['datosContactoAspirante'] as $ind => $regContactosAspirante) {
			//Desplegar el nombre de los Contactos 
			echo "
				<tr>
					<td class='$nom_clase'>$cont</td>
					<td class='$nom_clase' align='left'>$regContactosAspirante[nombreCont]</td>
					<td class='$nom_clase' align='left'>$regContactosAspirante[calle]</td>
					<td class='$nom_clase' align='center'>$regContactosAspirante[numExt]</td>
					<td class='$nom_clase' align='center'>$regContactosAspirante[numInt]</td>	
					<td class='$nom_clase' align='left'>$regContactosAspirante[colonia]</td>
					<td class='$nom_clase' align='left'>$regContactosAspirante[estado]</td>
					<td class='$nom_clase' align='left'>$regContactosAspirante[pais]</td>
					<td class='$nom_clase' align='left'>$regContactosAspirante[tel]</td>";?>				
					<td class="<?php echo $nom_clase; ?>" align="center">
						<input type="image" src="../../images/borrar.png" width="30" height="25" border="0" title="Borrar Registro" title="Eliminar Contacto del Aspirante" 
						onClick="location.href='frm_modificarContactoAspirante.php?noRegContacto=<?php echo $cont-1; ?>';" />
					</td>
				</tr><?php			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";		
	}
			
		
	/*Funcion que se encarga de Actualizar el registro del Aspirante cuando sea modificado.*/
	function registrarAspirante(){
		//Abrimos la Conexión a la bd de Recursos Humanos
		$conn = conecta("bd_recursos");
						
		
		//Nos traemos la información que se va agregar a a la BD desde el vector $_POST[] ya que esta información vienen desde el formulario frm_registrarAspirante en el $_POST[]
		$folioAspirante =  $_POST['txt_folioAspirante'];
		$nombre = strtoupper($_POST['txt_nombre']);
		$apePat = strtoupper($_POST['txt_apePat']);
		$apeMat = strtoupper($_POST['txt_apeMat']);
		$curp =  strtoupper($_POST['txt_curp']);
		$edoCivil = strtoupper($_POST['txt_edoCivil']);
		$experiencia = strtoupper($_POST['txa_experiencia']);
		$lugarNac = strtoupper( $_POST['txt_lugarNac']);
		$nacionalidad = strtoupper($_POST['txt_nacionalidad']);
		$observaciones = strtoupper($_POST['txa_observaciones']);		
		//Por el alcance de las variables se tiene que hacer referencia a estas variables con el vector $_POST[]
		$edad = $_POST['txt_edad'];
		$fechaSolicitud = modFecha($_POST['txt_fechaSolicitud'],3);
		$tel = $_POST['txt_tel'];
		$telRef = $_POST['txt_telRef'];	
		
		
		
		//Crear la Sentencia UPDATE para actualizar los datos generales del Aspirante
		$stm_sql = "UPDATE bolsa_trabajo SET nombre='$nombre', ap_paterno='$apePat', ap_materno='$apeMat', curp='$curp', edad='$edad', 
		estado_civil='$edoCivil', experiencia_laboral='$experiencia', fecha_solicitud='$fechaSolicitud', lugar_nac='$lugarNac', nacionalidad='$nacionalidad', telefono='$tel', tel_referencia='$telRef', 
		observaciones='$observaciones' WHERE folio_aspirante = '$folioAspirante'";
		
		//Ejecutar la sentencia previamente creada
		$datos = mysql_query($stm_sql);
	
		//Confirmar que la inserción de datos fue realizada con exito.
		if($datos){ 									
			//Guardar las Areas y Pueslos asociados al Aspirante
			registrarAreaPuesto($folioAspirante);
			registrarOperacion("bd_recursos",$folioAspirante,"ModificarAspiranteBolsaTrabajo",$_SESSION['usr_reg']);					
							
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					
		//Cerrar la conexion con la BD
		mysql_close($conn);
		}
	}
	
	
		
	/*Esta funcion se encarga de guardar las Areas y Puestos Recomendados para el Aspirante*/
	function registrarAreaPuesto($folioAspirante){			
		//Borra el registro previo
		mysql_query("DELETE FROM area_puesto WHERE bolsa_trabajo_folio_aspirante = '$folioAspirante'");
		
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
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	
	/*uncion que se encarga de registrar el ó los contactos del Aspirante*/
	function registrarContacto($folioAspirante){	
		//Borra el registro previo
		mysql_query("DELETE FROM contacto WHERE bolsa_trabajo_folio_aspirante = '$folioAspirante'");
		
		//Variable para el manejo de los errores
		$ctrl_errores = 0;
		
		//Guardar cada Area y Puesto Asociados al Aspirante 
		foreach($_SESSION['datosContactoAspirante'] as $key => $regContacto){		
									
			//Creamos la sentencia SQL para guardar el Contacto del Aspirante en BD de recursos
			$stm_sql = "INSERT INTO contacto (bolsa_trabajo_folio_aspirante, nom_contacto, calle, num_ext, num_int, colonia, estado, pais, telefono) 
			VALUES ('$folioAspirante', '$regContacto[nombreCont]', '$regContacto[calle]', '$regContacto[numExt]', '$regContacto[numInt]', '$regContacto[colonia]', 
					'$regContacto[estado]', '$regContacto[pais]', '$regContacto[tel]')";
			
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
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
				
	}


	
?>