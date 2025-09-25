<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:20/Febrero/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de donde se Generan los Tipos de Permisos
	**/
	
	//Funcion para consultar la informacion del Plan de Contingencia ya sea por fecha o por Clave dle Plan
	function consultarPlanContingencia(){
	
		//Conectar a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//variables para manipular la consulta que será ejecutada y la que será regresada en caso de que haya datos
		$sql_stm = "";
		$consulta = "";
		
		//Si se consultan todos los Planes de Contingencia existentes, verificar que venga en el Post el boton 'sbt_consultarPlan'
		if(isset($_POST['sbt_consultarPlan'])){
		
			//Se recuperan los datos que se encuentran en el POS			
			$fechaReg = modFecha($_POST['txt_fechaReg'],3);
			$fechaProg = modFecha($_POST['txt_fechaProg'],3);
			
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM planes_contingencia WHERE fecha_reg>='$fechaReg' AND fecha_programada<='$fechaProg' AND planes_contingencia.estado = 'NO' 
			ORDER BY id_plan";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "<label align='center' class='msje_correcto'> Planes de Contingencia Programados del &nbsp; <em><u> ".$_POST['txt_fechaReg']." </u></em>&nbsp; al &nbsp;
			 <em><u>".$_POST['txt_fechaProg']." </u></em></label>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label align='center' class='msje_correcto' >No se Encontr&oacute; Ning&uacute;n Plan de Contingencia Registrado del
			<em><u>  ".$_POST['txt_fechaReg']."</u></em> al <em><u>  ".$_POST['txt_fechaProg']." </u></em></label>";	
		}
		
		//Si se consultan solo por clave del Plan de Contingencia, verificar que venga en el Post el boton 'sbt_consultarIdPlan'
		else if(isset($_POST['sbt_consultarIdPlan'])){
			//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
			$idPlanCont = $_POST['cmb_idPlan'];

			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM planes_contingencia WHERE id_plan = '$idPlanCont' AND estado = 'NO' ORDER BY id_plan";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "<label class='msje_correcto' align='center'> Plan de Contingencia Programado con Clave &nbsp;&nbsp; <em><u>  $idPlanCont </u></em>&nbsp;&nbsp;</label>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label align='center' class='msje_correcto'> El Plan de Contingencia con la Clave  &nbsp; <em><u>  $idPlanCont  </u></em> &nbsp; Ya se encuentra 
			Registrado como Ejecutado ó Realizado</label>";	
		}			
		
		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
		
			//Guardar la consulta cuando la que fue ejecutada regreso datos para mostrar
			$consulta = $sql_stm;
	
			//Desplegar los resultados de la consulta en una tabla
			echo "<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='11' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>MODIFICAR</td>
					<td class='nombres_columnas' align='center'>CLAVE PLAN</td>
					<td class='nombres_columnas' align='center'>RESPONSABLE</td>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>LUGAR</td>
					<td class='nombres_columnas' align='center'>SIMULACRO REALIZADO</td>
					<td class='nombres_columnas' align='center'>NOMBRE SIMULACRO</td>
					<td class='nombres_columnas' align='center'>TIPO SIMULACRO</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
					<td class='nombres_columnas' align='center'>FECHA PROGRAMADA</td>
					<td class='nombres_columnas' align='center'>DESCARGAR</td>
					<td class='nombres_columnas' align='center'>EJECUCIÓN DEL PLAN</td>															
				</tr>";			
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
			$clvPlan = $datos['id_plan'];
			$sql_stmArch = "SELECT id_documento FROM detalle_contingencia WHERE planes_contingencia_id_plan = '$clvPlan'";
			$rsArch  = mysql_query($sql_stmArch);
			$datosArch = mysql_fetch_array($rsArch);
			
			echo "<tr>	
						<td class='$nom_clase' align='center'>";?>
							<input type="radio" id="rdb_idPlanContingencia" name="rdb_idPlanContingencia"
								value="<?php echo $datos['id_plan'];?>" onclick="frm_exportarDatos.hdn_btnCambiar.value='radio'; cambiarSubmitPlan();"/><?php
				echo "</td>			 
						<td class='$nom_clase' align='center'>$datos[id_plan]</td>
						<td class='$nom_clase' align='center'>$datos[responsable]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[lugar]</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
						<td class='$nom_clase' align='center'>$datos[nom_simulacro]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_simulacro]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_reg'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_programada'],1)."</td>";
						if($datosArch['id_documento']== 'N/A'){?>
						<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
								title="Plan con Pasos Registrados" disabled="disabled"/>							
						</td>
						<?php }
						else{?>
							<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
								title="Descargar Documento<?php echo $datosArch['id_documento'];?>" 
								onClick="javascript:window.open('marco_descargaPlanCont.php?id_documento=<?php echo $datosArch['id_documento'];?>',
								'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<?php } ?>
						<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_regPlanEjecutado" class="botones_largos" value="Registro Tiempos" onMouseOver="window.estatus='';return true" 
								title="Registro de los Tiempos del Plan Ejecutado" 
								onClick="location.href='frm_complementarPlanContingencia.php?clavePlan=<?php echo $clvPlan; ?>'"/>							
							</td>
						<?php
					"</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";?><?php
			
			return $consulta;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo  $msg_error;		
			return $consulta;		
		}
	}

