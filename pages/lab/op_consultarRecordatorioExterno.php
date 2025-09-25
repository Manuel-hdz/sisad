<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad- Almacen                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 16/Diciembre/2011                                      			
	  * Descripción: Este archivo permite consultar el estado de los Recordatorios
	  **/
	 	
	function mostrarRecordatorios(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
		
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Obtenemos la bd para realizar la conexion
		if(isset($_GET['seg'])){
			//Realizar la conexion a la BD de Seguridad
			$conn = conecta("bd_seguridad");
			//Igualamos la variable de departamento al prefijo seg para proceder a enviar en el get y conocer de donde proviene la alerta
			$depto = "seg";
		}
		else{
			//Realizar la conexion a la BD de Aseguramiento
			$conn = conecta("bd_aseguramiento");
			$depto = "ase";
		}			
		
		
		
		//Si se encuentra definido el id de la Alerta seleccionamos un registro particular de lo contrario mostramos todos los registros
		if(isset($_GET['idAlerta'])){
			$idAlerta=$_GET['idAlerta'];
			//Creamos la sentencia SQL si viene de la alerta
			$stm_sql ="SELECT DISTINCT id_alerta, fecha_programada, descripcion FROM (alertas_generales JOIN detalle_alertas_generales ON 
						id_alerta=detalle_alertas_generales.alertas_generales_id_alerta) WHERE tipo_alerta='EXTERNA' AND id_alerta='$idAlerta' AND
					   detalle_alertas_generales.catalogo_departamentos_id_departamento='$idDepto' ORDER BY id_alerta";
		}
		else{
			$stm_sql ="SELECT DISTINCT id_alerta, fecha_programada, descripcion FROM (alertas_generales JOIN detalle_alertas_generales ON 
					   id_alerta=detalle_alertas_generales.alertas_generales_id_alerta) 
					   WHERE tipo_alerta='EXTERNA' AND estado='1' AND detalle_alertas_generales.catalogo_departamentos_id_departamento='$idDepto' 
					   AND dias_restantes<=5 ORDER BY id_alerta";
		}
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ACTIVIDADES REGISTRADAS</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>ACTIVIDAD A REALIZAR</td>
						<td class='nombres_columnas' align='center'>FECHA PROGRAMADA</td>
						<td class='nombres_columnas' align='center'>ARCHIVO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Arreglo que permite el almacenamiento de el resultado de la consulta		
				$arrArch=array();
				//Consulta que permite descargar el archivo 
				$stm_sqlArch = "SELECT DISTINCT id_documento, nombre, nom_archivo, nombre, tipo_archivo, ruta FROM (repositorio_documentos JOIN archivos_vinculados ON 
								repositorio_documentos_id_documento=id_documento) WHERE alertas_generales_id_alerta='$datos[id_alerta]'";
				//Ejecutamos la sentencia Previamente creada
				$rs2=mysql_query($stm_sqlArch);
				//Contamos el Numero de archivos
				$noReg = mysql_num_rows(mysql_query($stm_sqlArch));
				//Guardamos el resultado de la consulta en $arrArch
				$arrArch=mysql_fetch_array($rs2);
			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas'>$cont</td>
					<td class='$nom_clase'>$datos[descripcion]</td>
     		        <td class='$nom_clase'>".modFecha($datos['fecha_programada'],1)."</td>";?>
					<td class="<?php echo $nom_clase; ?>" align="center">
				<?php if($noReg!=0){
						if($noReg==1){?>
							<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
							title="Descargar Documento<?php echo $arrArch['nombre'];?>" 
							onClick="javascript:window.open('marco_descarga.php?id_documento=<?php echo $arrArch['id_documento'];?>&nomArchivo=<?php echo $arrArch['nom_archivo'];?>&ruta=<?php echo $arrArch['ruta'];?>&nombre=<?php echo $arrArch['nombre'];?>&tipo=<?php echo $arrArch['tipo_archivo'];?>&depto=<?php echo $depto;?>',
								'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
				 <?php }
					   elseif($noReg>1){?>
					   		<input type="button" name="btn_Archivo" class="botones" value="Ver Archivos" onMouseOver="window.estatus='';return true" 
							title="Descargar Documento<?php echo $arrArch['nombre'];?>" 
							onClick="javascript:window.open('verArchivos.php?id_alerta=<?php echo $datos['id_alerta'];?>&depto=<?php echo $depto;?>',
								'_blank','top=300, left=450, width=450, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>				
					<?php }
				 }
				elseif($noReg==0){?>
					<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true"  disabled="disabled"
					title="Aviso Sin Archivo Anexo" />		
			<?php }?>
				</td><?php 
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
			echo "<label class='msje_correcto'>  No existen Avisos Registrados </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Funcion que muestra los archivos registradas
	function mostrarArchivos($idAlerta, $depto){
			
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
				
		//Verificamos de cual departamento se esta enviando la alerta
		if($depto=="seg"){
			//Conectamos a la base de datos de seguridad
			$conn = conecta("bd_seguridad");
		}
		else{
			//Conectamos a la base de aseguramiento de la calidad
			$conn = conecta("bd_aseguramiento");
		}		
		
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
						onClick="javascript:window.open('marco_descarga.php?id_documento=<?php echo $datos['id_documento'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $datos['ruta'];?>&nombre=<?php echo $datos['nombre'];?>&tipo=<?php echo $datos['tipo_archivo'];?>&depto=<?php echo $depto;?>',
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