<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                              
	  * Nombre Programador: Nadia Madahí López Hernpandez
	  * Fecha:09/Marzo/2012                                      			
	  * Descripción: Este archivo permite modificar el estado de los Planes de Contingencia
	  **/
	 
	//Funcion que permite mostrar los Recordatorios regisatrados  	
	function mostrarRecordatorios(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Si viene definido el $get tomamos el id de la alerta; ya que indica que es una sola alerta
		if(isset($_GET['idAlerta'])){
			$idAlerta=$_GET['idAlerta'];
			//Creamos la sentencia SQL si viene de la alerta
			$stm_sql ="SELECT *	FROM alertas_planes_contingencia WHERE id_alerta_plan='$idAlerta' ORDER BY id_alerta_plan";
		}
		//De lo contrario seleccionamos aquellas que cumplan con los parametros
		else{
			//Cremos la consulta
			$stm_sql ="SELECT *	FROM alertas_planes_contingencia WHERE  estado='NO' ORDER BY id_alerta_plan";
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

						<td class='nombres_columnas' align='center'>FECHA PROGRAMADA</td>

						<td class='nombres_columnas' align='center'>NOMBRE SIMULACRO</td>

					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_alerta_plan]'/>
					</td>
					<td class='$nom_clase'>$datos[id_alerta_plan]</td>"; 
				echo "
     		          <td class='$nom_clase'>".modFecha($datos['fecha_programada'],1)."</td>
					  
					  <td class='$nom_clase'>$datos[nom_simulacro]</td>";
					  //if($datos['tipo_alerta']=="EXTERNA"){?>
					  <td class="<?php echo $nom_clase; ?>" align="center">
					  	<input type="button" name="btn_Archivo" class="botones" value="Departamenos" onMouseOver="window.estatus='';return true" 
						title="Ver Departamentos<?php echo $datos['nom_simulacro'];?>" 
						onClick="javascript:window.open('verDepartamentos.php?idAlerta=<?php echo $datos['id_alerta_plan'];?>',
								'_blank','top=300, left=450, width=400, height=300, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>					<?php
					//}
					//else{?>
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
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Recuperamos el Id de la alerta
		$idAlerta = $_POST['rdb_id'];
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Creamos la conslulta SQL que permite eliminar el plano de la BD
		$stm_sql2 ="DELETE FROM alertas_planes_contingencia WHERE id_alerta_plan='$idAlerta'";
			
		//Ejecutamos la consulta
		$rs2=mysql_query($stm_sql2);
		
		if($rs2){
			//Registramos la operación en la bitacora de movimientos
			registrarOperacion("bd_seguridad",$idAlerta,"EliminarAlertaPlanC",$_SESSION['usr_reg']);
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