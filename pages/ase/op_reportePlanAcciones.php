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
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM plan_acciones WHERE area_auditada='$_POST[cmb_depto]'";
					
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
				//Sentencia sQL para determinar si el registro seleccionado del plan de acciones fue complenentado
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
								<input type='radio' id='rdb_id' name='rdb_id' value='<?php echo $datos['id_plan_acciones'];?>' onclick='verificaPlanAcciones(this.value);'/><?php 
					echo " </td>				
							<td class='$nom_clase' align='center'>$datos[area_auditada]</td>					
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
									<input type="button" name="btn_Deptos" class="botones" value="Evidencias" onMouseOver="window.estatus='';return true" 
									title="Ver Evidencias Con Copia del Plan de Acciones" 
									onClick="javascript:window.open('verEvidenciasPlanAcciones.php?idPlanAcciones=<?php echo $datos['id_plan_acciones'];?>',
									'_blank','top=300, left=450, width=400, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
							</td>
						<?php }
						elseif($bandera==0){?>
							<td class='<?php echo $nom_clase;?>' align='left'> <strong>EL REGISTRO NO HA SIDO COMPLEMENTADO</strong></td>
						<?php }
						elseif($bandera==2){?>
							<td class="<?php echo $nom_clase; ?>" align="center">
									<input type="button" name="btn_Deptos" class="botones" value="Evidencias" onMouseOver="window.estatus='';return true" 
									title="El Registro Seleccionado No Cuenta Con Evidencias Cargadas O no Se Encuentra Complementado Verifique el Registro" 
									disabled="disabled"/>							
							</td>
						<?php }
						echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";?>	
			<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
			<input name="hdn_nomReporte" type="hidden" value="Reporte_Plan_Acciones_<?php echo $datos['id_plan_acciones'];?>" />
			<input name="hdn_msg" type="hidden" value="<?php echo $datos['id_plan_acciones'];?>"/>
			<input name="hdn_origen" type="hidden" value="reportePA"/>
			<input type="hidden" name="hdn_depto" value="<?php echo $_POST['cmb_depto'];?>"/><?php 
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Plan de Acciones Registrados para <em>".$_POST['cmb_depto']."</em> </label>";
			return 0;
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	
	
	
	//Función que permite mostrar las fotos registradas segun el id
	function mostrarArchivosPA($id){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion
		$conn = conecta("bd_aseguramiento");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT * FROM detalle_referencias WHERE plan_acciones_id_plan_acciones='$id' ORDER BY nom_archivo";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ARCHIVOS REGISTRADOS</caption>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>JUSTIFICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>VALIDACI&Oacute;N ASE</td>
						<td class='nombres_columnas' align='center'>ARCHIVO</td>
						<td class='nombres_columnas' align='center'>DESCARGAR</td>
					</tr>";
			//Contamos el Numero de archivos
			$noReg = mysql_num_rows(mysql_query($stm_sql));
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase' align='center'>$datos[justificacion]</td>
					<td class='$nom_clase' align='center'>$datos[validacion_ase]</td>
					<td class='$nom_clase' align='center'>$datos[nom_archivo]</td>";?>
					<td class="<?php echo $nom_clase;?>" align="center">
						<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
						title="Descargar Archivo<?php echo $datos['nom_archivo'];?>" 
						onClick="javascript:window.open('marco_descargaPA.php?id=<?php echo $datos['referencias_id_referencia'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $datos['referencias_id_referencia'];?>',
								'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/><?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Archivos Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
?>