//if(!isset($_SESSION["datosPlanContingencia"]) && !isset($_SESSION["datosGralPlan"])){

	//Desplegar la informacion que muestra los Planes de contingencia de acuerdo al tipo de consulta realizada por el usuario
	function mostrarRegPlanContingencia($clavePlan){
		echo "
			<table cellpadding='5' width='100%'>
				<caption><p class='msje_correcto'><strong>Pasos del Plan de Contingencia</strong></p></caption>   			
				<tr>
					<td class='nombres_columnas' align='center'>BORRAR</td>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>PASO</td>
					<td class='nombres_columnas' align='center'>ACCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>RESPONSABLE</td>
					<td class='nombres_columnas' align='center'>SIMULACRO</td>					
					<td class='nombres_columnas' align='center'>COMENTARIOS</td>
				</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$contar = count($_SESSION['datosPlanContingencia']);
		foreach($_SESSION['datosPlanContingencia'] as $ind => $registro) {
			$atrOnClick = "";
			if($contar!=1){
				$atrOnClick = "location.href='frm_modificarPlanContingencia2.php?noRegistro=".($cont-1)."&clavePlan=$clavePlan'";
				echo "
					<tr>
						<td class='$nom_clase' align='center'>";?>
							<input type="image" src="../../images/borrar.png" width="30" height="25" border="0" title="Borrar Registro"
							onclick="<?php echo $atrOnClick;  ?>" />
			<?php }//LLave de Cierre del if($contar!=1){
			else {?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Descripción del último Paso  No se puede Borrar, Registre uno Nuevo para Borrar el Anterior')", 500);
				</script><?php 		 						
			echo "
					</td>
					<td align='center' class='$nom_clase' align='center'>1° PASO</td>";?>
			<?php } //Cierre del else => if($contar!=1){
			
			echo"		
					<td align='center' class='$nom_clase' align='center'>$cont</td>
					<td align='center' class='$nom_clase' align='center'>$registro[paso]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[accion]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[resAccion]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[simulacro]</td>
					<td align='center' class='$nom_clase' align='center'>$registro[comentarios]</td>
				</tr>";?><?php 			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
				
		}//Cierre foreach($datosPlanContingencia as $ind => $registro)
		echo "</table>";
	}//Fin de la funcion mostrarRegPlanContingencia()	

