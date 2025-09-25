<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 19/Enero/2012                                      			
	  * Descripción: Este archivo permite modificar el estado de los Recordatorios
	  **/
	 	
	
	//Función que permite mostrar los recordatorios Registrados
	function mostrarRecordatorios(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT *	FROM alertas_generales ORDER BY id_alerta";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultados'> 
				<thead>";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>ID ALERTA</th>
						<th class='nombres_columnas' align='center'>FECHA GENERACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>FECHA PROGRAMADA</th>
						<th class='nombres_columnas' align='center'>TIPO ALERTA</th>
						<th class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</th>
						<td class='nombres_columnas' align='center'>DEPARTAMENTOS</td>
						<td class='nombres_columnas' align='center'>ARCHIVOS VINCULADOS</td>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='$nom_clase' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_alerta]'/>
					</td>
					<td class='$nom_clase'>$datos[id_alerta]</td>"; 
				echo "<td class='$nom_clase'>".modFecha($datos['fecha_generacion'],1)."</td>
     		          <td class='$nom_clase'>".modFecha($datos['fecha_programada'],1)."</td>
					  <td class='$nom_clase'>$datos[tipo_alerta]</td>
					  <td class='$nom_clase'>$datos[descripcion]</td>";
					  if($datos['tipo_alerta']=="EXTERNA"){?>
					  <td class="<?php echo $nom_clase; ?>" align="center">
					  	<input type="button" name="btn_Archivo" class="botones" value="Departamentos" onMouseOver="window.estatus='';return true" 
						title="Ver Departamentos<?php echo $datos['descripcion'];?>" 
						onClick="javascript:window.open('verDepartamentos.php?idAlerta=<?php echo $datos['id_alerta'];?>',
								'_blank','top=300, left=450, width=400, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
					 </td>
					<td class="<?php echo $nom_clase; ?>" align="center">
						<input type="button" name="btn_Archivo" class="botones" value="Archivos" onMouseOver="window.estatus='';return true" 
						title="Descargar Documento<?php echo $datos['descripcion'];?>" 
						onClick="javascript:window.open('verArchivos.php?idAlerta=<?php echo $datos['id_alerta'];?>',
								'_blank','top=300, left=450, width=450, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
					</td><?php
					}
					else{?>
					<td class="<?php echo $nom_clase; ?>" align="center">
					  	<input type="button" name="btn_Archivo" class="botones" value="Departamentos" onMouseOver="window.estatus='';return true" 
						title="Alerta Interna; No Hay Departamentos Registrados" disabled="disabled"/>							
					 </td>
					<td class="<?php echo $nom_clase; ?>" align="center">
						<input type="button" name="btn_Archivo" class="botones" value="Archivos" onMouseOver="window.estatus='';return true" 
						title="Alerta Interna; No Hay Archivos Registrados" disabled="disabled"/><?php 		
					}
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Recordatorios Registrados </label>";
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
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD	
		$idRecordatorio = $_POST["txt_idRecordatorio"];	
		$dptos=$_POST["txt_ubicacion"];
		$fechaRegistro=date("Y-m-d");
		$fechaProgramada=modFecha($_POST["txt_fechaProg"],3);
		$archivos=$_POST["txt_archivos"];
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$tipoAler=strtoupper($_POST["cmb_tipoAler"]);
				
		//Crear la sentencia para realizar el registro de los datos
		$stm_sql = "UPDATE alertas_generales SET estado='1', fecha_generacion='$fechaRegistro', tipo_alerta='$tipoAler',
		           descripcion='$descripcion',fecha_programada='$fechaProgramada' WHERE id_alerta='$idRecordatorio'";
						
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
				registrarOperacion("bd_seguridad",$idRecordatorio,"ModRecordatorio",$_SESSION['usr_reg']);
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
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la bd
		$conn = conecta("bd_seguridad");
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		
		//Dividimos la cadena que contiene los departamentos
		$divDptos = explode(",",$dptos);
		
		//Dividimos la cadena que contiene los archivos
		$divArch = explode(",",$archivos);
		
		//Iniciamos la sesion
		session_start();
		
		//Eliminamos las alertas para proceder a insertar las nuevas
		$stm_sqlDel = "DELETE FROM detalle_alertas_generales WHERE alertas_generales_id_alerta='$idRecordatorio'";
		
		//Ejecutamos la sentencia previamante creada
		$rsDel = mysql_query($stm_sqlDel);	
		
		//Eliminamos las alertas para proceder a insertar las nuevas
		$stm_sqlDelArch = "DELETE FROM archivos_vinculados WHERE alertas_generales_id_alerta='$idRecordatorio'";
		
		//Ejecutamos la sentencia previamante creada
		$rsDelArch = mysql_query($stm_sqlDelArch);	
		
		//Ciclo que permite el registro dentro de la tabla de detalles
		foreach($divDptos as $key => $valor){
			//Obtenemos el id del departamento
			$idDpto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "depto", $valor);
			//Realizar la conexion a la BD de Seguridad
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
			//echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_seguridad",$idRecordatorio,"ModRecordatorio",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	
	if(isset($_POST['sbt_eliminar'])){
		eliminarRegistro();
	}
	
	function eliminarRegistro(){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Recuperamos el Id de la alerta
		$idAlerta = $_POST['rdb_id'];
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Creamos la conslulta SQL que permite eliminar el plano de la BD
		$stm_sql2 ="DELETE FROM alertas_generales WHERE id_alerta='$idAlerta'";
			
		//Ejecutamos la consulta
		$rs2=mysql_query($stm_sql2);
		
		if($rs2){
			//Creamos la conslulta SQL que permite eliminar el plano de la BD
			$stm_sql3 ="DELETE FROM detalle_alertas_generales WHERE alertas_generales_id_alerta='$idAlerta'";
			
			//Ejecutamos la consulta
			$rs3=mysql_query($stm_sql3);
			if($rs3){
				//Creamos la conslulta SQL que permite eliminar el plano de la BD
				$stm_sql4 ="DELETE FROM archivos_vinculados WHERE alertas_generales_id_alerta='$idAlerta'";
				
				//Ejecutamos la consulta
				$rs4=mysql_query($stm_sql4);
				
				if($rs4){
					//Registramos la operación en la bitacora de movimientos
					registrarOperacion("bd_seguridad",$idAlerta,"EliminoRecordatorio",$_SESSION['usr_reg']);
					//Redireccionamos a la pantalla de éxito
					echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				}
				else{
					//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
					$error = mysql_error();	
					echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
				}
			}
			else{
				//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
				$error = mysql_error();	
				echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
			}
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();	
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);
	}
?>