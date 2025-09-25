<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 06/Abril/2011
	  * Descripción: Este archivo contiene funciones para Registrar las asistencias de los empleados a una capacitacion en la BD de Recursos
	**/

	//Funcion que muestra los empleados segun el criterio de búsqueda 
	/*Valores de PATRON
		1 -> Nombre
		2 -> Area
	*/
	function mostrarEmpleados($patron){

		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
	
		//Recoger el id de la capacitacion si viene de consultar clave
		if (isset ($_SESSION['id_capacitacion']))
			$id_capacitacion= $_SESSION['id_capacitacion']['id_capacitacion'];
		//Recoger el id de la capacitacion si viene de la consulta por fechas
		if (isset ($_SESSION['id_capacitaciones']))
			$id_capacitacion= $_SESSION['id_capacitaciones']; 

		//Preparar la consulta para obtener el nombre de la capacitacion
		$sql_stm= "SELECT nom_capacitacion FROM capacitaciones WHERE id_capacitacion='$id_capacitacion'";
		//Ejecutamos la sentencia SQL
		$datos_cap = mysql_fetch_array(mysql_query($sql_stm));

		//Verificamos bajo que patron se esta pidiendo hacer la consulta
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre]'  AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["txt_nombre"]."</em>";
			$titulo2="Agregar a la Capacitaci&oacute;n <em><u>".$id_capacitacion.",	".$datos_cap['nom_capacitacion']."</u></em>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar los datos de los empleados que estan en el área que llega via POST
			$stm_sql="SELECT * FROM empleados WHERE area='$_POST[cmb_area]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Empleados del &Aacute;rea <em><u>".$_POST["cmb_area"]."</u></em>";
			$titulo2="Agregar a la Capacitaci&oacute;n <em><u>".$id_capacitacion.",	".$datos_cap['nom_capacitacion']."</u></em>";
		}
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' cellspacing='5' id='tabla-resultadosEmpleados' width=1200>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>
				<thead>";
			echo "<br />";
			echo "	
					<tr>";?>
						<td class="nombres_columnas" align="center"><input name="ckbTodo" type="checkbox" id="ckbTodo" 
                        onclick="checarTodos(this,'frm_guardarAsistencia');" value="Todo"/><?php						
			echo "		<strong>Seleccionar Todo</strong>
						</td>
						<td colspan='8'><strong>$titulo2</strong></td>					
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR </td>
						<th class='nombres_columnas' align='center'>RFC</th>
						<th class='nombres_columnas' align='center'>CURP</th>
						<th class='nombres_columnas' align='center'>ID EMPRESA</th>
						<th class='nombres_columnas' align='center'>ID &Aacute;REA</th>
						<td class='nombres_columnas' align='center'>NOMBRE </td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA</th>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";

			do{										
				echo "	
					<tr>
						<td class='nombres_filas' align='center'><input type='checkbox' name='ckb_$cont' value='$datos[rfc_empleado]' onclick='desSeleccionar(this);' />
						</td>
						<td class='$nom_clase' align='center'>$datos[rfc_empleado]</td>
						<td class='$nom_clase' align='left'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_area]</td>
						<td class='$nom_clase' align='center'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>";
				echo "	
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "</tbody>";				
			echo "</table>
				<input type='hidden' name='hdn_cantRegistros' id='hdn_cantRegistros' value='$cont' />";
			return 1;
		}
		else{
			if ($patron==1){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre]
				</u></em></p>";
			}
			if ($patron==2){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[cmb_area]</u>
				</em></p>";
			}
			return 0;
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarEmpleados
	
	function guardarAsistencias(){
		//Recoger el id de la capacitacion si vine de la consulta por clave
		if (isset ($_SESSION['id_capacitacion']))
			$id_capacitacion= $_SESSION['id_capacitacion']['id_capacitacion'];
		//Recoger el id de la capacitacion si viene de la consulta por fechas
		if (isset ($_SESSION['id_capacitaciones']))
			$id_capacitacion= $_SESSION['id_capacitaciones']; 

 		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");

		foreach($_POST as $key => $value){
			if(substr($key,0,4)=="ckb_"){

				/*Esta funcion se modifico porque se podia registrar a un mismo empleado varias veces a una misma capacitacion, lo que arrojaba un resultado de REGISTRO DUPLICADO
				Por lo que se opto mejor borrar cada uno de los registros que coincidieran con el mismo RFC del empleado y el ID de la Capacitacion para despues insertarlos nuevamente*/
				mysql_query("DELETE FROM empleados_reciben_capacitaciones  WHERE  empleados_rfc_empleado = '$value' AND capacitaciones_id_capacitacion='$id_capacitacion'");
		
				//Crear la Sentencias SQL para Alamcenar las asistencias de la capacitacion
				$stm_sql= "INSERT INTO empleados_reciben_capacitaciones  (empleados_rfc_empleado, capacitaciones_id_capacitacion)
				VALUES ('$value','$id_capacitacion')";
				//Ejecutar la Sentencia
				$rs=mysql_query($stm_sql);
				//Verificar Resultado, es favorable de lo contrario marcar error
				if ($rs)
					$band=1;								
				else{
					$band=0;
					$error = mysql_error();
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				}
			}	
	 	}// Fin foreach($_POST as $key => $value)
		if($band==1){
			//Guardar la operacion realizada
			registrarOperacion("bd_recursos",$id_capacitacion,"RegAsistCap",$_SESSION['usr_reg']);
			$conn = conecta("bd_recursos");			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";																																			
		}
		
 		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}// Fin de la function guardarAsistencias()
?>