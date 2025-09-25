<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                           
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 19/Enero/2012                                      			
	  * Descripción: Este archivo permite guardar los recordatorios; así como alertas
	  **/

	
	///Esta funcion genera la Clave del Recordatorio Dependiendo de los registros en la BD
	function obtenerIdReg(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las tres letras la clave 
		$id_cadena = "ALS";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_alerta) AS cant FROM alertas_generales WHERE id_alerta LIKE 'ALS$mes$anio%'";
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
	
	
	//Función que permite mostrar los Departamento para agregarlos al registro
	function mostrarArchivos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM repositorio_documentos ORDER BY nombre";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>";
			echo "	
					<tr>
						<td colspan='2' class='nombres_columnas' align='center'>SELECCIONAR TODO </td>
						<td class='nombres_columnas' align='center'>
							<input type='checkbox' id='ckb_todo' name='ckb_todo' onclick='seleccionarTodoArch(this);'/>
						</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>CLAVE</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='checkbox' id='ckb_arch$cont' name='ckb_arch$cont' value='$datos[id_documento]' onclick='quitar(this);'/>
							</td>				
							<td class='$nom_clase' align='center'>$cont.-</td>	
							<td class='$nom_clase' align='left'>$datos[id_documento]</td>				
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			?>
				<input type="hidden" name="hdn_cant" id="hdn_cant" value="<?php echo $cont;?>"/>
			<?php 
			return 1;
		}
		else{
			?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('No Existen Archivos Registrados; Agregue Archivos en el Menú Repositorio');window.close()",500);
				
			</script>
			<?php 	
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Verificamos si viene definido el boton; de ser asi almacenar la información
	if(isset($_POST["sbt_guardar"])){
		//Llamamos la funcion guardarRegistro
		guardarRegistro();

	}	
	//Esta funcion permite registrar los Archivos en la BD
	function guardarRegistro(){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Seguridad Industrial
		$conn = conecta("bd_seguridad");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		
		//Iniciamos la sesion
		session_start();
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD	
		$idRecordatorio = $_POST["txt_idRecordatorio"];	
		$dptos=$_POST["txt_ubicacion"];
		$fechaRegistro=date("Y-m-d");
		$fechaProgramada=modFecha($_POST["txt_fechaProg"],3);
		$archivos=$_POST["txt_archivos"];
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$tipoAler=strtoupper($_POST["cmb_tipoAler"]);
				
		//Crear la sentencia para realizar el registro de los datos
		$stm_sql = "INSERT INTO alertas_generales(id_alerta, estado, fecha_generacion, tipo_alerta, descripcion,fecha_programada)
					VALUES('$idRecordatorio', '1','$fechaRegistro','$tipoAler',	'$descripcion','$fechaProgramada')";
						
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);
		if(!$rs){
			$band = 1;						
		}
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			if($dptos!=""){
				guardarRegistroDetalle($idRecordatorio, $dptos, $archivos);			
			}
			else{
				//Registrar la Operacion en la Bitácora de Movimientos
				registrarOperacion("bd_seguridad",$idRecordatorio,"RegRecordatorio",$_SESSION['usr_reg']);
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	//Esta funcion permite registrar los Archivos en la BD
	function guardarRegistroDetalle($idRecordatorio, $dptos, $archivos){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad Indistrial
		$conn = conecta("bd_seguridad");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		
		//Dividimos la cadena que contiene los departamentos
		$divDptos = explode(",",$dptos);
		//Dividimos la cadena que contiene los archivos
		$divArch = explode(",",$archivos);
		
		//Ciclo que permite el registro dentro de la tabla de detalles
		foreach($divDptos as $key => $valor){
			//Obtenemos el id del departamento
			$idDpto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "depto", $valor);
			//Realizar la conexion a la BD de Seguridad Indistrial
			$conn = conecta("bd_seguridad");
		
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "INSERT INTO detalle_alertas_generales(alertas_generales_id_alerta, catalogo_departamentos_id_departamento)
						VALUES('$idRecordatorio', '$idDpto')";
							
			//Ejecutar la sentencia previamente creada 
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;						
			}
			if($archivos!=""){
				//Ciclo que permite el registro de los archivos viunculados a la alerta
				foreach($divArch as $key => $arch){
					//Crear la sentencia para realizar el registro de los datos
					$stm_sqlArch = "INSERT INTO archivos_vinculados(catalogo_departamentos_id_departamento, 
									alertas_generales_id_alerta,repositorio_documentos_id_documento)
									VALUES('$idDpto', '$idRecordatorio','$arch')";
								
					//Ejecutar la sentencia previamente creada 
					$rsArch = mysql_query($stm_sqlArch);
					if(!$rsArch){
						$band = 1;						
					}
				}
			}
		}						
		
		if ($band==1){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_seguridad",$idRecordatorio,"RegRecordatorio",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	
	//Función que permite mostrar los Departamento para agregarlos al registro
	function mostrarDepartamentos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Usuarios
		$conn = conecta("bd_usuarios");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT DISTINCT UPPER (depto) as depto FROM usuarios WHERE depto NOT LIKE 'Panel' and depto NOT LIKE 'DireccionGral' ORDER BY depto";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>";
			echo "	
					<tr>
						<td colspan='2' class='nombres_columnas' align='center'>SELECCIONAR TODO </td>
						<td class='nombres_columnas' align='center'>
							<input type='checkbox' id='ckb_todo' name='ckb_todo' onclick='seleccionarTodo(this);'/>
						</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO </td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTO </td>
					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='checkbox' id='ckb_dpto$cont' name='ckb_dpto$cont' value='$datos[depto]' onclick='quitar(this);'/>
							</td>				
							<td class='$nom_clase' align='center'>$cont.-</td>					
							<td class='$nom_clase' align='left'>$datos[depto]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			?>
				<input type="hidden" name="hdn_cant" id="hdn_cant" value="<?php echo $cont;?>"/>
			<?php 
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Departamentos Registrados </label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
?>