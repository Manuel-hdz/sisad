<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 22/Febrero/2011
	  * Descripción: Permite guardar la informacion de la Bitacora en la base de datos
	**/
	
	/***********************************************************************************************************************************************/
	/*********************************************BITACORA DE MANTENIMIENTO PREVENTIVO**************************************************************/
	/***********************************************************************************************************************************************/
		
	//Comprobamos que este definido el boton guardar del Mtto Preventivo pare comenzar a guardar en la BD
	if(isset($_POST["sbt_guardar"])){
		session_start();
		registrarBitacora();
		
		if(isset($_SESSION["actividades"])){
			registrarAcciones();
		}
		if(isset($_SESSION["mecanicos"])){
			registrarMecanico();
		}
		if(isset($_SESSION["valesMtto"])){
			registrarMateriales();
		}
			
		if(isset($_SESSION["fotos"])){
			registrarFotosEquipo($_SESSION["bitacoraPrev"]["txt_claveBitacora"],$fotos);								
		}
	}
		
		
	//funcion que permite convertir los datos a mayusculas y asi mismo guardarlos en la BD del mantenimiento preventivo
	function registrarBitacora(){
		//Agregamos includes para las conexiones 
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		include("../../includes/op_operacionesBD.php");
		
		//Convertimos la fecha al formato necesario
		$fecha = date("Y-m-d");

		//Conectamos con la BD
		$conn = conecta("bd_mantenimiento");
		
		//Obtener los datos del POST, asi como convertir los necesarios a mayusculas
		$id_bitacora = $_SESSION['bitacoraPrev']['txt_claveBitacora'];
		$id_equipo = $_POST['txt_claveEquipo'];
		$fecha_mtto = modFecha($_POST["txt_fechaMant"],3);		
		
		if($_POST['txt_horometro']!="NO APLICA")
			$horometro = str_replace(",","",$_POST['txt_horometro']);
		else 
			$horometro = 0;
		
		if($_POST['txt_odometro']!="NO APLICA")		
			$odometro = str_replace(",","",$_POST['txt_odometro']);
		else
			$odometro = 0;
		
		
		$prox_mtto = modFecha($_POST["txt_proxMant"],3);
		$tiempoTotal = $_POST['txt_tiempoTotal'];		
		$costoTotal = str_replace(",","",$_POST['txt_costoTotal']);		
		$num_factura = strtoupper($_POST["txt_noFactura"]);
		$comentarios = strtoupper($_POST["txa_comentarios"]);					
				
		//Creamos la sentencia SQL para insertar los datos en la Bitacora
		$stm_sql = "UPDATE bitacora_mtto SET fecha_mtto='$fecha_mtto', horometro=$horometro, odometro=$odometro, tiempo_total='$tiempoTotal', costo_mtto=$costoTotal,
					num_factura='$num_factura', comentarios='$comentarios', prox_mtto='$prox_mtto' WHERE id_bitacora='$id_bitacora'";		
					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//verificamos que la sentencia sea ejecutada con exito
		if($rs){
			
			//Actualizar el Estado de la Orden de Trabajo para indicar que ya ha sido registrada la Bitacora de Mtto. Preventivo
			$rs = mysql_query("UPDATE orden_trabajo SET estado='1' WHERE id_orden_trabajo='$_POST[txt_ot]'");
			
			//Actualizar el Estado de la Alerta en el caso de que el equipo tenga una alerta asociada			
			regEstadoAlertaBit($id_equipo);
			
			//Actualizar el estado de la Alerta de 2 a 3, cuando el Mtto. haya sido realizado
			registrarOperacion("bd_mantenimiento",$id_bitacora,"RegistroBitacoraPrev",$_SESSION['usr_reg']);
			
			//Actualizar la Fecha de Mtto y regresar a 0 las Horas Acumuladas
			actualizarHorasAcumuladas($id_equipo,$fecha_mtto);
			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);
			
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}							
	}//Cierre de la función registrarBitacora()
	
	
	/*Esta funcion se encarga de actualizar el estado de la alerta en la BD para el Equipo que se esta registrando*/
	function regEstadoAlertaBit($id_equipo){
		//Crear consluta para verificar si el material que se esta registrado genero una alerta
		$stm_sql = "SELECT * FROM alertas WHERE equipos_id_equipo = '$id_equipo' AND estado = 2";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Evaluar los resultados y si se encuentra el material, cambiar el estado de la alerta de 2 a 3
		if($datos=mysql_fetch_array($rs)){
			mysql_query("UPDATE alertas SET estado = 3 WHERE equipos_id_equipo = '$id_equipo'");
		}			
	}//Cierre de la función regEstadoAlertaBit($id_equipo)	
	
	/*Esta funcion se encargar de actualizar el acumulado de Horas y la fecha de Mtto Preventivo por Equipo*/
	function actualizarHorasAcumuladas($id_equipo,$fecha_mtto){
		//Verificar si existe un registro previo para el Equipo
		$existe=obtenerDato("bd_mantenimiento","acumulado_servicios","hrs_acum","equipos_id_equipo",$id_equipo);
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//Verificar el valor obtenido por la funcion obtener Dato
		if($existe!="")
			//Actualizar la horas acumuladas en la BD
			$sql="UPDATE acumulado_servicios SET hrs_acum='0',fecha_mtto='$fecha_mtto' WHERE equipos_id_equipo='$id_equipo'";
		else
			//Ingresar las horas acumuladas en la BD
			$sql="INSERT INTO acumulado_servicios (equipos_id_equipo,hrs_acum) VALUES ('$id_equipo','0')";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	/***********************************************************************************************************************************************/
	/*********************************************BITACORA DE MANTENIMIENTO CORRECTIVO**************************************************************/
	/***********************************************************************************************************************************************/		
	
	//Comprobamos que este definido el boton guardar del Mtto Correctivo pare comenzar a guardar en la BD
	if(isset($_POST["sbt_guardarCorr"])){
		session_start();
		registrarBitacoraCorr();
		if(isset($_SESSION["actividades"])){
			registrarAcciones();
		}
		if(isset($_SESSION["mecanicos"])){
			registrarMecanico();
		}
		if(isset($_SESSION["valesMtto"]))
			registrarMateriales();	
		
		if(isset($_SESSION["fotos"])){
			registrarFotosEquipo($_SESSION["bitacoraCorr"]["txt_claveBitacora"],$fotos);								
		}
	}
	
	
	//Funcion que permite convertir los datos a mayusculas y asi mismo guardarlos en la BD del mantenimiento Correctivo
	function registrarBitacoraCorr(){
		//Incluimos los archivos de conexión
		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		include("../../includes/op_operacionesBD.php");
		$fecha = date("Y-m-d");
				
		//Conectamos con la BD
		$conn = conecta("bd_mantenimiento");
		
		//Llamamos los datos del post; asi como convertir los necesarios a mayusculas		
		$claveBitacora = $_POST['txt_claveBitacora'];				
		$claveOrdenTrabajo = $_POST["txt_claveOrdenTrabajo"];
		$id_equipo = $_POST["cmb_claveEquipo"];
		$tipo_mtto = $_POST['txt_tipoMant'];
		$fecha_mtto = modFecha($_POST["txt_fechaMant"],3);
		$turno = $_POST['cmb_turno'];
		
		if($_POST['txt_metrica']=="HOROMETRO"){
			$horometro = str_replace(",","",$_POST['txt_cantMet']);
			$odometro = 0;		
		}
		else if($_POST['txt_metrica']=="ODOMETRO"){
			$odometro = str_replace(",","",$_POST['txt_cantMet']);
			$horometro = 0;
		}
		
		$tiempoTotal = $_POST["txt_tiempoTotal"];
		$costo_mtto = str_replace(",","",$_POST["txt_costoTotal"]);		
		$num_factura = strtoupper($_POST["txt_noFactura"]);
		$comentarios = strtoupper($_POST["txa_comentarios"]);		
				
		//Crear la Sentencia SQL para almacenar los datos del Mtto. Correctivo		
		$stm_sql = "INSERT INTO bitacora_mtto (id_bitacora,orden_trabajo_id_orden_trabajo,equipos_id_equipo,tipo_mtto,fecha_mtto,turno,horometro,odometro,
					tiempo_total,costo_mtto,num_factura,comentarios)
					VALUES('$claveBitacora','$claveOrdenTrabajo','$id_equipo','$tipo_mtto','$fecha_mtto','$turno',
					$horometro,$odometro,'$tiempoTotal',$costo_mtto,'$num_factura','$comentarios')";
															
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//verificamos que la sentencia sea ejecutada con exito
		if($rs){
			//Si los datos fueron agregados correctamente, muestra exito
			registrarOperacion("bd_mantenimiento",$claveBitacora,"RegistroBitacoraCorr",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Cerramos la conexion con la Base de Datos
			mysql_close($conn);	
			
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}			
	}//Cierre de la función registrarBitacoraCorr()
				
	
	/***********************************************************************************************************************************************/
	/******************FUNCIONES COMUNES A LAS BITACORA DE MTTO PREVENTIVO Y CORRECTIVO PARA EL REGISTRO DE LAS MISMAS******************************/
	/***********************************************************************************************************************************************/	
	
	//Permite registrar las actividades en los mantenimientos preventivo y correctivo
	function registrarAcciones(){
		//Conectamos con la BD
		$conn = conecta("bd_mantenimiento");
		$band = 0;
		$id_bitacora = $_POST["txt_claveBitacora"];
		
		foreach($_SESSION['actividades'] as $ind => $act){
			//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
			$stm_sql = "INSERT INTO actividades_correctivas (bitacora_mtto_id_bitacora, sistema, aplicacion, descripcion)
						VALUES('$id_bitacora','$act[sistema]', '$act[aplicacion]', '$act[actividad]')";
			//Agregar los sistemas al archivo de Texto siempre y cuando no esten repetidos
			$ruta = "includes/";
			$h = opendir($ruta);
			while($file=readdir($h)){
				if($file=='listaSistemas.txt'){
					//Leer el archivo, colocando el puntero al final del mismo
					$fp = @fopen($ruta.$file, "r");
					if(!is_resource($fp))
						echo "No se Pudo Leer el Archivo '$file'";
					else{
						//Esta bandera sirve para identificar si un sistema ya existe en la Base de Datos
						$bandera = 0;
						while($line = fgets($fp)){	
							$tamLinea = strlen($line)-2;
							if(substr($line,0,$tamLinea)==$act["sistema"]){
								$bandera = 1;
								break;
							}
						}
						//Si la bandera no se activo, agregar el sistema al archivo de Texto
						if($bandera==0){							
							$fp = @fopen($ruta.$file, "a");
							$linea = $act["sistema"]."
";
							fwrite($fp,$linea);
						}
					}
				}
			}
			closedir($h);
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		if ($band==1){
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			registrarOperacion("bd_mantenimiento",$id_bitacora,"RegistroAcciones",$_SESSION['usr_reg']);								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}					
	}//Fin de la funcion registrarAcciones()
			
	
	//Funcion que permite registrar los mecanicos de los mttos Prev y Corr	
	function registrarMecanico(){
		//Conectamos con la BD
		$conn = conecta("bd_mantenimiento");
		//Declaramos la variable bandera para control de la consulta
		$band = 0;
		$id_bitacora = $_POST["txt_claveBitacora"];
		
		//Recorremos el arreglo mecanicos para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['mecanicos'] as $ind => $mec){
			//Creamos la sentencia SQL para insertar los datos en la tabla Mecanico
			$stm_sql = "INSERT INTO mecanicos (bitacora_mtto_id_bitacora,nom_mecanico)
						VALUES('$id_bitacora','$mec[mecanico]')";
					
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		
		if ($band==1){
			//Cerrar la conexion con la BD		
			mysql_close($conn);
		
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			registrarOperacion("bd_mantenimiento",$id_bitacora,"RegistroMecanicos",$_SESSION['usr_reg']);								
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}		
	}//Cierre de la función registrarMecanico()		
	
	
	//Funcion que permite registrar los los materiales de los mttos Prev y Corr	
	function registrarMateriales(){
		//Conectamos con la BD
		$conn = conecta("bd_mantenimiento");
		$band = 0;		
		
		//Recorremos el arreglo materialesMtto para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['valesMtto'] as $ind => $mat){
			$id_bitacora = $_POST["txt_claveBitacora"];
			
			//Quitamos comas para conservar numeros			
			$importe = $mat['total'];
			if(strlen($importe)>6)
				$importe = str_replace(",","",$importe);
			//Creamos la sentencia SQL para insertar los datos en la tabla Materialesmtto
			$stm_sql = "INSERT INTO materiales_mtto (bitacora_mtto_id_bitacora, id_vale, costo_vale) 
						VALUES ('$_POST[txt_claveBitacora]','$mat[noVale]','$importe')";
					
			//Ejecutar la sentencia previamente creadas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;	
		}
		
		if ($band==1){
			//Cerrar la conexion con la BD
			mysql_close($conn);
			
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{			
			registrarOperacion("bd_mantenimiento",$id_bitacora,"RegistroMateriales",$_SESSION['usr_reg']);												
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}		
	}//Cierre de la función registrarMateriales()
		
		
	//Esta funcion genera la Clave de la Bitacora de acuerdo a los registros en la BD
	function obtenerIdRegBitacora(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "BIT";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_bitacora) AS cant FROM bitacora_mtto WHERE id_bitacora LIKE 'BIT$mes$anio%'";
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
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	
	
	
	
	/***********************************************************************************************************************************************/
	/*************************************FUNCIONES PARA MOSTRAR Y CARGAR DATOS RELACIONADOS CON LAS BITACORAS**************************************/
	/***********************************************************************************************************************************************/
			
	//Desplegar las Actividades en complementar Bitacora
	function mostrarActividades($actividades){
		echo "<table cellpadding='4' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Datos agregados a la Bit&aacute;cora ".$_POST['txt_claveBitacora']."</strong></p></caption>";
		echo "      			
			<tr>
				<td width='40' class='nombres_columnas' align='center'>PARTIDA</td>
				<td class='nombres_columnas' align='center'>SISTEMA</td>
        		<td class='nombres_columnas' align='center'>APLICACION</td>
			    <td class='nombres_columnas' align='center'>ACTIVIDAD</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($actividades as $ind => $registro) {
			echo "
			<tr>
				<td align='center' colspan='1'class='nombres_filas'>$registro[partida]</td>
				<td align='center' colspan='1'class='$nom_clase'>$registro[sistema]</td>
				<td align='center' colspan='1'class='$nom_clase'>$registro[aplicacion]</td>
				<td align='center' colspan='1'class='$nom_clase'>$registro[actividad]</td>
				";
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarActividades($actividades)	
	
	
	//Desplegar los mecanicos en complementar Bitacora
	function mostrarMecanicos($mecanicos){
		echo "<table cellpadding='5' width='423'>";
		echo "<caption><p class='msje_correcto'><strong>Datos agregados a la Bit&aacute;cora ".$_POST['txt_claveBitacora']."</strong></p></caption>";
		echo "      			
			<tr>
				<td width='40' class='nombres_columnas' align='center'>PARTIDA</td>
				<td width='326' class='nombres_columnas' align='center'>M&Eacute;CANICO</td>
        	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($mecanicos as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {
				switch($key){
					case "partida":
						echo "<td align='center'  class='nombres_filas'>$value</td>";
					break;
					case "mecanico":
						echo "<td align='center' class='$nom_clase'>$value</td>";
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
	}//Fin de la funcion mostrarMecanicos($mecanicos)	
	
	
	//Registra los detalles de los mantenimientos preventivos
	function detallesRegistroMaterial(){
		//Obtenemos la clave de la orden de trabajo
		$id_ot = obtenerDato("bd_mantenimiento", "bitacora_mtto", "orden_trabajo_id_orden_trabajo", "id_bitacora", $_POST["txt_claveBitacora"]);
	
		//se realiza la conexion con la base de datos
		$conn = conecta("bd_mantenimiento");
	
		//Crear sentencia SQL
		$stm_sql = "SELECT vale_salida_id_vale_salida, partida, id_material,nom_material, cantidad, precio_unitario, unidad_medida, importe, vale_salida.fecha 
					FROM (detalle_vale_salida JOIN vale_salida on	vale_salida_id_vale_salida=id_vale_salida) WHERE orden_trabajo_id_orden_trabajo='$id_ot'";
		
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			echo "<table cellpadding='5' width='850' align='center'> 
					<caption class='titulo_etiqueta'>Materiales de la Orden de Trabajo ".$id_ot."</caption></br>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>CLAVE VALE</td>
						<td class='nombres_columnas' align='center'>CLAVE MATERIAL</td>
						<td class='nombres_columnas' align='center'>NOMBRE MATERIAL</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$materialesMtto = array();
			do{										
				echo "	
				<tr>					
					<td class='$nom_clase' align='center'>$datos[vale_salida_id_vale_salida]</td>					
					<td class='$nom_clase' align='center'>$datos[id_material]</td>
					<td class='$nom_clase' align='center'>$datos[nom_material]</td>
					<td class='$nom_clase' align='center'>";?>
						<input name="txt_cantidad<?php echo $cont-1;?>" class="caja_de_texto" size="10" maxlength="10" 
						value="<?php echo $datos['cantidad']?>"/><?php 
				echo  "		
					</td>
					<td class='$nom_clase' align='center'>$datos[precio_unitario]</td>
					<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>					
				</tr>";
					
				$precioUni = $datos['precio_unitario'];
				$importe = $datos['importe'];
				if(strlen($precioUni)>6)
					$precioUni = str_replace(",","",$precioUni);
				if(strlen($importe)>6)
					$importe = str_replace(",","",$importe);
				//Gurdar los datos en arreglo de sesion
				$materialesMtto[] = array("id_vale"=>$datos["vale_salida_id_vale_salida"], "partida"=>$datos["partida"], "id_material"=>$datos["id_material"], 
				"nom_material"=>$datos["nom_material"], "cantidad"=>$datos["cantidad"], "precio_unitario"=>$precioUni,
				"unidad_medida"=>$datos["unidad_medida"], "importe"=>$importe);
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>NO SE HAN REGISTRADO ORDENES DE TRABAJO</p>";
		}
		
		//Cerar conexion a BD
		mysql_close($conn);
		
		$_SESSION["materialesMtto"]=$materialesMtto;
	}//Cierre de la función detallesRegistroMaterial()
	
	
	//Desplegar los registros del material en la Bitacora
	function mostrarMateriales($materialesMtto){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Datos agregados a la Bit&aacute;cora </strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>CLAVE VALE</td>
				<td class='nombres_columnas' align='center'>PARTIDA</td>
				<td class='nombres_columnas' align='center'>CLAVE MATERIAL</td>
				<td class='nombres_columnas' align='center'>NOMBRE MATERIAL</td>
				<td class='nombres_columnas' align='center'>CANTIDAD</td>
				<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
				<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
				<td class='nombres_columnas' align='center'>IMPORTE</td>				
        	</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		
		foreach ($materialesMtto as $ind => $material) {
			echo "<tr>";
			foreach ($material as $key => $value) {				
				switch($key){				
					case "id_vale":
						echo "<td align='center'class='nombres_filas'>$value</td>";
					break;
					case "partida":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "id_material":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "nom_material":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "cantidad":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "precio_unitario":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "unidad_medida":
						echo "<td align='center' class='$nom_clase'>$value</td>";
					break;
					case "importe":
						echo "<td align='center' class='$nom_clase'>$value</td>";
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
	}//Fin de la funcion mostrarMateriales($materialesMtto)	
	
	
	//Funcion para actualizar la cantidad que se encuentra en el arreglo materiales mtto; para verificar si sobraron mat 
	function actualizarCantMat(){
		foreach($_SESSION["materialesMtto"] as $key=> $material){
			$indice=$key;
			foreach ($material as $key => $value) {
				switch($key){
					case "cantidad":
						$_SESSION["materialesMtto"][$indice]["cantidad"]=$_POST["txt_cantidad$indice"];
					break;
				}//Switch
			}//Foreach				
		}//Foreach
	}//Cierre de la función actualizarCantMat()
	
	
	//Cargar los materiales incluidos en el vale con el No. de vale proporcionado
	function cargarMateriales($claveVale){
		//Si el registro actual no incluye materiales guardar el dato en la SESSION y redireccionar a la página correspondiente
		if($claveVale=="N/A"){
			//Esta variable de SESSION indicará que el registro actual de la Bitácora de Mtto Preventivo o Correctivo no incluye materiales
			$_SESSION['regSinValeMtto'] = "SI";
			
			//Redireccionar a la pagina, dependiendo del tipo de Mtto que esta siendo registrado
			if(isset($_SESSION['bitacoraPrev']))
				echo "<meta http-equiv='refresh' content='0;url=frm_bitacoraMttoPreventivo.php'>";
			else if(isset($_SESSION['bitacoraCorr']))
				echo "<meta http-equiv='refresh' content='0;url=frm_bitacoraMttoCorrectivo.php'>";
		}
		else{//Proceder a cargar los datos de los materiales incluido en el No. de vale introducido
			//Conectamos con la BD
			$conn = conecta("bd_almacen");
			
			//OBTENER LOS DATOS DE LOS VALES Y GUARDARLOS EN LA SESSION
			if(isset($_SESSION["bitacoraPrev"]))
				$idEquipo = $_SESSION['bitacoraPrev']['txt_claveEquipo'];
			else
				$idEquipo = $_SESSION['bitacoraCorr']['cmb_claveEquipo'];
				
				
			//Crear la Sentencia SQL para obtener los datos del vale y el costo total de los materiales asociados al equipo indicado en dicho vale
			$stm_sql = "SELECT DISTINCT no_vale, fecha_salida, solicitante, turno,
					 	(SELECT SUM(detalle_salidas.costo_total) FROM detalle_salidas JOIN salidas ON salidas_id_salida=id_salida 
					 	WHERE id_equipo_destino = '$idEquipo' AND no_vale='$claveVale') AS costo_total 
					 	FROM (salidas JOIN detalle_salidas ON id_salida=salidas_id_salida) 
					 	WHERE no_vale='$claveVale' and id_equipo_destino='$idEquipo'";
									 
			//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
			$rs = mysql_query($stm_sql);
			if($datos = mysql_fetch_array($rs)){
				//Verificar si existe el arreglo en la SESSION
				if(isset($_SESSION['valesMtto'])){
					$cond = 0;
					
					//Verificar que el Id del vale no sea agregado más de una vez para el registro actual de la Bitacora
					foreach($_SESSION["valesMtto"] as $key=> $arrVale){
						if(in_array($claveVale,$arrVale))
							$cond = 1;
					}
					
					//Agregar el Vale al registro de la Bitacora Actual
					if($cond==0){
						$_SESSION['valesMtto'][] = array("noVale"=>$datos["no_vale"], "fecha"=>$datos["fecha_salida"], "turno"=>$datos["turno"], 
														 "solicitante"=>$datos["solicitante"], "total"=>$datos["costo_total"]);
					}
					else{?>
						<script>
							function mostrarMensaje(){
								alert("El Vale <?php echo $claveVale; ?> ya ha Sido Agregado al Registro");
							}
							setTimeout("mostrarMensaje();",200);
						</script><?php				
					}
				}
				else{//Crear el arreglo y guardar el primer registro
					$_SESSION['valesMtto'] = array(array("noVale"=>$datos["no_vale"], "fecha"=>$datos["fecha_salida"], "turno"=>$datos["turno"], 
														 "solicitante"=>$datos["solicitante"], "total"=>$datos["costo_total"]));
				}
			}
			else{?>
				<script>
					function mostrarMensaje(){
						alert("No existen Materiales Asociados para el Equipo: <?php echo $idEquipo?> \nEn el Vale: <?php echo $claveVale; ?>");
					}
					setTimeout("mostrarMensaje();",200);
				</script><?php	
			}	
			//Cerar conexion a BD
			mysql_close($conn);	
		}
	}//Cierrre de la función cargarMateriales()
	
	
	/*Esta funcion se encarga de mostrar los materiales registrados en el vale indicado*/
	function mostrarVales($clave){		
		//MOSTRAR LA INFORMACION DE LOS VALES
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		if($_SESSION['valesMtto']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Materiales usados en el Mantenimiento Vale </caption>";
			echo "<tr>
						<td class='nombres_columnas'>VER DETALLE</td>
						<td class='nombres_columnas' align='center'>CLAVE VALE</td>
						<td class='nombres_columnas' align='center'>FECHA SALIDA</td>
						<td class='nombres_columnas' align='center'>SOLICITANTE</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>COSTO TOTAL</td>
						<td class='nombres_columnas' align='center'>BORRAR</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;		
			foreach($_SESSION['valesMtto'] as $key => $arrVale){?>
				<tr>
					<td class='nombres_filas'>
						<input type='checkbox' name='RC$cont' value='$arrVale[noVale]' 
						onClick="location.href='frm_mostrarDetalleVale.php?vale=<?php echo $arrVale['noVale'];?>'"/>
					</td><?php
				echo "
					<td class='$nom_clase' align='center'>$arrVale[noVale]</td>					
					<td class='$nom_clase' align='center'>".modFecha($arrVale['fecha'],1)."</td>	
					<td class='$nom_clase' align='center'>$arrVale[solicitante]</td>
					<td class='$nom_clase' align='center'>$arrVale[turno]</td>
					<td class='$nom_clase' align='center'>$".number_format($arrVale['total'],2,".",",")."</td>";?>
					<td class="<?php echo $nom_clase;?>" align="center">
						<input type="image" src="../../images/borrar.png" width="30" height="25" border="0" title="Borrar Registro" 
						onclick="location.href='frm_regMatMtto.php?noRegistro=<?php echo $cont-1;?>'"/>
					</td>
				</tr><?php
													
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}
			echo " </table>";
		}
	}//Cierre de la función mostrarVales($clave)
	
	
	
	//Esta función se encarga de mostrar el detalle del registro seleccionado
	function mostrarDetalle($clave){
		//Realizar la conexion a la BD de mantenimiento
		$conn = conecta("bd_almacen");
		if(isset($_SESSION["bitacoraPrev"]))
			$equipo = $_SESSION['bitacoraPrev']['txt_claveEquipo'];
		else
			$equipo = $_SESSION['bitacoraCorr']['cmb_claveEquipo'];
		$stm_sql = "SELECT DISTINCT salidas_id_salida, materiales_id_material, cant_salida, costo_unidad, detalle_salidas.costo_total, id_equipo_destino 
					FROM (detalle_salidas JOIN salidas ON id_salida=salidas_id_salida) WHERE no_vale='$clave' AND id_equipo_destino='$equipo'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>DETALLE DE LOS MATERIALES UTILIZADOS EN EL MANTENIMIENTO <em>$clave</em></caption>					
				<tr>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>CLAVE </td>
					<td class='nombres_columnas'>CLAVE MATERIAL</td>
					<td class='nombres_columnas'>NOMBRE MATERIAL</td>
					<td class='nombres_columnas'>CANTIDAD</td>
					<td class='nombres_columnas'>COSTO UNIDAD</td>
					<td class='nombres_columnas'>COSTO TOTAL</td>
					<td class='nombres_columnas'>CLAVE EQUIPO</td>				
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Obtener el Nombre del Material
				$nomMaterial = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $datos['materiales_id_material']);
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>$datos[salidas_id_salida]</td>
						<td class='$nom_clase'>$datos[materiales_id_material]</td>
						<td class='$nom_clase' align='left'>$nomMaterial</td>
						<td class='$nom_clase'>$datos[cant_salida]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos['costo_unidad'],2,".",",")."</td>
 					    <td class='$nom_clase' align='center'>$".number_format($datos['costo_total'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[id_equipo_destino]</td>							
						</tr>";
													
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));			
			echo "</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No se encontraron Materiales Asociados al Vale <em><u>$clave</u></em></label>";
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalle()	



	/***********************************************************************************************************************************************/
	/*****************************************GESTION DE FOTOGRAFIAS ANEXADAS A LA BITACORA*********************************************************/
	/***********************************************************************************************************************************************/					
	
	//Esta funcion permite registrar los archivos de la bitacora en que se este trabajando
	function registrarFotosEquipo($id_bitacora,$fotos){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		$band = 0;
		//Registrar todos los materiales dados de alta en el arreglo $fotos
		foreach($_SESSION["fotos"] as $ind => $doc){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "INSERT INTO registro_fotografico(bitacora_mtto_id_bitacora,estado,nombre_archivo)
						VALUES('$id_bitacora','$doc[estatus]','$doc[archivo]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de registro_fotografico
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro  en el caso de que existan errores	
			if($band==1)
				break;	
		}
		
		if ($band==1){
			//Cerrar la conexion con la BD		
			mysql_close($conn);
		
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";						
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_mantenimiento","$id_bitacora","AgregarFotosEquipo",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=frm_agregarFotoEquipo.php'>";
		}		
	}//Cierre de la función registrarFotosEquipo($id_bitacora,$fotos)
	
	
	//Esta funcion permite subir Archivos que forman parte del registro de la bitacora
	function subirFotos($clave){		
		$band = true;	
		//Verificamos el tipo de archivo
		if((substr($_FILES['file_documento']['type'],0,5) != 'image')&&(substr($_FILES['file_documento']['type'],12,3) != 'pdf')&&(substr($_FILES['file_documento']['type'],12,6) != 'msword')){?>
			<script>
				setTimeout("alert('El Formato del Archivo no es Válido. Sólo se Permiten PDF, DOC e Imágenes');",500);
			</script><?php
			exit('');			 
			$band = false;			
		}
		//De lo contrario el archivo es valido y se procedera a subir el archivo en el lugar correspondiente
		else{
			//Creamos la variable ruta que servira para abrir la misma	
			$Ruta = '';			
			$carpeta2 = "";
			$carpeta3 = "";
			//Creamos la carpeta inicial dentro de documentos
			$carpeta = 'documentos/'.$clave;
			//Verificamos que opcion viene definida en el post
			if($_POST['cmb_estatus']=="ANTES"){
				$var = "A";
				$carpeta2 = $carpeta."/ANTES";
			}
			else{
				$var = "B";
				$carpeta3 = $carpeta."/DESPUES";
			}
			
			//Abrimos la ruta para crear la carpeta
   			$dir = opendir($Ruta); 
			//Verificamos que el archivo haya sido updated
		   	if (is_uploaded_file($_FILES['file_documento']['tmp_name'])) { 
				//Si $carpeta no ah sido creado se crea con mkdir
				if (!file_exists($carpeta."/")){
					mkdir($carpeta."/", 0777);
				}
				//Verificamos si $carpeta2 y $carpeta3 estan definidos; de no ser asi crearlos
				if (!file_exists($carpeta2."/")||!file_exists($carpeta3."/")){
					if($var=="A")
						mkdir($carpeta2."/", 0777);
					else
						mkdir($carpeta3."/", 0777);
				}
				if($var=="A"){
					//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
					if (!file_exists($carpeta2."/".$_FILES['file_documento']['name'])){
		 	       		move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta2."/".$_FILES['file_documento']['name']); 
						//llamar la funcion que se encarga de reducir el peso de la fotografia 
						redimensionarFoto($carpeta2."/".$_FILES['file_documento']['name'],$_FILES['file_documento']['name'],$carpeta2."/",100,100);?>
						<script>
							setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
						</script><?php
					}
					else{
						$band=false;?>
						<script>
							setTimeout("alert('El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe');",500);
						</script><?php
					}
				}
				else{
				//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
					if (!file_exists($carpeta3."/".$_FILES['file_documento']['name'])){
		 	       		move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta3."/".$_FILES['file_documento']['name']);
						//llamar la funcion que se encarga de reducir el peso de la fotografia 
						redimensionarFoto($carpeta3."/".$_FILES['file_documento']['name'],$_FILES['file_documento']['name'],$carpeta3."/",100,100);?>
						<script>
							setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
						</script><?php
					}
					else{
						$band = false;?>
						<script>
							setTimeout("alert('El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe');",500);
						</script><?php
					}
				}
			}
		}
		
		return $band;
		
	}//Fin de la funcion subirFotos($clave)
	
	
	//Desplegar las fotos registradas en el apartado Registro Fotografico de la bitacora
	function mostrarFotossReg($fotos){
		if(isset($_SESSION["bitacoraPrev"]))
			$clave = $_SESSION["bitacoraPrev"]["txt_claveBitacora"];
		else 
			$clave = $_SESSION["bitacoraCorr"]["txt_claveBitacora"];
			
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Registro Fotogr&aacute;fico de la Bit&aacute;cora ". $clave."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NOMBRE ARCHIVO</td>
        		<td class='nombres_columnas' align='center'>REGISTRO FOTOGR&Aacute;FICO</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach($fotos as $ind => $material) {
			echo "<tr>";
			foreach($material as $key => $value) {
				switch($key){
					case "archivo":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "estatus":
						echo "<td align='center' class='$nom_clase'>$value "."DEL SERVICIO</td>";
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
	}//Fin de la funcion mostrarFotossReg($fotos)	
	
	
	//Funcion que borra el arreglo de sesion y las fotos en caso de cancelar
	function borrarFotos($carpeta){
		foreach ($_SESSION["fotos"] as $ind => $doc){
			//Variable que obtiene el nombre del Archivo
			$nombreArchivo=$doc["archivo"];
			$subCarpeta=$doc["estatus"];
			//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
			@unlink("documentos/".$carpeta."/".$subCarpeta."/".$nombreArchivo);
		}
	}//Fin de la funcion borrarFotos()
	
	
	//Funcion que borra los archivos que  han sido cargados durante la sesion si esta fue cerrada
	function borrarFotosSesion(){
		//Tomamos los valores de $carpeta que es el id de la bitacora esta funcion es para Mtto Correctivo y Prev por eso se verifica
		if(isset($_SESSION["bitacoraPrev"])){
			$carpeta=$_SESSION["bitacoraPrev"]["txt_claveBitacora"];
		}
		else{
			$carpeta=$_SESSION["bitacoraCorr"]["txt_claveBitacora"];
		}
		//Recorremos el arreglo fotos en busca del nombre y estatus para eliminar la ruta correcta
		foreach ($_SESSION["fotos"] as $ind => $doc){
			//Variable que obtiene el nombre del Archivo
			$nombreArchivo=$doc["archivo"];
			$subCarpeta=$doc["estatus"];
			//Pasamos la ruta completa, ya que esta funcion borra archivos desde la pagina salir.php
			@unlink("man/documentos/".$carpeta."/".$subCarpeta."/".$nombreArchivo);
		}
	}//Fin de la funcion borrarFotosSesion()
	
		
?>