<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad- Almacen                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 16/Diciembre/2011                                      			
	  * Descripción: Este archivo permite consultar los planes de accion
	  **/
	 	
	function mostrarPlanAcciones(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		
		//Obtenemos el id del Departameno
		$depto=obtenerDato("bd_usuarios", "usuarios", "depto", "usuario", $user);
		$depto=strtoupper($depto);
		
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Si se encuentra definido el id de la Alerta seleccionamos un registro particular de lo contrario mostramos todos los registros
		if(isset($_GET['idAlerta'])){
			$idAlerta=$_GET['idAlerta'];
			//Creamos la sentencia SQL si viene de la alerta
			$stm_sql ="SELECT * FROM (alertas_plan_acciones JOIN plan_acciones ON  plan_acciones_id_plan_acciones=id_plan_acciones) 
						WHERE  estado='1' AND id_alertas_plan_acciones='$idAlerta' ORDER BY area_auditada";
		}
		if(isset($_GET['idAlerta'])&&$depto=="CALIDAD"){
			if($depto=="CALIDAD"){
				 $stm_sql ="SELECT * FROM (alertas_plan_acciones JOIN plan_acciones ON  plan_acciones_id_plan_acciones=id_plan_acciones) 
						WHERE  estado='1' AND area_auditada='$depto' ORDER BY area_auditada";
			}
			else{
				$stm_sql ="SELECT * FROM (alertas_plan_acciones JOIN plan_acciones ON  plan_acciones_id_plan_acciones=id_plan_acciones) 
						WHERE  estado='1' ORDER BY area_auditada";
			}
		}
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>PLANES DE ACCI&Oacute;N REGISTRADOS</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA AUDITADA</th>
						<th class='nombres_columnas' align='center'>CREADO POR</th>
						<th class='nombres_columnas' align='center'>APROBADO POR</th>
						<th class='nombres_columnas' align='center'>VERIFICADO POR</th>
						<th class='nombres_columnas' align='center'>FECHA REGISTRO</th>
						<td class='nombres_columnas' align='center'>REVISI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>NO. DOCUMENTO</td>
						<td class='nombres_columnas' align='center'>PARTICIPANTES</td>
						<td class='nombres_columnas' align='center'>COPIAS ENTREGADAS</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Mostrar todos los registros que han sido completados
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_plan_acciones]'/>
								<input type='hidden' name='hdn_depto' value='$datos[area_auditada]'/>
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
			echo "<label class='msje_correcto'>  No existen Planes de Acci&oacute;n Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Funcion que muestra los archivos registradas
	function mostrarArchivos($idAlerta){
			
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
				
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		$stm_sql ="SELECT DISTINCT id_documento, nombre, nom_archivo, nombre, tipo_archivo, ruta FROM (repositorio_documentos JOIN archivos_vinculados ON 
								repositorio_documentos_id_documento=id_documento) WHERE alertas_generales_id_alerta='$idAlerta'";

					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ARCHIVOS REGISTRADOS</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>ID. DOCUMENTO</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>DESCARGAR</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase'>$datos[id_documento]</td>
     		        <td class='$nom_clase'>$datos[nombre]</td>";?>
					<td class="<?php echo $nom_clase; ?>" align="center">
						<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
						title="Descargar Documento<?php echo $datos['nombre'];?>" 
						onClick="javascript:window.open('marco_descarga.php?id_documento=<?php echo $datos['id_documento'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $datos['ruta'];?>&nombre=<?php echo $datos['nombre'];?>&tipo=<?php echo $datos['tipo_archivo'];?>',
						'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>		
				</td><?php 
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