//}	

	/***********************************************FUNCION QUE MODIFICA LA INFORMACION DEL PLAN DE CONTINGENCIA*********************************/

	//Funcion que modifica la informacion del Plan de Contingencia
	function modificarPlanContingencia(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de Seguridad
		$conn = conecta("bd_seguridad");
		
		//OBtener las variables que vienen en el POST		
		$idPlan = $_POST['txt_idPlan'];
		//Obtener las variables que vienen en la SESSION				
		$resSim = $_SESSION['datosGralPlan']['resSim'];
		$fechaReg = modFecha($_SESSION['datosGralPlan']['fechaReg'],3);
		$fechaProg = modFecha($_SESSION['datosGralPlan']['fechaProg'],3);
		$area = $_SESSION['datosGralPlan']['area'];
		$lugar = $_SESSION['datosGralPlan']['lugar'];
		$nomSim = $_SESSION['datosGralPlan']['nomSim'];
		$tipoSim = $_SESSION['datosGralPlan']['tipoSim'];
	
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	
		//Crear la Sentencia SQL que Elimine la informacion de la tabla de planes_contingencia de la BD de Seguridad
 		$stm_sqlPlan= "DELETE  FROM planes_contingencia WHERE id_plan = '$idPlan'";	     
		//Crear la Sentencia SQL que Elimine la informacion de la tabla de detalle_contingencia de la BD de Seguridad
 		$stm_sqlDetalle = "DELETE  FROM detalle_contingencia WHERE planes_contingencia_id_plan = '$idPlan'";	     		 
				 
		//Ejecutar la sentencia que elimine los datos generales del plan de Contingencia seleccionado
		$rsPlan = mysql_query($stm_sqlPlan);
		//Ejecutar la sentencia que elimina el detalle del plan de contingencia
		$rsDetalle = mysql_query($stm_sqlDetalle);
		
		
		
		//Crear la sentencia que Agregue o registre la nueva informacion tanto en la tabla de planes_contingencia como en la de detalle_contingencia
		$stm_sqlModPlan= "INSERT INTO planes_contingencia (id_plan, responsable, area, lugar, nom_simulacro, tipo_simulacro, fecha_reg, fecha_programada, estado)
					VALUES ('$idPlan', '$resSim', '$area', '$lugar', '$nomSim', '$tipoSim', '$fechaReg', '$fechaProg', 'NO')";	     
		
		//Ejecutar la sentencia donde se modifican los datos del plan de contingencia			
		$rsModPlan = mysql_query($stm_sqlModPlan);	
							
		//Si la ejecucion de la consulta No trae resultados     
		if ($rsModPlan){
			//Ciclo que permite registrar los pasos del plan de contingencia que se este modificando
			foreach($_SESSION['datosPlanContingencia'] as $key => $pasosPlan){
				//Crear la Sentencia SQL para almacenar el detalle del plan de contingencia
				$stm_sqlModDet = "INSERT INTO detalle_contingencia (planes_contingencia_id_plan, paso, accion, responsable, simulacro, comentarios,  id_documento)
				VALUES ('$idPlan','$pasosPlan[paso]','$pasosPlan[accion]', '$pasosPlan[resAccion]','$pasosPlan[simulacro]', '$pasosPlan[comentarios]', 'N/A')";
				
				//Ejecutar la sentencia donde se modifica el detalle del Plan de Contingencia
				$rsModDet = mysql_query($stm_sqlModDet);
				if (!$rsModDet)
					break;
			}//Cierre foreach($_SESSION['datosPlanContingencia'] as $key => $pasosPlan)
		}//Cierre if ($rsModPlan)
		
		//Verificar Resultado de la ejecucion de las consultas de Modificar tabla planes_contingencia y detalles_contingencia
		if ($rsModPlan && $rsModDet){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPlan,"ModificarPlanContingencia",$_SESSION['usr_reg']);	
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarPlanContingencia()
	 
	 	
	
	
	
	//Funcion que modifica el archivo vinculado al Plan de Contingencia
	function modificarArchivoPlanContingencia(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de Seguridad
		$conn = conecta("bd_seguridad");
		
		//OBtener las variables que vienen en el POST		
		$idPlan = $_POST['txt_idPlan'];
		$archivo = $_POST['txt_archivos'];
		
		//Obtener las variables que vienen en la SESSION				
		$resSim = $_SESSION['datosGralPlan']['resSim'];
		$fechaReg = modFecha($_SESSION['datosGralPlan']['fechaReg'],3);
		$fechaProg = modFecha($_SESSION['datosGralPlan']['fechaProg'],3);
		$area = $_SESSION['datosGralPlan']['area'];
		$lugar = $_SESSION['datosGralPlan']['lugar'];
		$nomSim = $_SESSION['datosGralPlan']['nomSim'];
		$tipoSim = $_SESSION['datosGralPlan']['tipoSim'];
		
		;

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	
		//Crear la Sentencia SQL que Elimine la informacion de la tabla de planes_contingencia de la BD de Seguridad
 		$stm_sqlArchPlan= "DELETE  FROM planes_contingencia WHERE id_plan = '$idPlan'";	     
		//Crear la Sentencia SQL que Elimine la informacion de la tabla de detalle_contingencia de la BD de Seguridad
 		$stm_sqlArchDetalle = "DELETE  FROM detalle_contingencia WHERE planes_contingencia_id_plan = '$idPlan'";	     		 
				 
		//Ejecutar la sentencia que elimine los datos generales del plan de Contingencia seleccionado
		$rsArchPlan = mysql_query($stm_sqlArchPlan);
		//Ejecutar la sentencia que elimina el detalle del plan de contingencia
		$rsArchDetalle = mysql_query($stm_sqlArchDetalle);
		
				
		//Crear la sentencia que Agregue o registre la nueva informacion tanto en la tabla de planes_contingencia como en la de detalle_contingencia
		$stm_sqlModArchPlan= "INSERT INTO planes_contingencia (id_plan, responsable, area, lugar, nom_simulacro, tipo_simulacro, fecha_reg, fecha_programada, estado)
					VALUES ('$idPlan', '$resSim', '$area', '$lugar', '$nomSim', '$tipoSim', '$fechaReg', '$fechaProg', 'NO')";	     
		
		//Ejecutar la sentencia donde se modifican los datos del plan de contingencia			
		$rsModArchPlan = mysql_query($stm_sqlModArchPlan);	
							
		//Si la ejecucion de la consulta No trae resultados     
		if ($rsModArchPlan){
				//Crear la Sentencia SQL para almacenar el detalle del plan de contingencia
				$stm_sqlModArchDet = "INSERT INTO detalle_contingencia (planes_contingencia_id_plan, paso, accion, responsable, simulacro, comentarios,  id_documento)
				VALUES ('$idPlan','N/A','N/A', 'N/A','N/A', 'N/A', '$archivo')";
				
				//Ejecutar la sentencia donde se modifica el detalle del Plan de Contingencia
				$rsModArchDet = mysql_query($stm_sqlModArchDet);
				if (!$rsModArchDet)
					break;
		}//Cierre if ($rsModPlan)
		
		//Verificar Resultado de la ejecucion de las consultas de Modificar tabla planes_contingencia y detalles_contingencia
		if ($rsModArchPlan){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPlan,"ModArchPlanContingencia",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		
		
	 }// Fin function registrarPlanContingencia()
	
			
		
	/***********************************************REGSITRO DE LOS TIEMPOS CUANDO EL PLAN DE CONTINGENCIA ES EJECUTADO*************************/

	
	//Funcion que se utiliza para complmentar el plan, es decir Registra los tiempos del Plan cuando este es realizado o ejecutado
	function complementarPlanEjecutado(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de Seguridad
		$conn = conecta("bd_seguridad");
		
		//OBtener las variables que vienen en el POST		
		//$idPlan = ($_POST['txt_idPlan']);
		$fechaEje = modFecha($_POST['txt_fechaEjecucion'],3);
		
		//Estas variables son utilizadas para actualizar el complemento del plan cuando este es ejecutado o realizado
		$tiempoTotal = strtoupper($_POST['txt_tiempoTotal']);
		$comentarios = strtoupper($_POST['txa_comentarios']);
		$observaciones = strtoupper($_POST['txa_observaciones']);		
		$idPlan = ($_POST['txt_idPlan']);		

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	
		//Cargar la Imagenes que evidencien el tipo de permiso
		$foto_info_1 = cargarImagen("txt_foto1");//Cargar la Imagen 1
		$foto_info_2 = cargarImagen("txt_foto2");//Cargar la Imagen 2
		$foto_info_3 = cargarImagen("txt_foto3");//Cargar la Imagen 3
		$foto_info_4 = cargarImagen("txt_foto4");//Cargar la Imagen 4
		$foto_info_5 = cargarImagen("txt_foto5");//Cargar la Imagen 5
			

		//Crear la Sentencia SQL para Alamcenar los datos del Plan de ContinegenciA
		$stm_sql= "INSERT INTO tiempos_simulacro (planes_contingencia_id_plan, fecha_realizado, comentarios, observaciones, tiempo_total, 
				evidencia1, mime1, evidencia2, mime2,  evidencia3, mime3, evidencia4, mime4,  evidencia5, mime5)
			VALUES ('$idPlan', '$fechaEje', '$comentarios', '$observaciones', '$tiempoTotal', '$foto_info_1[foto]', '$foto_info_1[type]', '$foto_info_2[foto]', 
				'$foto_info_2[type]', '$foto_info_3[foto]', '$foto_info_3[type]', '$foto_info_4[foto]', '$foto_info_4[type]','$foto_info_5[foto]', '$foto_info_5[type]')";	 
		
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);

		//Verificar Resultado
		if ($rs)	
			//Crear una sentencia al momenento de Complementar el Plan de COntingencia para que cambie el valor de estado no ejecutado = NO a estado ejecutado = SI
			$sql_stmAct = "UPDATE planes_contingencia SET estado = 'SI' WHERE estado = 'NO' AND id_plan = '$idPlan'";
			//Ejecutar la sentencia donde se ejecuta la actualizacion
			$rsAct = mysql_query($sql_stmAct);
			
		//Verificar Resultado de la ejecucion de las consultas de Modificar tabla planes_contingencia y detalles_contingencia
		if ($rs && $rsAct){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPlan,"CompPlanEjecutado",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
	 }// Fin function registrarPlanContingencia()	
	 

	//Esta funcion se encarga de convertir la imagen en un flujo binario para ser almacenada en la BD de Seguridad
	function cargarImagen($nomInputFile){
		// Mime types permitidos: todos los navegadores==>'image/gif', IE==>'image/x-png' y 'image/pjpeg', Mozilla Firefox==>'image/png' y 'image/jpeg'
		$mimetypes = array("image/gif", "image/x-png", "image/png", "image/pjpeg", "image/jpeg");
			
		$name = $_FILES[$nomInputFile]["name"];
		$type = $_FILES[$nomInputFile]["type"];
		$tmp_name = $_FILES[$nomInputFile]["tmp_name"];
		$size = $_FILES[$nomInputFile]["size"];		
		
		//Verificamos si el archivo es una imagen válida y que el tamaño de la misma no exceda los 10,000 Kb 10,240,000 Bytes
		if(in_array($type, $mimetypes) && $size<10240000){							
			
			//Cargar y Redimensionar la Imagen en el Directorio "../seg/documentos/temp"
			$archivoRedimensionado = cargarFotosEvidenciaPlan($nomInputFile);									
			
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
	function cargarFotosEvidenciaPlan($nomInputFile){		
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
	 	

?>	