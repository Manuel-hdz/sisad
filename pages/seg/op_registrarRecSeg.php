<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 15/Febrero/2012
	  * Descripción: Este archivo permite registrar la informacion relacionada con los recorridos de seguridad
	  **/
	 	
	//Esta funcion genera la Clave de la acta de acuerdo a los registros en la BD
	function obtenerIdRS(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "REC";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_recorrido) AS cant FROM recorridos_seguridad WHERE id_recorrido LIKE 'REC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	
	
	
	//Funcion que nos permite obntener el id del detalle del registro 
	function obtenerIdDetRS(){
	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
			
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_detalle_recorrido_seguridad)+1 AS cant FROM detalle_recorridos_seguridad";
		
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
	
	function obtenerIdAlertaRS(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "ARC";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_alerta_recorrido) AS cant FROM alertas_recorridos_seguridad WHERE id_alerta_recorrido LIKE 'ARC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	
	
	
	
	//Esta funcion permite subir Archivos que forman parte del registro de la bitacora
	function subirFotos($clave, $anomalia, $cont){
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		$band=true;
	
		//Verificamos el tipo de archivo
		if((substr($_FILES['file_documento']['type'],0,5) != 'image')){
			?>
				<script>
					setTimeout("alert('El Formato del Archivo no es Válido. Sólo se Permiten Imágenes');",500);
				</script>
			<?php			 
			$band = false;
			
		}
		//De lo contrario el archivo es valido y se procedera a subir el archivo en el lugar correspondiente
		else{
			//Creamos la variable ruta que servira para abrir la misma	
			$Ruta='';	
				
			//Creamos la carpeta inicial dentro de documentos
			$carpeta='documentos/'.$clave;
			//Abrimos la ruta para crear la carpeta
   			$dir = opendir($Ruta); 
			//Verificamos que el archivo haya sido updated
			 
			//Si $carpeta no ah sido creado se crea con mkdir
			if(!file_exists($carpeta."/")){
				mkdir($carpeta."/", 0777);
			}
			//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
			if (!file_exists($carpeta."/".$_FILES['file_documento']['name'])){
		    	move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta."/".$_FILES['file_documento']['name']); 
				//llamar la funcion que se encarga de reducir el peso de la fotografia 
				redimensionarFoto($carpeta."/".$_FILES['file_documento']['name'],$_FILES['file_documento']['name'],$carpeta."/",100,100);
				rename($carpeta."/".$_FILES['file_documento']['name'], $carpeta."/".$anomalia."_".$cont."_".$_FILES['file_documento']['name']);?>
				<script>
					setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
				</script>
				<?php
			}
			else{
				$band=false;?>
				<script>
					setTimeout("alert('El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe');",500);
				</script><?php
			}
		}
		
		return $band;
		
	}//Fin de la funcion subirFotos($clave)
	
	//Desplegar las fotos registradas en el apartado Registro Fotografico de la bitacora
	function mostrarFotossReg($registroFotografico, $anomalia, $tam){
	echo "<div id='tabla-fotos' class='borde_seccion2'>";
		if(isset($_SESSION["registroFotografico"]))
				$clave=$_SESSION["registroFotografico"][0]["clave"];
			echo "<table cellpadding='5' width='100%'>";
			echo "<caption><p class='msje_correcto'><strong>Registro Fotogr&aacute;fico Recorrido de Seguridad No. ". $clave."</strong></p></caption>";
			echo "      			
				<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
        			<td class='nombres_columnas' align='center'>NOMBRE ARCHIVO</td>
      			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			foreach($_SESSION['registroFotografico'] as $key => $arrVale){
				if($arrVale['anomalia']==$anomalia){
					echo "<tr>
							<td align='center'  class='$nom_clase'>$cont</td>
							<td align='center'  class='$nom_clase'>$arrVale[archivo]</td>";				
					echo "</tr>";					
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}
			}		
			echo "</table>";
		echo "</div>";
	}//Fin de la funcion 	
	
	//Desplegar los registros de anomalias registradas
	function mostrarRegRecorridos($recorridosSeg){
	if(isset($_SESSION["recorridosSeg"]))
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Detalle del Registro</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NO.</td>
        		<td class='nombres_columnas' align='center'>&Aacute;REA</td>
				<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A DETECTADA</td>
				<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A CORREGIDA</td>
				<td class='nombres_columnas' align='center'>LUGAR</td>
				<td class='nombres_columnas' align='center'>REGISTRO FOTOGR&Aacute;FICO</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($recorridosSeg as $ind => $registro) {
			echo "<tr>";
			foreach ($registro as $key => $value) {
			$reg= obtenerIdDetRS();
			if($cont!=1){
				$reg= $reg+($cont-1);
			}
				switch($key){
					case "area":
						echo "<td align='center' class='$nom_clase' align='center'>$cont</td>";
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "anomaliaDet":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "anomaliaCor":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "lugar":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center">
							<input name="btn_regFot" type="button" class="botones_largos" id="btn_regFot"  value="Registro Fotogr&aacute;fico" 
				 			title="Registro Fotogr&aacute;fico" onclick="abrirRegFotografico('<?php echo $cont;?>','<?php echo $reg;?>');"
							onmouseover="window.status='';return true" />
						</td>
						<?php 
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
	}//Fin de la funcion 	
	
	
	//Funcion que borra el arreglo de sesion y las fotos en caso de cancelar
	function borrarFotos($carpeta){
		if(isset($_SESSION['registroFotografico'])){
			foreach ($_SESSION["registroFotografico"] as $ind => $doc){
				//Variable que obtiene el nombre del Archivo
				$nombreArchivo=$doc["archivo"];
				//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
				@unlink("documentos/".$carpeta."/".$nombreArchivo);
			}
		}
	}//Fin de la funcion borrarFotos()
	
	//Funcion que borra los archivos que  han sido cargados durante la sesion si esta fue cerrada
	function borrarFotosSesion(){
		//Tomamos los valores de $carpeta que es el id de la bitacora esta funcion es para Mtto Correctivo y Prev por eso se verifica
		$carpeta=$_SESSION["registroFotografico"]["txt_claveRegFot"];
		//Recorremos el arreglo fotos en busca del nombre y estatus para eliminar la ruta correcta
		foreach ($_SESSION["registroFotografico"] as $ind => $doc){
			//Variable que obtiene el nombre del Archivo
			$nombreArchivo=$doc["archivo"];
			//Pasamos la ruta completa, ya que esta funcion borra archivos desde la pagina salir.php
			@unlink("seg/documentos/".$carpeta."/".$nombreArchivo);
		}
	}//Fin de la funcion borrarFotosSesion()
	
	
	//Esta funcion permite registrar los archivos de la bitacora en que se este trabajando
	function registrarFotosRecorrido($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		$band=0;
		//Registrar todos los materiales dados de alta en el arreglo $fotos
		foreach ($_SESSION["registroFotografico"] as $ind => $doc){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "INSERT INTO registro_fotografico(recorridos_seguridad_id_recorrido,detalle_recorridos_seguridad_id_detalle_recorrido_seguridad,nom_archivo)
			VALUES('$clave','$doc[registro]','$doc[archivo]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de registro_fotografico
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro  en el caso de que existan errores	
			if($band==1)
				break;	
		}
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		else{
			registrarOperacion("bd_seguridad",$clave,"RegistrarRecSeg",$_SESSION['usr_reg']);
			$conn = conecta("bd_seguridad");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	if(isset($_POST['sbt_guardar'])){
		registrarRecorridos();
	}
	
	 //Funcion para guardar la informacion de los Recorridos de Seguridad 
	function registrarRecorridos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
		//Recuperar la informacion del post
		$clave=$_POST['txt_clave'];
		$fecha = modFecha($_POST['txt_fecha'],3);
		$responsable = strtoupper($_POST['txt_responsable']);
		$observaciones = strtoupper($_POST['txa_observaciones']);
		$divDeptos = explode(",",$_POST['txt_ubicacion']);
		$idAlerta=obtenerIdAlertaRS();
 
		//Guardamos la fecha actual
		$fechaAct= date("Y-m-d");
		//Ciclo que permite registrar los departamentos a los cuales se les enviara dicho concepto
		foreach($divDeptos as $key => $depto){
			//Obtenemos el id del departamento
			$idDpto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "depto", $depto);
			//Realizar la conexion a la BD de Seguridad Industrial
			$conn = conecta("bd_seguridad");
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlDepo = "INSERT INTO alertas_recorridos_seguridad(id_alerta_recorrido,recorridos_seguridad_id_recorrido,catalogo_departamentos_id_departamento,
				estatus, fecha_programada)
				VALUES('$idAlerta','$clave','$idDpto','1','$fechaAct')";
								
			//Ejecutar la sentencia previamente creada 
			$rsDepto = mysql_query($stm_sqlDepo);
			if(!$rsDepto){
				borrarFotos($clave);
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
				break;			
			}
		}
		//Consulta Principal
 		$stm_sql= "INSERT INTO recorridos_seguridad (id_recorrido, fecha, responsable, observaciones )
				VALUES ('$clave', '$fecha', '$responsable',  '$observaciones')";
	
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
				//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarDetalle($clave);
		}
		else{
			borrarFotos($clave);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	 //Funcion para guardar la informacion de los Recorridos de Seguridad 
	function registrarDetalle($clave){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		$band=0;
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		 
		$fecha =date("Y-m-d");
		//Ciclo que permite registrar los departamentos a los cuales se les enviara dicho concepto
		foreach($_SESSION['recorridosSeg'] as $key => $arrreg){	
		$claveDet = obtenerIdDetRS();
			//Crear la Sentencia SQL para Alamcenar los materiales agregados 
			$stm_sqlDet= "INSERT INTO detalle_recorridos_seguridad (id_detalle_recorrido_seguridad , recorridos_seguridad_id_recorrido,area, lugar,
					 anomalia,correccion_anomalia, fecha)
					VALUES ('$claveDet', '$clave', '$arrreg[area]', '$arrreg[lugar]', '$arrreg[anomaliaDet]', '$arrreg[anomaliaCor]', '$fecha')";
	
			//Ejecutar la Sentencia
			$rsDet=mysql_query($stm_sqlDet);
			if(!$rsDet){
				$band=1;	
			}
		}
		//Verificar Resultado
		if ($band==0){
			if(isset($_SESSION['registroFotografico'])){
				//Guardar la operacion realizada
				registrarFotosRecorrido($clave);
			}
			else{
				registrarOperacion("bd_seguridad",$clave,"RegistrarRecSeg",$_SESSION['usr_reg']);
				$conn = conecta("bd_seguridad");
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}
		else{
			borrarFotos($clave);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
?>