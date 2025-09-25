<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 01/Marxo/2012
	  * Descripción: Este archivo contiene funciones para consultar los recorridos de seguridad
	**/
	
	
	
	if(isset($_POST['sbt_desactivar'])){
		desactivarAlertas();
	}
	
	//Funcion que permite desactivar la alerta; realizando un update a la tabl alertas recorridos
	function desactivarAlertas(){
		//Iniciamos la Sesion
		session_start();
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
				
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
		
		//Realizamos la conexion a la base de datos
		$conn = conecta("bd_seguridad");
		
		//Ciclo que permite cambiar el estado de la alerta elegida
		foreach($_POST as $key => $value){
			//Creamos la sentencia SQL
			$stm_sql = "DELETE FROM alertas_recorridos_seguridad WHERE catalogo_departamentos_id_departamento = '$idDepto'";
			
			//Ejecutamos la sentencia Previamente Creada
			$rs= mysql_query($stm_sql);
		}
		
		if($rs){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerramos la conexion
		mysql_close($conn);
	}
	
	
		
	//Función que permite mostrar los documentos Registrados en las fechas especificadas
	function mostrarRegistros($alerta){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Verificamos el contenido del post
		if($alerta!=1){
			$idRec=obtenerDato("bd_seguridad", "alertas_recorridos_seguridad", "recorridos_seguridad_id_recorrido", "id_alerta_recorrido", $alerta);
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM recorridos_seguridad   WHERE id_recorrido = '$idRec' ORDER BY id_recorrido ";
			//Creaqmos titulo
			$titulo = "REGISTROS DE RECORRIDOS DE SEGURIDAD CON CLAVE <u><em><strong>$idRec</strong></em></u>";
			//Titulo en caso de no existir registros
			$noTitulo = "No existen Registros de Recorrido de Seguridad con Clave <strong>$idRec</strong>";
		}
		else{
			//Guardamos el Departamento Actual
			$user=$_SESSION['usr_reg'];
			//Obtenemos el id del Departameno
			$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
			//Realizar la conexion a la BD de Seguridad ya que  la funcion obtener dato cierra la conexion actual
			$conn = conecta("bd_seguridad");			
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM (recorridos_seguridad  JOIN alertas_recorridos_seguridad ON recorridos_seguridad_id_recorrido = id_recorrido) 
					   WHERE catalogo_departamentos_id_departamento = '$idDepto'";
			//Creamos el titulo
			$titulo = "REGISTROS DE RECORRIDOS DE SEGURIDAD REGISTRADOS";
			//Creamos el titulo en caso de no existir registros
			$noTitulo = "No existen Registros de Recorrido de Seguridad de Registrados";
		}
		
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption></br>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>REGISTRO FOTOGR&Aacute;FICO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//Arreglo que permite el almacenamiento de el resultado de la consulta		
				$arrArch=array();
				//Consulta que permite descargar el archivo 
				$stm_sqlArch = "SELECT * FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$datos[id_recorrido]' ORDER BY nom_archivo";
				//Ejecutamos la sentencia Previamente creada
				$rs2=mysql_query($stm_sqlArch);
				//Contamos el Numero de archivos
				$noReg = mysql_num_rows(mysql_query($stm_sqlArch));
				//Guardamos el resultado de la consulta en $arrArch
				$arrArch=mysql_fetch_array($rs2);			
				echo "	<tr>	
							<td class='$nom_clase' align='center'>$cont</td>";?>
							<input type='hidden' name='hdn_registro<?php echo $cont;?>' id='hdn_registro<?php echo $cont;?>' 
							value="<?php echo  $datos['id_recorrido'];?>"/>
				<?php echo "<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' align='center'>$datos[responsable]</td>	
							<td class='$nom_clase' align='center'>$datos[observaciones]</td>";?>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<input name="btn_verReg" type="button" class="botones" id="btn_verReg"  value="Anomal&iacute;a" 
								title="Ver Anomal&iacute;as Registradas" onclick="verAnomalias('<?php echo $datos['id_recorrido'];?>');"
								onmouseover="window.status='';return true" /> 
							</td>
							<td class='<?php echo $nom_clase;?>' align='center'>
								<?php
								//Verificamos el numero de registros para ver que boton sera el mostrado
								if($noReg==1){?>
									<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
									title="Descargar Imagen <?php echo $arrArch['nom_archivo'];?>" 
									onClick="javascript:window.open('marco_descargaRS.php?id=<?php echo $arrArch['detalle_recorridos_seguridad_id_detalle_recorrido_seguridad'];?>&nomArchivo=<?php echo $arrArch['nom_archivo'];?>&ruta=<?php echo $datos['id_recorrido'];?>',
									'_blank','top=300, left=450, width=1, height=1, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/><?php 
								}
								elseif($noReg>1){?>
									<input name="btn_verFoto" type="button" class="botones" id="btn_verFoto"  value="Ver Archivos" 
									title="Ver Registro Fotogr&aacute;fico" onclick="verFotografias('<?php echo $datos['id_recorrido'];?>');"
									onmouseover="window.status='';return true" /><?php 
								}
								elseif($noReg==0){?>
									<input name="btn_verFoto" type="button" class="botones" id="btn_verFoto"  value="Descargar"  disabled="disabled"
									title="No Existe Registro Fotog&aacute;ficon Para El Registro Seleccionado" 
									onclick="verFotografias('<?php echo $datos['id_recorrido'];?>');"
									onmouseover="window.status='';return true" /><?php 
							}?>
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
			echo "<p class='msje_correcto' align='center'></br></br></br></br>$noTitulo</p>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Función que permite mostrar las fotos registradas segun el id
	function mostrarFotosRS($id){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT * FROM registro_fotografico WHERE recorridos_seguridad_id_recorrido='$id' ORDER BY nom_archivo";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ARCHIVOS REGISTRADOS</caption>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
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
					<td class='$nom_clase'>$datos[nom_archivo]</td>";?>
					<td class="<?php echo $nom_clase;?>">
						<input type="button" name="btn_Archivo" class="botones" value="Descargar" onMouseOver="window.estatus='';return true" 
						title="Descargar Imagen<?php echo $datos['nom_archivo'];?>" 
						onClick="javascript:window.open('marco_descargaRS.php?id=<?php echo $datos['detalle_recorridos_seguridad_id_detalle_recorrido_seguridad'];?>&nomArchivo=<?php echo $datos['nom_archivo'];?>&ruta=<?php echo $id;?>',
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
	
	
	//Función que permite mostrar las Anomalias
	function mostrarAnomalias($id){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL si viene de la alerta
		$stm_sql ="SELECT * FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$id'";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ANOMAl&Iacute;AS REGISTRADAS</caption>
					<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>LUGAR</td>
						<td class='nombres_columnas' align='center'>ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>CORRECCI&Oacute;N ANOMAL&Iacute;A</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
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
					<td class='$nom_clase'>$datos[area]</td>
					<td class='$nom_clase'>$datos[lugar]</td>
					<td class='$nom_clase'>$datos[anomalia]</td>
					<td class='$nom_clase'>$datos[correccion_anomalia]</td>
					<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
				</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Anomal&iacute;as Registradas </label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	
	
?>