<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 09/Noviembre/2011                                      			
	  * Descripción: Este archivo permite modificar el plan de acciones
	  **/
	 	
	
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarResultados(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Verificamos que exista el hdn_org; eso indica que se eesta enviando a la pantalla correspondiente desde una alaerta
		if(!isset($_POST['hdn_org'])){
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM plan_acciones WHERE area_auditada='$_POST[cmb_depto]'";
		}
		else{
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM plan_acciones";
		}
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultados'> 
				<thead>
				";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>&Aacute;REA AUDITADA</th>
						<th class='nombres_columnas' align='center'>CREADO POR</th>
						<th class='nombres_columnas' align='center'>APROBADO POR</th>
						<th class='nombres_columnas' align='center'>VERIFICADO POR</th>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>REVISI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>NO. DOCUMENTO</td>
						<td class='nombres_columnas' align='center'>PARTICIPANTES</td>
						<td class='nombres_columnas' align='center'>COPIAS ENTREGADAS</td>
						<td class='nombres_columnas' align='center'>EVIDENCIAS</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$bandera="";
			echo "<tbody>";
			do{	
				$stm_sqlRef ="SELECT * FROM detalle_referencias WHERE plan_acciones_id_plan_acciones='$datos[id_plan_acciones]'";				
				//Ejecutamos la sentencia sql
				$rsRef=mysql_query($stm_sqlRef);
				//Guardamos el resultado de la sentencia en el arreglo $datosRef
				if($datosRef=mysql_fetch_array($rsRef)){
					//Verificamos que los datos que son complementados existan; de ser asi activamos la bandera en 1
					if($datosRef['justificacion']!=NULL&&$datosRef['accion_planeada']!=NULL&&$datosRef['fecha_planeada']!=NULL&&$datosRef['fecha_real_terminacion']!=NULL&&$datosRef['validacion_ase']!=""&&$datosRef['nom_archivo']!=NULL){
						$bandera=1;
					}
					if($datosRef['nom_archivo']==NULL){
						$bandera = 2;
					}
				}//De lo contrario la bandera se activa con valor 0
								
				echo "	<tr>
							<td class='$nom_clase' align='center'>";?>
								<input type='radio' id='rdb_id' name='rdb_id' value='<?php echo $datos['id_plan_acciones'];?>' 
								onclick="verificaPlanAccionesRadio('<?php echo $datos['id_plan_acciones'];?>');"/>
							</td><?php 
					 echo "	<td class='$nom_clase' align='center'>$datos[area_auditada]</td>					
							<td class='$nom_clase' align='left'>$datos[creador]</td>
							<td class='$nom_clase' align='center'>$datos[aprobador]</td>
							<td class='$nom_clase' align='center'>$datos[verificador]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' align='center'>$datos[revision]</td>					
							<td class='$nom_clase' align='left'>$datos[no_documento]</td>";
							?>
							<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Archivo" class="botones" value="Participantes" onMouseOver="window.estatus='';return true" 
								title="Ver Participantes del Plan de Acciones" 
								onClick="javascript:window.open('verModParticipantesAuditoria.php?idPlanAcciones=<?php echo $datos['id_plan_acciones'];?>',
								'_blank','top=300, left=450, width=400, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Deptos" class="botones" value="Copias" onMouseOver="window.estatus='';return true" 
								title="Ver Departamentos Con Copia del Plan de Acciones" 
								onClick="javascript:window.open('verCopiasEntregadas.php?idPlanAcciones=<?php echo $datos['id_plan_acciones'];?>',
								'_blank','top=300, left=450, width=400, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<?php if($bandera==1){?>
							<td class="<?php echo $nom_clase; ?>" align="center">
									<input type="button" name="btn_evidencias" class="botones" value="Evidencias" onMouseOver="window.estatus='';return true" 
									title="Ver Evidencias del Plan de Acciones" 
									onClick="javascript:window.open('verEvidenciasPlanAcciones.php?idPlanAcciones=<?php echo $datos['id_plan_acciones'];?>',
									'_blank','top=300, left=450, width=700, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
							</td>
						<?php 
						}
						elseif($bandera==2){?>
							<td class="<?php echo $nom_clase; ?>" align="center">
									<input type="button" name="btn_Deptos" class="botones" value="Evidencias" onMouseOver="window.estatus='';return true" 
									title="El Registro Seleccionado No Cuenta Con Evidencias Cargadas" disabled="disabled"/>							
							</td>
						<?php }?>
					<?php
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
			echo "<label class='msje_correcto'>  No existen Plan de Acciones Registrados para el Departamento Seleccionado </label>";
			return 0;
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
		
		//Variable que permite conocer si se actualizara una tabla o no
		$flag = 0;
				
		//Dividimos la cadena que contiene los Participantes
		$divPart = explode(",",$_POST['txt_paticipantesAu']);
		
		//Dividimos la cadena que contiene los departamentos
		$divDeptos = explode(",",$_POST['txt_ubicacion']);
 
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$id_registro=$_POST['rdb_id'];
		$areaAudi=$_POST["cmb_depto"];
		$creador=strtoupper($_POST["txt_creador"]);
		$aprobador=strtoupper($_POST["txt_aprobado"]);
		$verificador=strtoupper($_POST["txt_verificado"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		$revision=$_POST["txt_rev"];
		$noDoc=$_POST["txt_NoDoc"];
		$fechaAlerta = date("Y-m-d");
		$refer = strtoupper($_POST['txt_referencias']);
		
		//********Instrucciones de Borrado de todas las tablas para realizar las inserciones************//
		//Crear la sentencia para realizar la eliminacion de los datos de la tabla de caralogo_participantes_auditoria
		$stm_DelCPart = "DELETE FROM catalogo_participantes_auditoria WHERE plan_acciones_id_plan_acciones='$id_registro'"; 
		//Ejecutamos la sentencia previamente creada
		$rsDelCPart = mysql_query($stm_DelCPart);
		
		//Crear la sentencia para realizar la eliminacion de los datos de la tabla de copias_entregadas
		$stm_DelCE = "DELETE FROM copias_entregadas WHERE plan_acciones_id_plan_acciones='$id_registro'"; 
		//Ejecutamos la sentencia previamente creada
		$rsDelCE = mysql_query($stm_DelCE);
		
		//Crear la sentencia para realizar la eliminacion de los datos de la tabla de referencias
		$stm_DeRef = "DELETE FROM referencias WHERE plan_acciones_id_plan_acciones='$id_registro'"; 
		//Ejecutamos la sentencia previamente creada
		$rsDelRef = mysql_query($stm_DeRef);
		
		//Crear la sentencia para realizar la eliminacion de los datos de la tabla de referencias
		$stm_DeRef2 = "DELETE FROM detalle_referencias WHERE plan_acciones_id_plan_acciones='$id_registro'"; 
		//Ejecutamos la sentencia previamente creada
		$rsDelRef2 = mysql_query($stm_DeRef2);
		
		//********Instrucciones de Inserción de todas las tablas relacionadas con el plan de Acciones************//		
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
			//Obtenemos el id de la referencia
			$idRef=obtenerIdReferencia();
			
			//Cambiamos el valor de la bandera
			$flag = 1;
			
			///Crear la sentencia para realizar el registro de los datos
			$stm_sqlRef = "INSERT INTO referencias(id_referencia, plan_acciones_id_plan_acciones, no_referencia)
						   VALUES('$idRef','$id_registro','$valor[clave]')";
			
			//Ejecutar la sentencia previamente creada 
			$rsRef = mysql_query($stm_sqlRef);
			if(!$rsRef){
				$band = 1;
				break;			
			}
			$stm_sqlRef2 = "INSERT INTO detalle_referencias(referencias_id_referencia,desv_obs_exp, plan_acciones_id_plan_acciones)
						   VALUES('$idRef','$valor[referencia]', '$id_registro')";			   
								
			
			$rsRef2 = mysql_query($stm_sqlRef2);
			if(!$rsRef2){
				$band = 1;
				break;			
			}
		}
				
		//Crear la sentencia para realizar el registro de los datos
		$stm_sql = "UPDATE plan_acciones SET area_auditada='$areaAudi', creador='$creador', aprobador='$aprobador', verificador='$verificador', 
					fecha='$fecha', revision='$revision', no_documento='$noDoc', referencia='$refer' WHERE id_plan_acciones='$id_registro'";
						
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);
		if(!$rs){
			$band = 1;						
		}	
		
		if($flag!=0){
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql_alertas = "UPDATE alertas_plan_acciones SET estado='1' WHERE plan_acciones_id_plan_acciones='$id_registro' AND estado = '0'";
						
			//Ejecutar la sentencia previamente creada 
			$rs_alertas = mysql_query($stm_sql_alertas);
			if(!$rs_alertas){
				$band = 1;						
			}
		}
		
		
		if ($band==1){
			eliminarRegFallido($id_registro);
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$id_registro,"ModPlanAcciones",$_SESSION['usr_reg']);
			if(isset($_SESSION['referencias'])){
				unset($_SESSION['referencias']);
			}
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	//Si El boton de eliminar fue presionado eliminar el registro
	if(isset($_POST['sbt_eliminar'])){
		eliminarRegFallido($_POST['rdb_id']);
	}
	
	//Funcion para eliminar los REgistros en caso de haber ocurrido alguna falla
	function eliminarRegFallido($id_registro){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Crear la sentencia para realizar el registro de los datos
		$stm_sqlPart = "DELETE FROM catalogo_participantes_auditoria WHERE plan_acciones_id_plan_acciones='$id_registro'";
								
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
		
		if(isset($_POST['rdb_id'])){
			//Iniciamos la session
			session_start();
			//Crear la sentencia para realizar el registro de los datos
			$stm_sqlDelAler = "DELETE FROM alertas_plan_acciones WHERE plan_acciones_id_plan_acciones='$id_registro'";
						
			//Ejecutar la sentencia previamente creada 
			$rsDelAler = mysql_query($stm_sqlDelAler);
			
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$id_registro,"EliminoPlanAcciones",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		
		//Cerramos la conexión
	//mysql_close($conn);

	}
	
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
	
	
	//Función que permite eliminar la evidencia del directorio
	function eliminarEvidencia($id){
		//Incluimos archivo de conexión
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Creamos la consulta
		$stm_sql ="SELECT nom_archivo FROM detalle_referencias WHERE referencias_id_referencia='$id'";
		
		//Verificar si la sentencia ejecutada se genero con exito
		$rs=mysql_query($stm_sql);
		
		//Creamos la consulta
		$stm_sqlDel ="DELETE FROM detalle_referencias WHERE  referencias_id_referencia='$id'";
				
		
		$rsDel=mysql_query($stm_sqlDel);
		
		//Creamos la consulta
		$stm_sqlDelRef ="DELETE FROM referencias WHERE id_referencia='$id'";
				
		
		$rsDelRef=mysql_query($stm_sqlDelRef);
		
		$ruta="documentos/EVIDENCIAS";
		//verificamos que la sentencia sea ejecutada con exito
		if ($rs){
			//Guardamos los datos necesarios para poder tomarlos de la consulta e indicar que archivo sera eliminado
			if($datos=mysql_fetch_array($rs)){						
				$nombreArchivo=$datos["nom_archivo"];
			}
			
				//Creamos arreglos para verificar si las carpetas tienen datos; ya que si tienen datos no pueden ser eliminadas
			$archivos=array();
			
			//Abrimos el archivo y reccorremos en busqueda de sub-carpetas o archivos
			if($gestor = opendir($ruta)) {
	    		while(false !== ($arch = readdir($gestor))){
					if ($arch != "." && $arch != ".."){
				   		//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
						@unlink($ruta."/".$id."_".$nombreArchivo);
					}
	    		}
			}
	   	 	closedir($gestor);
		}
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	}

	//Funcion encargada de mostrar la lista_maestra en una ventana pop up en caso de existir
	function mostrarReferencias($referencias){
		//Verificamos que exista la session
		if($_SESSION['referencias']){
			$id = "";
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
				if($arrVale['id']!="no"){
					$id = $arrVale['id'];
				}
				else{
					$id = "no";
				}
				echo "<tr>
						<td align='center'  class='$nom_clase'>$arrVale[clave]</td>
						<td align='center'  class='$nom_clase'>$arrVale[referencia]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	>
							<input type="image" src="../../images/borrar.png" width="30" height="25"
							border="0" title="Borrar Registro" 
							onclick="location.href='verModReferencias.php?noRegistro=<?php echo $key;?>&id=<?php echo $id;?>'"/>
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