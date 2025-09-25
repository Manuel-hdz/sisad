<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 05/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Eliminar Capacitacion en la BD
	**/
	
	//Funcion que se encarga de desplegar las capacitaciones en el rango de fechas
	function mostrarCapacitaciones(){

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Si viene sbt_consultar la buqueda de las capacitaciones proviene de un rango de fechas
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM capacitaciones	WHERE fecha_inicio>='$f1' AND fecha_inicio<='$f2' ORDER BY id_capacitacion";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Capacitaciones en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>	$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Capacitaci&oacute;n en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		
		//Si viene sbt_consultar2 la buqueda de la capacitacion proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM capacitaciones WHERE id_capacitacion = '$_POST[cmb_claveCapacitacion]'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Capacitaci&oacute;n  <em><u> $_POST[cmb_claveCapacitacion]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Capacitaci&oacute;n </label>";										
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>ELIMINAR</td>
					<td class='nombres_columnas' align='center'>ID CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NOMBRE CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>DURACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>INSTRUCTOR</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb' value= $datos[id_capacitacion] />
						</td>
						<td class='$nom_clase'>$datos[id_capacitacion]</td>
						<td class='$nom_clase'>$datos[nom_capacitacion]</td>
						<td class='$nom_clase'>$datos[hrs_capacitacion] HORAS</td>
						<td class='$nom_clase'>$datos[instructor]</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	//Funcion que se encarga de eliminar la capacitacion seleccionada
	function eliminarCapacitacionSeleccionada(){
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Creamos la sentencia SQL para borrar de capacitacines
		$stm_sql="DELETE FROM capacitaciones  WHERE id_capacitacion = '$_POST[rdb]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_recursos",$_POST['rdb'],"EliminarCapacitacion",$_SESSION['usr_reg']);																			
			//Funcion que elimina a los empleados que recibieron alguna capacitacion de la Base de Datos de Recursos Humanos
			eliminar_empleados_reciben_capacitaciones();
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion eliminarCapacitacionSeleccionada
	
	//Funcion que se encarga de eliminar los empleados que recibieron la capacitacion seleccionada
	function eliminar_empleados_reciben_capacitaciones(){
		//Sentencia SQL
		$stm_sql="DELETE FROM empleados_reciben_capacitaciones  WHERE capacitaciones_id_capacitacion = '$_POST[rdb]'";
		//Conectar a la BD de Recursos Humanos
		$conn=conecta("bd_recursos");
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Cerrar la conexion con la BD de Recursos Humanos
		mysql_close($conn);
	}// Fin function eliminar_empleados_reciben_capacitaciones
?>