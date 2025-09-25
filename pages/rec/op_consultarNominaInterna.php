<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 08/Abril/2011
	  * Descripción: Este archivo permite consultar los empleados registrados en la nómina interna
	**/
	
	
	//Función que nos permite consultar los empleados en la nomina interna	
	function mostrarNomina(){
		//Abrimos la conexion con la Base de datos
		$conn = conecta("bd_recursos");
				
		//Recuperar Datos del POST
		$idNomina = $_POST['cmb_periodo'];
		$area = $_POST['cmb_area'];
		
		//Obtener los datos generales de la nomina
		$datosNomina = mysql_fetch_array(mysql_query("SELECT *,DATEDIFF(fecha_fin,fecha_inicio) AS cant_dias FROM nomina_interna WHERE id_nomina = '$idNomina'"));
		$fechaIni = $datosNomina['fecha_inicio'];
		$fechaFin = $datosNomina['fecha_fin'];
		$cantDias = $datosNomina['cant_dias'] + 1;
		
		$periodo = "SEMANA";//Variable utilizada en los mensajes de la caja de texto de Sueldo Semanal o Quincenal
		$msgSemQuin = "SEMANA DEL ".modFecha($fechaIni,1)." AL ".modFecha($fechaFin,1);
		if($cantDias==15 || $cantDias==16){
			$msgSemQuin = "QUINCENA DEL ".modFecha($fechaIni,1)." AL ".modFecha($fechaFin,1);
			$periodo = "QUINCENA";
		}
												
		//Crear la Sentencia SQL para extraer los trbajadores registrados en la Nomina Seleccionada
		$sql_empleados = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, puesto, jornada, det_nom_interna.* FROM det_nom_interna JOIN empleados 
							ON empleados_rfc_empleado=rfc_empleado WHERE nomina_interna_id_nomina = '$idNomina' ORDER BY nombre";
	
		//Ejecutamos la sentencia SQL
		$rs_empleado = mysql_query($sql_empleados);
		
		//Esta variable ayudará a activar el Boton de Exportar Nomina cuando existan resultados
		$resConsultaNomina = 0;
		
		//Si la consulta arrojo datos se crea la tabla para mostrar los resultados
		if($datosEmpleados=mysql_fetch_array($rs_empleado)){
			
			$resConsultaNomina = 1;?>
			
			<table class="tabla_frm" cellpadding="5" width="150%">
				<caption class="titulo_etiqueta">
					EMPLEADOS DEL &Aacute;REA: <u><em><?php echo $area; ?></em></u>
					<br />
					<?php echo $msgSemQuin; ?>
				</caption>
				<tr>							
					<td rowspan="2" align="center" class="nombres_columnas">NO.</td>
					<td rowspan="2" align="center" class="nombres_columnas">NOMBRE</td>
					<td rowspan="2" align="center" class="nombres_columnas">PUESTO</td>
					<td rowspan="2"align="center" class="nombres_columnas">SUELDO DIARIO</td>
					<td colspan="<?php echo $cantDias; ?>" align="center" class="nombres_columnas">KARDEX</td>
					<td rowspan="2" align="center" class="nombres_columnas">SUELDO <?php echo $periodo; ?></td>
					<td rowspan="2" align="center" class="nombres_columnas">T.E.</td>
					<td rowspan="2" align="center" class="nombres_columnas">D.T.</td>
					<td rowspan="2" align="center" class="nombres_columnas">BONIFICACION</td>
					<td rowspan="2" align="center" class="nombres_columnas">TOTAL</td>
				</tr>
				<tr><?php
					//Colocar la letra inicial del dia de la semana que corresponde a la fecha indicada
					$fechaActual = $fechaIni;
					for($i=0;$i<$cantDias;$i++){ 
						//Obtener el nombre del día de la fecha pasada como parámetro en formato aaaa-mm-dd
						$nomDia = obtenerNombreDia($fechaActual);
						//Obtener la letra inicial del dia obtenido
						$letraDia = substr($nomDia,0,1);?>
						
						<td align="center" class="nombres_columnas"><?php echo $letraDia; ?></td><?php
						
						//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
						$seccFecha = split("-",$fechaActual);
						//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
						$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + 1;
						//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
						$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
					}?>														
				</tr><?php
				
			//Variables para dar formato a cada renglon de la tabla que será dibujada
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			
			//DESPLEGAR EL REGISTRO DE CADA EMPLEADO
			do{?>
				<tr>		
					<td align="center" class="<?php echo $nom_clase; ?>" rowspan="2"><?php echo $cont; ?></td>
					<td align="left" class="<?php echo $nom_clase; ?>"><?php 
						echo $datosEmpleados['nombre']; ?>						
					</td>
					<td align="left" class="<?php echo $nom_clase; ?>" rowspan="2"><?php echo $datosEmpleados['puesto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$<?php 
						echo number_format($datosEmpleados['sueldo_diario'],2,".",","); ?>
					</td><?php				
				//Calcular el costo del tiempo extra de cada trabajador
				$precioTE = ($datosEmpleados['sueldo_diario']/$datosEmpleados['jornada']) * 2;
				//Esta variable guardara el segundo renglon del registro de cada empleado, el cual será desplegado una vez que haya sido cerrado (</tr>) el primero
				$segRenglon = "<tr><td class='$nom_clase'>Tiempo Extra</td><td align='center' class='$nom_clase'>$".number_format($precioTE,2,".",",")."</td>";
				
				
				//Obtener los datos del Kardex de cada empleado que será listado en el arreglo '$datosKardex' el cual contiene los siguientes
				//Indices por fecha: 'incidencia', 'horasTrabajadas', 'horasExtra' y fuera de las fechas 'diasTrabajados'
				$datosKardex = obtenerKardexEmpleado($datosEmpleados['empleados_rfc_empleado'],$fechaIni,$fechaFin,$cantDias,$datosEmpleados['jornada']);							
								
				//Guardar la fecha de inicio como fecha actual.
				$fechaActual = $fechaIni;
				//Colocar los datos del kardex(Incidencia, Horas Trabajas y Horas Extra) de cada empleado
				for($i=0;$i<$cantDias;$i++){?>
					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php 
						echo $datosKardex[$fechaActual]['incidencia']; ?>
					</td><?php			
					
					//Agregar las Horas Extra al segundo renglon							
					$segRenglon .= "<td class='$nom_clase' align='center'>".$datosKardex[$fechaActual]['horasExtra']."</td>";
					
					
					//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
					$seccFecha = split("-",$fechaActual);
					//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
					$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) +1;
					//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
					$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
				}//Ciere for($i=0;$i<$cantDias;$i++)?>
								
					<td align="center" class="<?php echo $nom_clase; ?>" title="Sueldo de la <?php echo $periodo; ?>" rowspan="2"><?php 
						echo "$".number_format($datosEmpleados['sueldo_periodo'],2,".",",");?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Tiempo Extra de la <?php echo $periodo; ?>" rowspan="2"><?php 
						echo "$".number_format($datosEmpleados['tiempo_extra'],2,".",","); ?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Descanso Trabajado" rowspan="2"><?php 
						echo "$".number_format($datosEmpleados['descanso_trabajado'],2,".",","); ?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Bonificaci&oacute;n de la <?php echo $periodo; ?>"rowspan="2"><?php 
						echo "$".number_format($datosEmpleados['bonificacion'],2,".",","); ?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Sueldo Total de la <?php echo $periodo; ?>" rowspan="2"><?php 
						echo "$".number_format($datosEmpleados['sueldo_total'],2,".",","); ?>
					</td>
				</tr><?php
				
				//Imprimir el contenido del segundo Renglon
				echo $segRenglon."</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datosEmpleados=mysql_fetch_array($rs_empleado));?>
			</table><?php
		}//Cierre if($datosEmpleados=mysql_fetch_array($rs_empleado))
		else	
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Datos Registrados";
		
		//Cerramos la conexion con la Base de Datos		
		mysql_close($conn);
		
		
		return $resConsultaNomina;
		
	}//Fin de la funcion mostrarNomina()
	
	
	/*Esta función desplegará el resumen de los prestamos relacionados a los empleados dentro del periodo de la nomina seleccionada*/
	function verInfoPrestamos(){
		
	}//Cierre de la funcion verInfoPrestamos()
	
		
?>
