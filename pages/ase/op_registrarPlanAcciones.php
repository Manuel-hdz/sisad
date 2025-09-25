<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                           
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 16/Noviembre/2011                                      			
	  * Descripción: Este archivo permite guardar los registros dela Lista Maestra así como mostrar informacion para las diversas operaciones que consciernen
	  **/
	
	//Función que permite mostrar los Departamento para agregarlos al registro
	function mostrarParticipantes(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_recursos");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM (organigrama JOIN empleados ON 
				   rfc_empleado=empleados_rfc_empleado) WHERE departamento NOT LIKE 'DIRECCION GENERAL' ORDER BY departamento";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "<table cellpadding='5' width='100%'>";
				echo "<tr>
						<td colspan='2' class='nombres_columnas' align='center'>SELECCIONAR TODO </td>
						<td class='nombres_columnas' align='center'>
							<input type='checkbox' id='ckb_todo' name='ckb_todo' onclick='seleccionarTodoPart(this);'/>
						</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO </td>
						<td class='nombres_columnas' align='center'>NOMBRE </td>
					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='checkbox' id='ckb_dpto$cont' name='ckb_dpto$cont' value='$datos[nombre]' onclick='quitar(this);'/>
							</td>				
							<td class='$nom_clase' align='center'>$cont.-</td>					
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
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
			echo "<label class='msje_correcto'>  No existen Participantes Registrados </label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Esta funcion permite registrar los Archivos en la BD
	function guardarRegistro(){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
				
		//Dividimos la cadena que contiene los Participantes
		$divPart = explode(",",$_POST['txt_paticipantesAu']);
		
		//Dividimos la cadena que contiene los departamentos
		$divDeptos = explode(",",$_POST['txt_ubicacion']);
 
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$id_registro=obtenerIdReg();
		$areaAudi=$_POST["cmb_depto"];
		$creador=strtoupper($_POST["txt_creador"]);
		$aprobador=strtoupper($_POST["txt_aprobado"]);
		$verificador=strtoupper($_POST["txt_verificado"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		$revision=$_POST["txt_rev"];
		$noDoc=$_POST["txt_NoDoc"];
		$referencia=$_POST["txt_referencias"];
		$idAlerta = obtenerIdAlerta();
		$fechaAlerta = date("Y-m-d");
		
		//Ciclo que permite el registro de los participantes en las auditorias
		foreach($divPart as $key => $part){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlPart = "INSERT INTO catalogo_participantes_auditoria(plan_acciones_id_plan_acciones,nombre) VALUES('$id_registro', '$part')";
								
			//Ejecutar la sentencia previamente creada 
			$rsPart = mysql_query($stm_sqlPart);
			if(!$rsPart){
				$band = 1;	
				break;					
			}
		}
		
		//Ciclo que permite el de las copias a departamentos
		foreach($divDeptos as $key => $depto){
			//Obtenemos el id del departamento
			$idDpto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "depto", $depto);
			//Realizar la conexion a la BD de Aseguramiento Calidad
			$conn = conecta("bd_aseguramiento");
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlDepo = "INSERT INTO copias_entregadas(plan_acciones_id_plan_acciones,catalogo_departamentos_id_departamento) VALUES('$id_registro', '$idDpto')";
								
			//Ejecutar la sentencia previamente creada 
			$rsDepto = mysql_query($stm_sqlDepo);
			if(!$rsDepto){
				$band = 1;
				break;			
			}
		}

		//Ciclo que permite registrar las referencias vinculadas
		foreach($_SESSION['referencias'] as $key => $valor){
			$idRef=obtenerIdReferencia();
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlRef = "INSERT INTO referencias(id_referencia, plan_acciones_id_plan_acciones,no_referencia)
						   VALUES('$idRef','$id_registro','$valor[clave]')";
		 					
			//Ejecutar la sentencia previamente creada 
			$rsRef = mysql_query($stm_sqlRef);
			if(!$rsRef){
				$band = 1;
				break;			
			}
			//Crear la sentencia para realizar el registro de los datos en el detalle de referencias 
			 $stm_sqlRef2 = "INSERT INTO detalle_referencias(referencias_id_referencia,desv_obs_exp, plan_acciones_id_plan_acciones)
						   VALUES('$idRef','$valor[referencia]', '$id_registro')";
			$rsRef2 = mysql_query($stm_sqlRef2);
			if(!$rsRef2){
				$band = 1;
				break;			
			}
		}
				
		//Crear la sentencia para realizar el registro de los datos
		 $stm_sql = "INSERT INTO plan_acciones(id_plan_acciones,area_auditada, creador, aprobador, verificador, fecha, revision, no_documento, referencia)
		 			VALUES('$id_registro','$areaAudi', '$creador','$aprobador','$verificador', '$fecha','$revision', '$noDoc', '$referencia')";
						
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);
		if(!$rs){
			$band = 1;						
		}
		if ($band==1){
			eliminarRegFallido($id_registro);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			//Crear la sentencia para realizar el registro de los datos
			 $stm_sqlAler = "INSERT INTO alertas_plan_acciones(id_alertas_plan_acciones,plan_acciones_id_plan_acciones, estado, fecha_generacion)
		 				    VALUES('$idAlerta','$id_registro', '1','$fechaAlerta')";
						
			//Ejecutar la sentencia previamente creada 
			$rsAler = mysql_query($stm_sqlAler);
			
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$id_registro,"RegPlanAcciones",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	
	///Esta funcion genera la Clave del Recordatorio Dependiendo de los registros en la BD
	function obtenerIdReg(){
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "PLN";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_plan_acciones) AS cant FROM plan_acciones WHERE id_plan_acciones LIKE 'PLN$mes$anio%'";
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
	
	//Funcion que permite obtener el id del Participante
	function obtenerIdAlerta(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_alertas_plan_acciones)+1 AS cant FROM alertas_plan_acciones";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos[0];
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
	
	//Funcion que permite obtener el id del Participante
	function obtenerIdReferencia(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_referencia)+1 AS cant FROM referencias";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant==NULL)
				$id_cadena=1;
			else
				$id_cadena = $datos[0];
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
	
	function eliminarRegFallido($id_registro){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Crear la sentencia para realizar el registro de los datos
		$stm_sqlPart = "DELETE FROM catalogo_participantes_auditoria WHERE plan_acciones_id_plan_acciones='$id_registro')";
							
		//Ejecutar la sentencia previamente creada 
		$rsPart = mysql_query($stm_sqlPart);
		
		//Crear la sentencia para realizar el registro de los datos
		$stm_sqlDepo = "DELETE FROM copias_entregadas WHERE plan_acciones_id_plan_acciones='$id_registro'";
								
		//Ejecutar la sentencia previamente creada 
		$rsDepto = mysql_query($stm_sqlDepo);
			
		//Crear la sentencia para realizar el registro de los datos
		$stm_sqlRef = "DELETE FROM referencias WHERE  plan_acciones_id_plan_acciones='$id_registro'";
								
		//Ejecutar la sentencia previamente creada 
		$rsRef = mysql_query($stm_sqlRef);
		
		//Crear la sentencia para realizar el registro de los datos
		$stm_sqlRef2 = "DELETE FROM detalle_referencias WHERE  plan_acciones_id_plan_acciones='$id_registro'";
								
		//Ejecutar la sentencia previamente creada 
		$rsRef2 = mysql_query($stm_sqlRef2);
			
		//Crear la sentencia para realizar el registro de los datos
		$stm_sql = "DELETE FROM plan_acciones WHERE id_plan_acciones='$id_registro'";
						
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);

		//Crear la sentencia para realizar el registro de los datos
		$stm_sqlAler = "DELETE FROM  alertas_plan_acciones WHEWRE plan_acciones_id_plan_acciones='$id_registro'";
						
		//Ejecutar la sentencia previamente creada 
		$rsAler = mysql_query($stm_sqlAler);
		
		//Cerramos la conexión
		mysql_close();
	}
	
	//Funcion encargada de mostrar la lista_maestra en una ventana pop up en caso de existir
	function mostrarReferencias($referencias){
		//Verificamos que exista la session
		if($_SESSION['referencias']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle del Registro</caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO. REFERENCIA</td>
					<td class='nombres_columnas' align='center'>DESVIACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['referencias'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='$nom_clase'>$arrVale[clave]</td>
						<td align='center'  class='$nom_clase'>$arrVale[referencia]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	>
							<input type="image" src="../../images/borrar.png" width="30" height="25"
							border="0" title="Borrar Registro" 
							onclick="location.href='verRegReferencias.php?noRegistro=<?php echo $key;?>'"/>
						</td><?php 
			 	echo"</tr>";					
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			};
			echo " </table>";
		}
	}
	?>