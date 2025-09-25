<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 06/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Consultar Capacitacion en la BD de RH
	**/
	

	//Funcion que se encarga de desplegar las capacitaciones en el rango de fechas
	function mostrarCapacitaciones(){

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		// Si esta definido consultaFecha la busqueda proviene de un rango de fechas
		if (isset ($_SESSION['consultaFecha'])){
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM capacitaciones	WHERE fecha_inicio>='$f1' AND fecha_fin<='$f2' ORDER BY id_capacitacion";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Capacitaciones en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Capacitaci&oacute;n en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		
		// Si esta definido consultaClave la busqueda proviene del combo box donde se muestran las capacitaciones registradas en la bd
		else if (isset ($_SESSION['consultaClave'])){
		
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM capacitaciones WHERE id_capacitacion = '$_POST[cmb_claveCapacitacion]'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Capacitaci&oacute;n  <em><u> $_POST[cmb_claveCapacitacion]</u></em>";
			
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
				<caption align='center' class='titulo_etiqueta'>$msg</caption>
				<tr>
					<td class='nombres_columnas' align='center'>VER DETALLE</td>
					<td class='nombres_columnas' align='center'>ID CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NOMBRE CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ASISTENTES</td>
					<td class='nombres_columnas' align='center'>DURACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>INSTRUCTOR</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>				
				<form name='frm_detalleCapacitacion' method='post' action='frm_consultarCapacitacion.php'>
				<input type='hidden' name='verDetalle' value='si' />";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Preparar la sentencia para obtener el numero de personas que asistieron a cada capacitacion
				$sql_stm2 ="SELECT COUNT(capacitaciones_id_capacitacion) AS cant FROM empleados_reciben_capacitaciones 
				WHERE capacitaciones_id_capacitacion='$datos[id_capacitacion]'";	
				
				//Ejecutar la sentencia previamente creada
				$tot_asist = mysql_fetch_array(mysql_query($sql_stm2));
				
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' width='6%' align='center'><input type='checkbox' name='ckb' value='$datos[id_capacitacion]' 
							onClick='javascript:document.frm_detalleCapacitacion.submit();'/></td>
						<td class='renglon_gris' width='11%'>$datos[id_capacitacion]</td>
						<td class='renglon_gris' width='17%'>$datos[nom_capacitacion]</td>
						<td class='renglon_gris' width='6%'align='center'>$tot_asist[cant]</td>
						<td class='renglon_gris' width='10%'>$datos[hrs_capacitacion] HORAS</td>
						<td class='renglon_gris' width='20%'>$datos[instructor]</td>
						<td class='renglon_gris'>$datos[descripcion]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</form>	
			</table>";
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	}
	
	//Funcion que permite mostrar el detalle de la capacitacion seleccionada en un checkbox
	function mostrarDetalleCap($ckb){
		
		//Realizar la conexion a la BD de Recuros Humanos
		$conn = conecta("bd_recursos");
		
		//Realizar la consulta para obtener los empleados que recibieron la capacitacion seleccionada
		$stm_sql = "SELECT DISTINCT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre  ,area, puesto FROM (empleados JOIN empleados_reciben_capacitaciones 
		ON rfc_empleado = empleados_rfc_empleado 
		AND capacitaciones_id_capacitacion= '$ckb' )  ORDER BY rfc_empleado ";
		
		//Ejecutar la consulta y dibujar la tabla para mostrar el detalle
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<label class='titulo_etiqueta'>PERSONAL QUE RECIBIO LA CAPACITACI&Oacute;N <em><u>$ckb</u></em></label>								
			<br><br>								
			<table cellpadding='5'>
				<tr>
					<td class='nombres_columnas' align='center'>RFC EMPLEADO</td>
					<td class='nombres_columnas' align='center'>NOMBRE</td>						
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>PUESTO</td>
					<td class='nombres_columnas' align='center'>CONSTANCIA</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$datos[rfc_empleado]</td>	
						<td class='$nom_clase'>$datos[nombre]</td>	
						<td class='$nom_clase'>$datos[area]</td>	
						<td class='$nom_clase'>$datos[puesto]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_constancia" class="botones" value="Constancia" onMouseOver="window.estatus='';return true" 
                            title="Ver Constancia del Empleado <?php echo $datos['rfc_empleado'];?>" onclick="window.open('../../includes/generadorPDF/capacitacion.php?id=<?php echo $datos['rfc_empleado']; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>							
						</td>	
					</tr><?php
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table><br><br><br>";
		}else{
			echo "<br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No Hay Personal que haya Tomado esta Capacitaci&oacute;n</p>";
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>