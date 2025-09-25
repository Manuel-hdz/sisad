<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 23/Diciembre/2011                                      			
	  * Descripción: Este archivo permite complementar el plan de acciones
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
		
		//Verificamos si existe el combo departamento si existe se esta llegando a esta pagina por medio del modificar auditoria
		if(isset($_POST['cmb_depto'])){
			$depto = $_POST['cmb_depto'];		
		
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM plan_acciones WHERE area_auditada='$depto'";
					
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
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA AUDITADA</th>
						<th class='nombres_columnas' align='center'>CREADO POR</th>
						<th class='nombres_columnas' align='center'>APROBADO POR</th>
						<th class='nombres_columnas' align='center'>VERIFICADO POR<></th>
						<th class='nombres_columnas' align='center'>FECHA REGISTRO</th>
						<td class='nombres_columnas' align='center'>REVISI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>NO. DOCUMENTO</td>
						<td class='nombres_columnas' align='center'>PARTICIPANTES</td>
						<td class='nombres_columnas' align='center'>COPIAS ENTREGADAS</td>
						<td class='nombres_columnas' align='center'>EVIDENCIA</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{					
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_plan_acciones]'/>
							</td>				
							<td class='$nom_clase' align='center'>$datos[area_auditada]</td>					
							<td class='$nom_clase' align='left'>$datos[creador]</td>
							<td class='$nom_clase' align='center'>$datos[aprobador]</td>
							<td class='$nom_clase' align='center'>$datos[verificador]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],6)."</td>
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
			echo "<label class='msje_correcto'>  No existen ¨Plan de Acciones Registrados para <em>".$_POST['cmb_depto']."</em> </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Función que permite mostrar el plan de acciones para registrar la informacion complementaria
	function mostrarPosiblesRegistros(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM referencias WHERE plan_acciones_id_plan_acciones='$_POST[rbd_id]'";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultados'>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<th class='nombres_columnas' align='center'>REFERENCIA</th>
						<th class='nombres_columnas' align='center'>NO. REFERENCIA</th>
						<th class='nombres_columnas' align='center'>JUSTIFICACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>ACCI&Oacute;N PLANEADA/ FECHA PLANEADA<></th>
						<th class='nombres_columnas' align='center'>FECHA REAL DE TERMINACI&Oacute;N DE DOCUMENTO</th>
						<td class='nombres_columnas' align='center'>VALIDACI&Oacute;N ASE CALIDAD</td>
						<td class='nombres_columnas' align='center'>EVIDENCIA</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{					
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_plan_acciones]'/>
							</td>				
							<td class='$nom_clase' align='center'>$datos[area_auditada]</td>					
							<td class='$nom_clase' align='left'>$datos[creador]</td>
							<td class='$nom_clase' align='center'>$datos[aprobador]</td>
							<td class='$nom_clase' align='center'>$datos[verificador]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],6)."</td>
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
					<?php
						echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen ¨Plan de Acciones Registrados para <em>".$_POST['cmb_depto']."</em> </label>";
			return 0;
		}?>							
		<?php

		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
		
	
	// Funcion que se encarga de mostrar los equipos de acuerdo a la area y familia seleccionados
	function mostrarRegistrosRegistrar(){
		
		//Conectar a la BD
		$conn = conecta("bd_aseguramiento");
		
		$idPA=$_POST['rdb_id'];
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM (referencias JOIN detalle_referencias ON id_referencia=referencias_id_referencia) 
				WHERE referencias.plan_acciones_id_plan_acciones='$idPA'";
			
		$titulo="REFERENCIAS REGISTRADAS PARA  EL DEPARTAMENTO DE <em>".$_POST['hdn_depto']."</em>";
			
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_alerta = "	<label class='msje_correcto' align='center'>NO EXISTEN REFERENCIAS REGISTRADAS PARA EL DEPARTAMENTO SELECCIONADO</em>
		</label>";										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<table cellpadding='5' class='tabla_frm'>      			
			<tr>
				<td colspan='8' align='center' class='titulo_etiqueta'>".$titulo."</td>
			</tr>
			<tr>
				<td class='nombres_columnas'>NO.</td>
				<td class='nombres_columnas'>NO. REF.</td>
				<td class='nombres_columnas'>DESVIACI&Oacute;N</td>
				<td class='nombres_columnas'>JUSTIFICACI&Oacute;N</td>
				<td class='nombres_columnas'>ACCI&Oacute;N PLANEADA</td>
				<td class='nombres_columnas' title='Fecha en Formato dd/mm/aaaa'>FECHA PLANEADA</td>
				<td class='nombres_columnas' title='Fecha en Formato dd/mm/aaaa'>FECHA REAL TERMINACI&Oacute;N</td>
				<td class='nombres_columnas'>VALIDACI&Oacute;N ASE</td>
				<td class='nombres_columnas' align='center'>EVIDENCIA</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{	
				$fechaPla=$datos['fecha_planeada'];
				if($fechaPla!=""){
					$fechaPla=modFecha($datos['fecha_planeada'],1);
				}
				else{
					$fechaPla="";
				}
				$fechaRealTerm=$datos['fecha_real_terminacion'];
				if($fechaRealTerm!=""){
					$fechaRealTerm=modFecha($datos['fecha_real_terminacion'],1);
				}
				else{
					$fechaRealTerm="";
				}
				echo "
					<tr>
						<td width='70' class='$nom_clase'>$cont</td>
						<td width='70' class='$nom_clase'>$datos[no_referencia]</td>
						<td width='100' class='$nom_clase'>$datos[desv_obs_exp]</td>
						<td width='100' class='$nom_clase'>"; ?>
							<input type="hidden" name="hdn_idReg<?php echo $cont; ?>" id="hdn_idReg<?php echo $cont; ?>" value="<?php echo $datos['id_referencia'];?>"/>
							<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $cont;?>" 
							id="hdn_nombre<?php echo $cont; ?>"/>
							<textarea name="txa_justificacion<?php echo $cont;?>" id="txa_justificacion<?php echo $cont;?>" maxlength="240" 
							onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2"  cols="30" 
							onkeypress="return permite(event,'num_car', 0);"><?php echo $datos['justificacion'];?></textarea>
							<?php 
						echo "</td>
						<td width='100' class='$nom_clase'>"; ?>
							<textarea name="txa_accPla<?php echo $cont;?>" id="txa_accPla<?php echo $cont;?>" maxlength="240" 
							onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);" 
							><?php echo $datos['accion_planeada'];?></textarea><?php 
						echo "</td>	
						<td width='100' class='$nom_clase'>"; ?>
							<input type="text" name="txt_fechaPla<?php echo $cont;?>" id="txt_fechaPla<?php echo $cont;?>" size="10" 
							 onchange="formatFecha(this);" onkeypress="return permite(event,'num', 7);" value="<?php echo $fechaPla;?>" /><?php 
						echo "</td>	
						<td width='100' class='$nom_clase'>"; ?>
							<input type="text" name="txt_fechaReal<?php echo $cont;?>" id="txt_fechaReal<?php echo $cont;?>" size="10" 
						 	onchange="formatFecha(this);" onkeypress="return permite(event,'num', 7);" value="<?php echo $fechaRealTerm;?>"/><?php 
						echo " </td>	
						<td width='100' class='$nom_clase'>"; ?>
							<textarea name="txa_valASE<?php echo $cont;?>" id="txa_valASE<?php echo $cont;?>" maxlength="240" onkeyup="return ismaxlength(this)" 
							class="caja_de_texto" rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"
						 	><?php echo $datos['validacion_ase'];?></textarea><?php 
						echo "</td>	";
						echo "<td width='100' class='$nom_clase'>";
							if($datos['nom_archivo']==NULL){?>
								<input type="file" name="file_documento<?php echo $cont;?>" id="file_documento<?php echo $cont;?>" size="10" value=""/><?php 
							}
							else{
								echo "<strong>EVIDENCIA REGISTRADA</strong>";
							}
						echo " </td>";
				echo "</tr>";
					//Gurdar los datos en arreglo 
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					echo "<input type='hidden' name='hdn_idPA' id='hdn_idPA' value='$datos[plan_acciones_id_plan_acciones]'/>";				
			}while($datos=mysql_fetch_array($rs));
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
			$resultado = true;
		}// fin  if($datos=mysql_fetch_array($rs))
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Referencias Registradas para el Plan de Acciones Seleccionado </label>";
			$resultado = false;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
		
		return $resultado;
	}//fin function mostrarEquiposHorometro(){
	
	
	// Funcion que se encarga de mostrar los equipos de acuerdo a la area y familia seleccionados
	function mostrarRegistrosRegistrarSinRDB(){
		
		//Conectar a la BD
		$conn = conecta("bd_aseguramiento");		
		
		if(isset($_GET['idPA'])){
			$idPA = $_GET['idPA'];
			$sql_stm = "SELECT * FROM ((referencias JOIN detalle_referencias ON id_referencia=referencias_id_referencia)JOIN plan_acciones ON 
						referencias.plan_acciones_id_plan_acciones=id_plan_acciones) WHERE referencias.plan_acciones_id_plan_acciones='$idPA' ORDER BY area_auditada";
			echo "<input type='hidden' name='hdn_idPA' id='hdn_idPA' value='$idPA'/>";
		}
		else{		
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM ((referencias JOIN detalle_referencias ON id_referencia=referencias_id_referencia)JOIN plan_acciones ON 
						referencias.plan_acciones_id_plan_acciones=id_plan_acciones) ORDER BY area_auditada ";
		}
			$titulo="REFERENCIAS REGISTRADAS";
	
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_alerta = "";										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			echo "<table cellpadding='5' class='tabla_frm'>      			
			<tr>
				<td colspan='8' align='center' class='titulo_etiqueta'>".$titulo."</td>
			</tr>
			<tr>
				<td class='nombres_columnas'>NO.</td>
				<td class='nombres_columnas'>PLAN DE ACCI&Oacute;N</td>
				<td class='nombres_columnas'>&Aacute;REA AUDITADA</td>
				<td class='nombres_columnas'>NO. REF.</td>
				<td class='nombres_columnas'>DESVIACI&Oacute;N</td>
				<td class='nombres_columnas'>JUSTIFICACI&Oacute;N</td>
				<td class='nombres_columnas'>ACCI&Oacute;N PLANEADA</td>
				<td class='nombres_columnas title='Fecha en Formato dd/mm/aaaa'>FECHA PLANEADA</td>
				<td class='nombres_columnas' title='Fecha en Formato dd/mm/aaaa'>FECHA REAL TERMINACI&Oacute;N</td>
				<td class='nombres_columnas'>VALIDACI&Oacute;N ASE</td>
				<td class='nombres_columnas'>EVIDENCIA</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;			
			do{	
				$fechaPla=$datos['fecha_planeada'];
				if($fechaPla!=""){
					$fechaPla=modFecha($datos['fecha_planeada'],1);
				}
				else{
					$fechaPla="";
				}
				$fechaRealTerm=$datos['fecha_real_terminacion'];
				if($fechaRealTerm!=""){
					$fechaRealTerm=modFecha($datos['fecha_real_terminacion'],1);
				}
				else{
					$fechaRealTerm="";
				}
				if($datos['fecha_real_terminacion']==NULL&&$datos['justificacion']!=""){
					echo "
						<tr>
							<td class='$nom_clase'>$cont</td>
							<td class='$nom_clase'>$datos[id_plan_acciones]</td>
							<td class='$nom_clase'>$datos[area_auditada]</td>
							<td class='$nom_clase'>$datos[no_referencia]</td>
							<td class='$nom_clase'>$datos[desv_obs_exp]</td>
							<td class='$nom_clase'>"; ?>
								<input type="hidden" name="hdn_idReg<?php echo $cont; ?>" id="hdn_idReg<?php echo $cont; ?>" 
								value="<?php echo $datos['id_referencia'];?>"/>
								<input type="hidden" name="hdn_nombre<?php echo $cont; ?>"  value="<?php echo $datos["no_referencia"];?>" 
								id="hdn_nombre<?php echo $cont; ?>"/>
								<textarea name="txa_justificacion<?php echo $cont;?>" id="txa_justificacion<?php echo $cont;?>" maxlength="240" 
								onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2"  cols="30" 
								onkeypress="return permite(event,'num_car', 0);" readonly="readonly"><?php echo $datos['justificacion'];?></textarea>
								<?php 
							echo "</td>
							<td width='100' class='$nom_clase'>"; ?>
								<textarea name="txa_accPla<?php echo $cont;?>" id="txa_accPla<?php echo $cont;?>" maxlength="240" 
								onkeyup="return ismaxlength(this)"readonly="readonly"class="caja_de_texto" rows="2" cols="30" 
								onkeypress="return permite(event,'num_car', 0);" 
								><?php echo $datos['accion_planeada'];?></textarea><?php 
							echo "</td>	
							<td width='100' class='$nom_clase'>"; ?>
								<input type="text" name="txt_fechaPla<?php echo $cont;?>" id="txt_fechaPla<?php echo $cont;?>" size="10"  
								 onchange="formatFecha(this);" value="<?php echo $fechaPla;?>" readonly="readonly"/><?php 
							echo "</td>	
							<td width='100' class='$nom_clase'>"; ?>
								<input type="text" name="txt_fechaReal<?php echo $cont;?>" id="txt_fechaReal<?php echo $cont;?>" size="10" 
								onchange="formatFecha(this);" onkeypress="return permite(event,'num', 7);"; value="<?php echo $fechaRealTerm;?>"/><?php 
							echo " </td>	
							<td width='100' class='$nom_clase'>"; ?>
								<textarea name="txa_valASE<?php echo $cont;?>" id="txa_valASE<?php echo $cont;?>" maxlength="240" onkeyup="return ismaxlength(this)" 
								class="caja_de_texto" rows="2" cols="30"  onkeypress="return permite(event,'num_car', 0);"
								><?php echo $datos['validacion_ase'];?></textarea><?php 
							echo "</td>	";
							echo "<td width='100' class='$nom_clase'>";
								if($datos['nom_archivo']==NULL){?>
									<input type="file" name="file_documento<?php echo $cont;?>" id="file_documento<?php echo $cont;?>" size="10" value=""/><?php 
								}
								else{
									echo "<strong>EVIDENCIA REGISTRADA</strong>";
								}
						echo " </td>
						</tr>";
						//Gurdar los datos en arreglo 
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
						echo "<input type='hidden' name='hdn_idPA' id='hdn_idPA' value='$datos[plan_acciones_id_plan_acciones]'/>";	
				}
			}while($datos=mysql_fetch_array($rs));
			
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "	</table>";
			
			$resultado = true;
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo "<label class='msje_correcto'>  No existen Referencias Registradas para el Plan de Acciones Seleccionado </label>";
			$resultado = false;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
		
		return $resultado;
	}//fin function mostrarEquiposHorometro(){
	
	if(isset($_POST['sbt_guardar'])){
		guardarRegistro();
	}
	
	//Funcion que guarda los cambios en los registros seleccionados
	function guardarRegistro(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");		
		include_once("../../includes/op_operacionesBD.php");//Manejo de fechas
		include_once ("../../includes/func_fechas.php");
		
		//Conectamos con la BD
		$conn = conecta("bd_aseguramiento");
		//Variable bandera para la insercion de datos
		$flag=0;
		//Variable para almacenar el error en caso de generarse
		$error="";
		//Creamos la variable cantidad de la function mostrarEquiposHorometro() para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;
		//Iniciamos la variable de control interna
		$ctrl=0;
		session_start();		
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el horometro inicial y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["txa_justificacion$ctrl"])){
				$referencia = $_POST["hdn_idReg$ctrl"];
				$justificacion = strtoupper($_POST["txa_justificacion$ctrl"]);
				$accPla = strtoupper($_POST["txa_accPla$ctrl"]);
				$fechaPla = modFecha($_POST["txt_fechaPla$ctrl"],3);
				$fechaReal = modFecha($_POST["txt_fechaReal$ctrl"],3);
				$valASE = strtoupper($_POST["txa_valASE$ctrl"]);
				if(isset($_FILES)){
					$contar = count($_FILES);
					if($contar!=0){
						$nomArchivo = $_FILES["file_documento$ctrl"]['name'];
						//Creamos la sentencia SQL
						$stm_sql="UPDATE detalle_referencias SET justificacion = '$justificacion',
							 accion_planeada = '$accPla',fecha_planeada='$fechaPla',fecha_real_terminacion='$fechaReal',validacion_ase='$valASE',
							 nom_archivo='$nomArchivo' WHERE referencias_id_referencia = '$referencia'";
					}
					else{
						//Creamos la sentencia SQL
						$stm_sql="UPDATE detalle_referencias SET justificacion = '$justificacion',
							 accion_planeada = '$accPla',fecha_planeada='$fechaPla',fecha_real_terminacion='$fechaReal',validacion_ase='$valASE' 
							 WHERE referencias_id_referencia = '$referencia'";
					}
				}
				
				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				//Guardar el registro de movimientos
				registrarOperacion("bd_aseguramiento",$referencia,"RegistrarComplementoPA",$_SESSION['usr_reg']);
				//Conectamos con la BD
				$conn = conecta("bd_aseguramiento");
				if(isset($_FILES)){
					$contar = count($_FILES);
					if($contar!=0){
						subirEvidencia($referencia, $_FILES["file_documento$ctrl"]['tmp_name'],$nomArchivo);
					}
				}
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
					//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
					$error=mysql_error();
					break;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
			
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			//Sentencia que permite actualizar el estado de la alerta a cero; esto para darla de baja
			$sql_ActAlerta = "UPDATE alertas_plan_acciones SET estado=0 WHERE  plan_acciones_id_plan_acciones='$_POST[hdn_idPA]'";
			//Ejecutar la sentencia previamente creada
			$rsActAlerta=mysql_query($sql_ActAlerta);
			if($contar==0){
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}
		else{
			$error = mysql_error();
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);	
	
	}// Fin de la funcion 

//Esta funcion permite guardas los Documentos en el SERVIDOR
	function subirEvidencia($idRef, $nomFile, $nomArchivo){
		//Incluimos archivos para realizar las operaciones con la BAse de datos
		include_once("../../includes/op_operacionesBD.php");
		
		//Creamos la variable ruta que servira para abrir la misma	
		$Ruta='';
		
		//Vaiable que nos ayudar en el procesod e cargar imagen
		$carpeta='documentos/EVIDENCIAS';
		$archivo = $idRef."_".$nomArchivo;
		
		//Abrimos la ruta para crear la carpeta
		$dir = opendir($Ruta); 
		//Verificamos que el archivo haya sido updated
		if (is_uploaded_file($nomFile)) { 
			//Si $carpeta no ah sido creado se crea con mkdir
			if (!file_exists($carpeta."/")){
				mkdir($carpeta."/", 0777);
			}
			//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
			if(!file_exists($carpeta."/".$archivo)){		
				move_uploaded_file($nomFile, $carpeta."/".$archivo);     	    	 	
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";			
			}	
		}
		
	}//Fin de la funcion 
?>
