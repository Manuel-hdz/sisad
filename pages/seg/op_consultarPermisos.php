<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:25/Febrero/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de donde se Generan los Tipos de Permisos
	**/

	//Funcion para consultar la informacion del Permiso
	function consultarPermisos(){
		//Conectar a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Se recuperan los datos que se encuentran en el POST
		$tipoPermiso = $_POST['cmb_tipoPermiso'];
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		obtenerDato("bd_seguridad","permisos_trabajos","tipo_permiso","id_permiso_trab",$tipoPermiso);
		
		//Variable que almacenara el resultado de la consulta de acuerdo al tipo de permiso consultado desde el combo cmb_tipoPermiso
		$consulta = "";

		//Verificar que tipo de permiso es el seleccionado
		if($_POST['cmb_tipoPermiso']=="TRABAJOS ALTURAS"){
			//Crear sentencia SQL
			$sql_stm_PTA = "SELECT * FROM permisos_trabajos WHERE tipo_permiso = '$tipoPermiso' AND fecha_ini BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY id_permiso_trab";			
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg_PTA = "Permisos del  <em><u> ".$_POST['txt_fechaIni']."</u></em> al <em><u>   ".$_POST['txt_fechaFin']." </u></em> de los <em><u>  $tipoPermiso </u></em>";
			
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error_PTA = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Permiso Registrado del
			<em><u>  ".$_POST['txt_fechaIni']." </u></em> al <em><u>  ".$_POST['txt_fechaFin']." </u></em> de los <em><u>  '$tipoPermiso' </u></em>";
			
			//Ejecutar la sentencia previamente creada
			$rs_PTA = mysql_query($sql_stm_PTA);									
	
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos_PTA=mysql_fetch_array($rs_PTA)){
	
				//Esta variable es para que retorne el valor de la consulta dentro de este documento y dentro del op_consultarPermisos.php
				$consulta = $sql_stm_PTA;
		
				//Desplegar los resultados de la consulta en una tabla
				echo "<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='8' align='center' class='titulo_etiqueta'>$msg_PTA</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE PERMISO</td>
						<td class='nombres_columnas' align='center'>NOMBRE SOLICITANTE</td>
						<td class='nombres_columnas' align='center'>NOMBRE SUPERVISOR</td>
						<td class='nombres_columnas' align='center'>NOMBRE RESPONSABLE</td>
						<td colspan='2' class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N DEL TRABAJO</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>						
						<td class='nombres_columnas' align='center'>GENERAR PERMISO EN EXCEL</td>						
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{	
					//Mostrar todos los registros que han sido completados
					echo "<tr>
							<td class='$nom_clase' align='center'>$datos_PTA[id_permiso_trab]</td>
							<td class='$nom_clase' align='center'>$datos_PTA[nom_solicitante]</td>
							<td class='$nom_clase' align='center'>$datos_PTA[nom_supervisor]</td>
							<td class='$nom_clase' align='center'>$datos_PTA[nom_responsable]</td>
							<td colspan='2' class='$nom_clase' align='center'>$datos_PTA[descripcion_trabajo]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos_PTA['fecha_ini'],1)."</td>";?>
							<td class='<?php echo $nom_clase;?>' align='center'>
									<input name="sbt_exportar" type="submit" class="botones" id="sbt_exportar"  value="Ver Permiso" 
										title="Generar el Permiso en Formato Excel" 
										onmouseover="window.status='';return true" 
										onclick="location.href='guardar_reportePermisos.php?clavePermisoSegAlturas=<?php echo $datos_PTA['id_permiso_trab']?>'" />				
							</td>
					<?php
				"</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos_PTA=mysql_fetch_array($rs_PTA));
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "</table>";
			}// fin  if($datos=mysql_fetch_array($rs))
			else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
				echo $msg_error_PTA;					
			}
			return $consulta;
			//return $msg_permisos;
		}//Cierre if($_POST['cmb_tipoPermiso']=='TRABAJOS ALTURAS')
		
		
		//Verificar que tipo de permiso es el seleccionado
		 if($_POST['cmb_tipoPermiso']=='TRABAJOS FLAMA ABIERTA'){
		
			//Crear sentencia SQL
			$sql_stm_PTF ="SELECT * FROM permisos_trabajos WHERE tipo_permiso = '$tipoPermiso' AND fecha_ini BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY id_permiso_trab";		
										
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg_PTF = "Permisos del  <em><u> ".$_POST['txt_fechaIni']." </u></em> al <em><u>  ".$_POST['txt_fechaFin']." </u></em> de los <em><u>  $tipoPermiso </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error_PTF = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Permiso Registrado del
			<em><u>  ".$_POST['txt_fechaIni']."</u></em> al <em><u>  ".$_POST['txt_fechaFin']." </u></em> de los <em><u>  '$tipoPermiso' </u></em>";
		
			//Ejecutar la sentencia previamente creada
			$rs_PTF = mysql_query($sql_stm_PTF);									
		
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos_PTF=mysql_fetch_array($rs_PTF)){
		
				//Esta variable es para que retorne el valor de la consulta dentro de este documento y dentro del op_consultarPermisos.php
				$consulta = $sql_stm_PTF;

				//Desplegar los resultados de la consulta en una tabla
				echo "<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='8' align='center' class='titulo_etiqueta'>$msg_PTF</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE PERMISO</td>
						<td class='nombres_columnas' align='center'>FOLIO DEL PERMISO</td>
						<td class='nombres_columnas' align='center'>NOMBRE EMPRESA &Oacute; CONTRATISTA</td>
						<td class='nombres_columnas' align='center'>LUGAR DE TRABAJO</td>
						<td colspan='2' class='nombres_columnas' align='center'>TRABAJO ESPECIFICO</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{	
					//Mostrar todos los registros que han sido completados
					echo "<tr>
							<td class='$nom_clase' align='center'>$datos_PTF[id_permiso_trab]</td>
							<td class='$nom_clase' align='center'>$datos_PTF[folio_permiso]</td>
							<td class='$nom_clase' align='center'>$datos_PTF[nom_contratista]</td>
							<td class='$nom_clase' align='center'>$datos_PTF[lugar_trabajo]</td>
							<td colspan='2' class='$nom_clase' align='center'>$datos_PTF[trabajo_especifico]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos_PTF['fecha_ini'],1)."</td>
						</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos_PTF=mysql_fetch_array($rs_PTF));
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "</table>";
				
			}// fin  if($datos=mysql_fetch_array($rs))
			else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
				echo $msg_error_PTF;		
			}
			return $consulta;
		}//Cierre else if($_POST['cmb_tipoPermiso']=='TRABAJOS FLAMA ABIERTA')
		
		
		//Verificar que tipo de permiso es el seleccionado
		if($_POST['cmb_tipoPermiso']=="TRABAJOS PELIGROSOS" ){
			//Crear sentencia SQL
			$sql_stm_PTP = "SELECT * FROM permisos_trabajos JOIN permisos_secundarios ON permisos_secundarios_id_permiso_secundario = id_permiso_secundario
				 WHERE tipo_permiso = '$tipoPermiso' AND fecha_ini BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY id_permiso_trab";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg_PTP = "Permisos del  <em><u> ".$_POST['txt_fechaIni']." </u></em> al <em><u>  ".$_POST['txt_fechaFin']." </u></em> de los <em><u>  $tipoPermiso  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error_PTP = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Permiso Registrado del
			<em><u>  ".$_POST['txt_fechaIni']." </u></em> al <em><u>  ".$_POST['txt_fechaFin']." </u></em> de los <em><u>  $tipoPermiso </u></em>";
			
			//Ejecutar la sentencia previamente creada
			$rs_PTP = mysql_query($sql_stm_PTP);									
	
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos_PTP=mysql_fetch_array($rs_PTP)){
				
				//Esta variable es para que retorne el valor de la consulta dentro de este documento y dentro del op_consultarPermisos.php
				$consulta = $sql_stm_PTP;
			
				//Desplegar los resultados de la consulta en una tabla
				echo "<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='8' align='center' class='titulo_etiqueta'>$msg_PTP</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE PERMISO</td>
						<td class='nombres_columnas' align='center'>TIPO PERMISO</td>												
						<td class='nombres_columnas' align='center'>NOMBRE SOLICITANTE</td>
						<td class='nombres_columnas' align='center'>NOMBRE SUPERVISOR</td>
						<td class='nombres_columnas' align='center'>NOMBRE RESPONSABLE</td>
						<td colspan='2' class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N DEL TRABAJO</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>	
						<td class='nombres_columnas' align='center'>GENERAR PERMISO EN EXCEL</td>						
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='$nom_clase' align='center'>$datos_PTP[id_permiso_trab]</td>
							<td class='$nom_clase' align='center'>$datos_PTP[nom_permiso]</td>														
							<td class='$nom_clase' align='center'>$datos_PTP[nom_solicitante]</td>
							<td class='$nom_clase' align='center'>$datos_PTP[nom_supervisor]</td>
							<td class='$nom_clase' align='center'>$datos_PTP[nom_responsable]</td>
							<td colspan='2' class='$nom_clase' align='center'>$datos_PTP[descripcion_trabajo]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos_PTP['fecha_ini'],1)."</td>";?>
							<td class='<?php echo $nom_clase;?>' align='center'>
									<input name="sbt_exportar" type="submit" class="botones" id="sbt_exportar"  value="Ver Permiso" 
										title="Generar el Permiso en Formato Excel" 
										onmouseover="window.status='';return true" 
										onclick="location.href='guardar_reportePermisos.php?clavePermisoSeg=<?php echo $datos_PTP['id_permiso_trab']?>'" />				
							</td>		
					<?php
				"</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos_PTP=mysql_fetch_array($rs_PTP));
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "</table>";
			}// fin  if($datos=mysql_fetch_array($rs))
			else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
				echo $msg_error_PTP;					
			}
		}//Cierre if($_POST['cmb_tipoPermiso']=="TRABAJOS PELIGROSOS")
		
		return $consulta;
	}//Cierre de la funcion consultarPermisos()

?>	