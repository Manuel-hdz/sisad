<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Nómina de los empleados 
	**/
	
	//Función que permite mostrar el reporte de Nomina
	function reporteNomina(){		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
				
		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
				
		//Verificamos si viene definido el combo area; esto para ver cual sera la consulta a ejecutar
		if(isset($_POST["cmb_area"])){
			//Tomamos el area del post
			$area=$_POST["cmb_area"];
			//Crear la consulta
			$stm_sql = "SELECT empleados_rfc_empleado,  CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, dias_trabajados, nomina_interna.sueldo_diario,
						nomina_interna.sueldo_semana, puesto, tiempo_extra, domingo, dia_festivo, total, fecha_nomina_inicio, fecha_nomina_fin FROM (nomina_interna JOIN empleados 
						ON rfc_empleado=empleados_rfc_empleado) WHERE fecha_nomina_inicio>='$fechaIni' AND fecha_nomina_fin<='$fechaFin' AND area='$area' ORDER BY area, fecha_nomina_inicio";	
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de N&oacute;mina &Aacute;REA <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  del &Aacute;REA <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";			
		}
		else{
			//Crear la consulta 
			$stm_sql = "SELECT empleados_rfc_empleado,  CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, dias_trabajados, nomina_interna.sueldo_diario,
							nomina_interna.sueldo_semana, puesto, tiempo_extra, domingo, dia_festivo, total, fecha_nomina_inicio, fecha_nomina_fin FROM (nomina_interna JOIN empleados 
							ON rfc_empleado=empleados_rfc_empleado) WHERE fecha_nomina_inicio>='$fechaIni' AND fecha_nomina_fin<='$fechaFin' ORDER BY fecha_nomina_inicio";
						
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de N&oacute;mina De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";		
		}
			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table align='center'  class='tabla-frm' cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>						
						<td align='center' class='nombres_columnas'>INICIO REGISTRO N&Oacute;MINA</td>
						<td align='center' class='nombres_columnas'>FIN REGISTRO N&Oacute;MINA</td>
						<td align='center' class='nombres_columnas'>D&Iacute;AS TRABAJADOS</td>
						<td align='center' class='nombres_columnas'>SUELDO DIARIO</td>
						<td align='center' class='nombres_columnas'>SUELDO SEMANAL</td>
						<td align='center' class='nombres_columnas'>TIEMPO EXTRA</td>
						<td align='center' class='nombres_columnas'>DOMINGOS</td>
						<td align='center' class='nombres_columnas'>D&Iacute;A FESTIVO</td>
						<td align='center' class='nombres_columnas'>TOTAL</td>
						
					</tr>";
										
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Variable para guardar el total de nomina
			$total_nomina=0;
			do{	
				//Ejecutamos la consulta para obtener el numero la asistencia del empleado
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>						
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_nomina_inicio'],1)."</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_nomina_fin'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[dias_trabajados]</td>												
						<td align='center' class='$nom_clase'>$".number_format($datos['sueldo_diario'],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$".number_format($datos['sueldo_semana'],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$".number_format($datos['tiempo_extra'],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$".number_format($datos['domingo'],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$".number_format($datos['dia_festivo'],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$".number_format($datos['total'],2,".",",")."</td>
					</tr>";
				//Sumar el monto de la nomina para obtener el total
				$total_nomina += $datos['total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "
				<td colspan='12'>&nbsp;</td><td class='nombres_columnas'>$".number_format($total_nomina,2,".",",")."</td>	
			</table>";
		
		}//Cierre if($datos = mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>			
		</div>
		<div id="btns-regpdf" align="center">
		<table width="17%" cellpadding="12">
			<tr>
				<td width="30%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte N&oacute;mina" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reporteNomina.php'" />
			  </td>
			  	<?php 
					if($flag==1){
						//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
						<td width="70%" align="center">
							<form action="guardar_reporte.php" method="post">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
								<?php
								if(isset($_POST["cmb_area"])){?>
									<input name="hdn_nomReporte" type="hidden" 
									value="Reporte_Nomina_<?php echo $area;?>_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
								<?php
								}
								else{ ?>
									<input name="hdn_nomReporte" type="hidden" 
									value="Reporte_Nomina_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
								<?php 
								} ?>
								<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
								<input name="hdn_origen" type="hidden" value="reporteNomina" />	
								<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
								title="Exportar a Excel los Datos de la Consulta Realizada" 
								onMouseOver="window.estatus='';return true"  />
							</form>
			  </td>
				<?php 
					}?>
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
	
	//Funcion que muestra la Nómina Guardada lista para exportarse a Excel
	function mostrarNominaDesarrolloBD(){
		$band="false";
		//Realizar la conexion a la BD
		$conn = conecta($_POST["hdn_bd"]);
		
		if($_POST["hdn_bd"] != "bd_recursos"){
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN bd_recursos.empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		} else {
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		}
						
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$fechaI = $datos["fecha_inicio"];
			$fechaF = $datos["fecha_fin"];
			//Mensaje de encabezado de la Tabla
			$msje="<strong>N&oacute;mina de <u><em>$_POST[hdn_area]</u></em> del <u><em>".modFecha($datos["fecha_inicio"],1)."</em></u> al <u><em>".modFecha($datos["fecha_fin"],1)."</em></u></strong>";
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosNomina'> 
				<caption><p class='msje_correcto'>$msje</p></caption>
				<thead>
					<tr>
        				<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
				        <th class='nombres_columnas' align='center'>NOMBRE</th>
        				<td class='nombres_columnas' align='center'>J</td>
						<td class='nombres_columnas' align='center'>V</td>
						<td class='nombres_columnas' align='center'>S</td>
        				<td class='nombres_columnas' align='center'>D</td>
						<td class='nombres_columnas' align='center'>L</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>SUELDO BASE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>DESTAJO</td>
						<td class='nombres_columnas' align='center'>TOTAL PAGADO</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>GUARDIA 8HRS</td>
						<td class='nombres_columnas' align='center'>GUARDIA 12HRS</td>";
						if($_POST["hdn_bd"] == "bd_recursos")
							echo "<td class='nombres_columnas' align='center'>BONO</td>";
			echo "		<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				$destajo = $datos["destajo"] + ($datos["horas_extra"] * ($datos["sueldo_diario"] / 8));
				if($datos["guarda_12hrs"] == 1) {
					$g12 = "X";
					$destajo += 500;
				}	else $g12 = "";
				if($datos["guarda_8hrs"] == 1) {
					$g8 = "X";
					$destajo += 350;
				}	else $g8 = "";
				$destajo = $datos["destajo"];
				echo "	<tr>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>"; ?>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
			<?php 			
				echo "	<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_base"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_diario"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($destajo,2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["total_pagado"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[horas_extra]</td>
						<td class='$nom_clase' align='center'>$g8</td>
						<td class='$nom_clase' align='center'>$g12</td>";
						if($_POST["hdn_bd"] == "bd_recursos"){
							echo "<td class='$nom_clase' align='center'>$datos[bono]</td>";
						}
						
				echo "	<td class='$nom_clase' align='center'>$datos[comentarios]</td>
						</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));		
			echo "</tbody>";
			echo "</table>";
			$band="true";
		}
		else{
			//if ($area=="TODO")
				//Si no hay registros de Nómina de todos los departamentos con las Fechas ingresadas
				echo "<br><br><br><br><br><br><br><br><br><br><br>
					<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
			//else
				//Si no hay registros de Nómina del departamento elegido con las Fechas ingresadas
				//echo "<br><br><br><br><br><br><br><br><br><br><br>
					//<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina de <u><em>$area</u></em> del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql."<br>".$msje."<br>".$fechaI."<br>".$fechaF;
	}
	
	//Funcion que muestra la Nómina Guardada lista para exportarse a Excel
	function mostrarNominaZarpeoBD(){
		$band="false";
		//Realizar la conexion a la BD
		$conn = conecta($_POST["hdn_bd"]);
		
		if($_POST["hdn_bd"] != "bd_recursos"){
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN bd_recursos.empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		} else {
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		}
			
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$fechaI = $datos["fecha_inicio"];
			$fechaF = $datos["fecha_fin"];
			//Mensaje de encabezado de la Tabla
			$msje="<strong>N&oacute;mina de <u><em>$_POST[hdn_area]</u></em> del <u><em>".modFecha($datos["fecha_inicio"],1)."</em></u> al <u><em>".modFecha($datos["fecha_fin"],1)."</em></u></strong>";
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosNomina'> 
				<caption><p class='msje_correcto'>$msje</p></caption>
				<thead>
					<tr>
        				<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
				        <th class='nombres_columnas' align='center'>NOMBRE</th>
        				<td class='nombres_columnas' align='center'>J</td>
						<td class='nombres_columnas' align='center'>V</td>
						<td class='nombres_columnas' align='center'>S</td>
        				<td class='nombres_columnas' align='center'>D</td>
						<td class='nombres_columnas' align='center'>L</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>SUELDO BASE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>CUMPLIMIENTO</td>
						<td class='nombres_columnas' align='center'>CALIDAD OBRA</td>
						<td class='nombres_columnas' align='center'>BONIFICACION</td>
						<td class='nombres_columnas' align='center'>TOTAL PAGADO</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>GUARDIA 8HRS</td>
						<td class='nombres_columnas' align='center'>GUARDIA 12HRS</td>";
						if($_POST["hdn_bd"] == "bd_recursos")
							echo "<td class='nombres_columnas' align='center'>BONO</td>";
			echo "		<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				if($datos["guarda_12hrs"] == 1) {
					$g12 = "X";
				}	else $g12 = "";
				if($datos["guarda_8hrs"] == 1) {
					$g8 = "X";
				}	else $g8 = "";
				?> 	<tr>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["nombre_emp"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_base"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_diario"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["cumpl"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["calidad"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["bonif"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["total_pagado"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["horas_extra"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g8; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g12; ?></td>
						<?php if($_POST["hdn_bd"] == "bd_recursos"){ ?>
							<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["bono"]; ?></td>
						<?php } ?>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["comentarios"]; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));		
			echo "</tbody>";
			echo "</table>";
			$band="true";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><br><br><br>
				<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql."<br>".$msje."<br>".$fechaI."<br>".$fechaF;
	}
	
	//Funcion que muestra la Nómina Guardada lista para exportarse a Excel
	function mostrarNominaMamBD(){
		$band="false";
		//Realizar la conexion a la BD
		$conn = conecta($_POST["hdn_bd"]);
		
		if($_POST["hdn_bd"] != "bd_recursos"){
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN bd_recursos.empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		} else {
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		}
			
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$fechaI = $datos["fecha_inicio"];
			$fechaF = $datos["fecha_fin"];
			//Mensaje de encabezado de la Tabla
			$msje="<strong>N&oacute;mina de <u><em>$_POST[hdn_area]</u></em> del <u><em>".modFecha($datos["fecha_inicio"],1)."</em></u> al <u><em>".modFecha($datos["fecha_fin"],1)."</em></u></strong>";
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosNomina'> 
				<caption><p class='msje_correcto'>$msje</p></caption>
				<thead>
					<tr>
        				<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
				        <th class='nombres_columnas' align='center'>NOMBRE</th>
        				<td class='nombres_columnas' align='center'>J</td>
						<td class='nombres_columnas' align='center'>V</td>
						<td class='nombres_columnas' align='center'>S</td>
        				<td class='nombres_columnas' align='center'>D</td>
						<td class='nombres_columnas' align='center'>L</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>SUELDO BASE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TOTAL PAGADO</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>GUARDIA 8HRS</td>
						<td class='nombres_columnas' align='center'>GUARDIA 12HRS</td>";
						if($_POST["hdn_bd"] == "bd_recursos")
							echo "<td class='nombres_columnas' align='center'>BONO</td>";
			echo "		<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				if($datos["guarda_12hrs"] == 1) {
					$g12 = "X";
				}	else $g12 = "";
				if($datos["guarda_8hrs"] == 1) {
					$g8 = "X";
				}	else $g8 = "";
				?> 	<tr>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["nombre_emp"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_base"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_diario"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["total_pagado"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["horas_extra"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g8; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g12; ?></td>
						<?php if($_POST["hdn_bd"] == "bd_recursos"){ ?>
							<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["bono"]; ?></td>
						<?php } ?>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["comentarios"]; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));		
			echo "</tbody>";
			echo "</table>";
			$band="true";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><br><br><br>
				<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql."<br>".$msje."<br>".$fechaI."<br>".$fechaF;
	}
	
	//Funcion que muestra la Nómina Guardada lista para exportarse a Excel
	function mostrarNominaMacBD(){
		$band="false";
		//Realizar la conexion a la BD
		$conn = conecta($_POST["hdn_bd"]);
		
		if($_POST["hdn_bd"] != "bd_recursos"){
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN bd_recursos.empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		} else {
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		}
			
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$fechaI = $datos["fecha_inicio"];
			$fechaF = $datos["fecha_fin"];
			//Mensaje de encabezado de la Tabla
			$msje="<strong>N&oacute;mina de <u><em>$_POST[hdn_area]</u></em> del <u><em>".modFecha($datos["fecha_inicio"],1)."</em></u> al <u><em>".modFecha($datos["fecha_fin"],1)."</em></u></strong>";
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosNomina'> 
				<caption><p class='msje_correcto'>$msje</p></caption>
				<thead>
					<tr>
        				<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
				        <th class='nombres_columnas' align='center'>NOMBRE</th>
        				<td class='nombres_columnas' align='center'>J</td>
						<td class='nombres_columnas' align='center'>V</td>
						<td class='nombres_columnas' align='center'>S</td>
        				<td class='nombres_columnas' align='center'>D</td>
						<td class='nombres_columnas' align='center'>L</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>SUELDO BASE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TOTAL PAGADO</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>GUARDIA 8HRS</td>
						<td class='nombres_columnas' align='center'>GUARDIA 12HRS</td>";
						if($_POST["hdn_bd"] == "bd_recursos")
							echo "<td class='nombres_columnas' align='center'>BONO</td>";
			echo "		<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				if($datos["guarda_12hrs"] == 1) {
					$g12 = "X";
				}	else $g12 = "";
				if($datos["guarda_8hrs"] == 1) {
					$g8 = "X";
				}	else $g8 = "";
				?> 	<tr>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["nombre_emp"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_base"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_diario"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["total_pagado"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["horas_extra"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g8; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g12; ?></td>
						<?php if($_POST["hdn_bd"] == "bd_recursos"){ ?>
							<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["bono"]; ?></td>
						<?php } ?>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["comentarios"]; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));		
			echo "</tbody>";
			echo "</table>";
			$band="true";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><br><br><br>
				<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql."<br>".$msje."<br>".$fechaI."<br>".$fechaF;
	}
	
	//Funcion que muestra la Nómina Guardada lista para exportarse a Excel
	function mostrarNominaAdminBD(){
		$band="false";
		//Realizar la conexion a la BD
		$conn = conecta($_POST["hdn_bd"]);
		
		if($_POST["hdn_bd"] != "bd_recursos"){
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN bd_recursos.empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		} else {
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. *, T1.fecha_inicio, T1.fecha_fin 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.id_nomina =  '$_POST[cmb_nomina]'
						AND T1.area = '$_POST[hdn_area]'";
		}
			
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$fechaI = $datos["fecha_inicio"];
			$fechaF = $datos["fecha_fin"];
			//Mensaje de encabezado de la Tabla
			$msje="<strong>N&oacute;mina de <u><em>$_POST[hdn_area]</u></em> del <u><em>".modFecha($datos["fecha_inicio"],1)."</em></u> al <u><em>".modFecha($datos["fecha_fin"],1)."</em></u></strong>";
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosNomina'> 
				<caption><p class='msje_correcto'>$msje</p></caption>
				<thead>
					<tr>
        				<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
				        <th class='nombres_columnas' align='center'>NOMBRE</th>
        				<td class='nombres_columnas' align='center'>J</td>
						<td class='nombres_columnas' align='center'>V</td>
						<td class='nombres_columnas' align='center'>S</td>
        				<td class='nombres_columnas' align='center'>D</td>
						<td class='nombres_columnas' align='center'>L</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>SUELDO BASE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TOTAL PAGADO</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>GUARDIA 8HRS</td>
						<td class='nombres_columnas' align='center'>GUARDIA 12HRS</td>";
						if($_POST["hdn_bd"] == "bd_recursos")
							echo "<td class='nombres_columnas' align='center'>BONO</td>";
			echo "		<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				if($datos["guarda_12hrs"] == 1) {
					$g12 = "X";
				}	else $g12 = "";
				if($datos["guarda_8hrs"] == 1) {
					$g8 = "X";
				}	else $g8 = "";
				?> 	<tr>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["nombre_emp"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_base"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_diario"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["total_pagado"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["horas_extra"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g8; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g12; ?></td>
						<?php if($_POST["hdn_bd"] == "bd_recursos"){ ?>
							<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["bono"]; ?></td>
						<?php } ?>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["comentarios"]; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));		
			echo "</tbody>";
			echo "</table>";
			$band="true";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><br><br><br>
				<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql."<br>".$msje."<br>".$fechaI."<br>".$fechaF;
	}
?>