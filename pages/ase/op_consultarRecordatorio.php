<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 14/Diciembre/2011                                      			
	  * Descripción: Este archivo permite modificar el estado de los Recordatorios
	  **/
	 
	//Funcion que permite mostrar los Recordatorios regisatrados  	
	function mostrarRecordatorios(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		
		//Si viene definido el $get tomamos el id de la alerta; ya que indica que es una sola alerta
		if(isset($_GET['idAlerta'])){
			$idAlerta=$_GET['idAlerta'];
			//Creamos la sentencia SQL si viene de la alerta
			$stm_sql ="SELECT *	FROM alertas_generales WHERE id_alerta='$idAlerta' AND tipo_alerta='INTERNA' ORDER BY id_alerta";
		}
		//De lo contrario seleccionamos aquellas que cumplan con los parametros
		else{
			//Cremos la consulta
			$stm_sql ="SELECT *	FROM alertas_generales WHERE tipo_alerta='INTERNA' AND estado='1' ORDER BY id_alerta";
		}
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>RECORDADORIOS REGISTRADOS</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>ID ALERTA</td>
						<td class='nombres_columnas' align='center'>FECHA GENERACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>FECHA PROGRAMADA</td>
						<td class='nombres_columnas' align='center'>TIPO ALERTA</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>DEPARTAMENTOS</td>
						<td class='nombres_columnas' align='center'>ARCHIVOS VINCULADOS</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_alerta]'/>
					</td>
					<td class='$nom_clase'>$datos[id_alerta]</td>"; 
				echo "<td class='$nom_clase'>".modFecha($datos['fecha_generacion'],1)."</td>
     		          <td class='$nom_clase'>".modFecha($datos['fecha_programada'],1)."</td>
					  <td class='$nom_clase'>$datos[tipo_alerta]</td>
					  <td class='$nom_clase'>$datos[descripcion]</td>";
					  if($datos['tipo_alerta']=="EXTERNA"){?>
					  <td class="<?php echo $nom_clase; ?>" align="center">
					  	<input type="button" name="btn_Archivo" class="botones" value="Departamenos" onMouseOver="window.estatus='';return true" 
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
					  	<input type="button" name="btn_Archivo" class="botones" value="Departamenos" onMouseOver="window.estatus='';return true" 
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
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Recuperamos el Id de la alerta
		$idAlerta = $_POST['rdb_id'];
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Creamos la conslulta SQL que permite eliminar el plano de la BD
		$stm_sql2 ="DELETE FROM alertas_generales WHERE id_alerta='$idAlerta'";
			
		//Ejecutamos la consulta
		$rs2=mysql_query($stm_sql2);
		
		if($rs2){
			//Registramos la operación en la bitacora de movimientos
			registrarOperacion("bd_aseguramiento",$idAlerta,"EliminoRecordatorio",$_SESSION['usr_reg']);
			//Redireccionamos a la pantalla de éxito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
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