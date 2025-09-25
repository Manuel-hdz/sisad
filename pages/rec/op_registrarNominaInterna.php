<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 27/Marzo/2012
	  * Descripción: Este Archivo contine las funciones para realizar los calculos de la Nomina Interna.
	**/
	
	
	
	//Esta función muestra los empleados de acuerdo a los Parámetros de Búsqueda Seleccionados
	function mostrarEmpleados(){	
		
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
				
		//Recuperar los datos de Área, Fechas y Nombre del Empleado del POST
		$area = $_POST['cmb_area'];
		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$trabajador = $_POST['txt_nombre'];
		$cantDias = $_POST['hdn_cantDias'];
		
		
		//Determinar si el periodo seleccionado es Semanal o Quincenal
		$periodo = "SEMANA";//Variable utilizada en los mensajes de la caja de texto de Sueldo Semanal o Quincenal
		$msgSemQuin = "SEMANA DEL ".$_POST['txt_fechaIni']." AL ".$_POST['txt_fechaFin'];
		if($cantDias==15 || $cantDias==16){
			$msgSemQuin = "QUINCENA DEL ".$_POST['txt_fechaIni']." AL ".$_POST['txt_fechaFin'];
			$periodo = "QUINCENA";
		}
		
		
		/*
		 ***NOTA: Antes de realizar los calculos de la Nomina Verificar si existe un registro en la BD de datos en el Periodo y Área seleccionados
		 * 1. Sí no existe registro previo, realizamos los calculos para obtener el sueldo del periodo(Semanal ó Quincenal), Tiempo Extra, Descanso Trabajado,
		 *    Bonificación y Sueldo Total.
		 * 2. Sí existe una registro de Nomina en el Periodo y Área seleccionados, realizar el calculo del sueldo del periodo(Semanal ó Quincenal) y del Tiempo Extra
		 *    previendo que existan cambios en el KARDEX y extraer de la Base de datos los campos de Descanso Trabajado y Bonificación y por ultimo recalcular el 		
		 *    Sueldo Total */
		$noiPrevia = 0;		
		$rs_noiPrevia = mysql_query("SELECT * FROM nomina_interna WHERE periodo = '$periodo' AND fecha_inicio = '$fechaInicio' AND fecha_fin = '$fechaFin' AND area = '$area'");
		if($datos_noiPrevia=mysql_fetch_array($rs_noiPrevia)){
			$noiPrevia = 1;
			//Esta variable indicara que hay que actualizar los datos en lugar de guardarlos como nuevos ?>
			<input type="hidden" name="hdn_idNominaInterna" id="hdn_idNominaInterna" value="<?php echo $datos_noiPrevia['id_nomina']; ?>" /><?php
		}//Cierre if($datos_noiPrevia=mysql_fetch_array($rs_noiPrevia))
		
		
		//Creamos la sentencia SQL para obtener los nombres de los empleados
		$sql_stm = "";
		if($trabajador==""){
			$sql_stm = "SELECT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) as nombre, puesto, sueldo_diario, jornada FROM empleados WHERE area = '$area' ORDER BY nombre";
		}
		else if($trabajador!=""){
			$sql_stm = "SELECT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) as nombre, puesto, sueldo_diario, jornada FROM empleados 
						WHERE area = '$area' AND '$trabajador' = CONCAT(nombre,' ',ape_pat,' ',ape_mat)";
		}			
		
					
		//Ejecutamos la sentencia
		$rs_datosEmp = mysql_query($sql_stm);
		
		//Obtener la cantidad de registros a ser desplegados
		$numRegs = mysql_num_rows($rs_datosEmp);						
		
		//Verificamos que la consulta regresa datos
		if($datosEmpleados=mysql_fetch_array($rs_datosEmp)){
			//Guardar en una variable oculta la cantidad empleados a los que se les va a registrar la Nomina y el Periodo?>
			<input type="hidden" name="hdn_cantRegistros" id="hdn_cantRegistros" value="<?php echo $numRegs; ?>" />
			<input type="hidden" name="hdn_periodo" id="hdn_periodo" value="<?php echo $periodo; ?>" />
			
			
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
					$fechaActual = $fechaInicio;					
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
						<input type="hidden" name="hdn_rfcEmpleado<?php echo $cont; ?>" id="hdn_rfcEmpleado<?php echo $cont; ?>" 
						value="<?php echo $datosEmpleados['rfc_empleado']; ?>" />
					</td>
					<td align="left" class="<?php echo $nom_clase; ?>" rowspan="2"><?php echo $datosEmpleados['puesto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$<?php 
						echo number_format($datosEmpleados['sueldo_diario'],2,".",","); ?>
						<input type="hidden" name="hdn_sueldoDiario<?php echo $cont; ?>" id="hdn_sueldoDiario<?php echo $cont; ?>" 
						value="<?php echo $datosEmpleados['sueldo_diario']?>" />
					</td><?php
					
				//Calcular el costo del tiempo extra de cada trabajador
				$precioHoraExtra = ($datosEmpleados['sueldo_diario']/$datosEmpleados['jornada']) * 2;
				//Esta variable guardara el segundo renglon del registro de cada empleado, el cual será desplegado una vez que haya sido cerrado (</tr>) el primero
				$segRenglon = "<tr><td class='$nom_clase'>Tiempo Extra</td><td align='center' class='$nom_clase'>$".number_format($precioHoraExtra,2,".",",")."</td>";									
				
					
				//Obtener los datos del Kardex de cada empleado que será listado en el arreglo '$datosKardex' el cual contiene los siguientes
				//Indices por fecha: 'incidencia', 'horasTrabajadas', 'horasExtra' y fuera de las fechas 'diasTrabajados'
				$datosKardex = obtenerKardexEmpleado($datosEmpleados['rfc_empleado'],$fechaInicio,$fechaFin,$cantDias,$datosEmpleados['jornada']);							
				
				//variable parea sumar las horas extra por día por cada trabajador
				$hrsExtra = 0;
				
				//Guardar la fecha de inicio como fecha actual.
				$fechaActual = $fechaInicio;
				//Colocar los datos del kardex(Incidencia, Horas Trabajas y Horas Extra) de cada empleado
				for($i=0;$i<$cantDias;$i++){?>
					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php 
						echo $datosKardex[$fechaActual]['incidencia']; ?>
					</td><?php
					
					//Agregar las Horas Extra al segundo renglon							
					$segRenglon .= "<td class='$nom_clase' align='center'>".$datosKardex[$fechaActual]['horasTrabajadas']."-".$datosKardex[$fechaActual]['horasExtra']."</td>";
					
					//Acumular las horas extra
					$hrsExtra += $datosKardex[$fechaActual]['horasExtra'];
					
					//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
					$seccFecha = split("-",$fechaActual);
					//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
					$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) +1;
					//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
					$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
				}//Ciere for($i=0;$i<$cantDias;$i++)								
				
				//Obtener el Sueldo en base a los dias trabajados multiplicados por el sueldo diario de cada trabajador
				$sueldo = $datosKardex['diasTrabajados'] * $datosEmpleados['sueldo_diario']; 												
				
				//Obtener el valor del tiempo extra (Horas pagadas al doble)				
				$tiempoExtra = $hrsExtra * $precioHoraExtra;
				
				//Calcular el Total de Sueldo hasta el Momento en que se carga la pagina para realizar los ajustes correspondientes a la Nómina 
				$sueldoTotal = $sueldo + $tiempoExtra;
				
				//Verificar si existe un registro previo para extraer los valores de Descanso Trabajado y Bonificación
				$dt = "$0.00"; $bonificacion = "$0.00"; $bonos = "";
				if($noiPrevia==1){
					//Extraer los datos del Registro de Nomina
					$rs_percepciones = mysql_query("SELECT descanso_trabajado, bonificacion, bonos FROM det_nom_interna 
					WHERE empleados_rfc_empleado = '$datosEmpleados[rfc_empleado]' AND nomina_interna_id_nomina = '$datos_noiPrevia[id_nomina]'");
					$datos_percepciones = mysql_fetch_array($rs_percepciones);
					
					//Guardar Datos en variables
					$dt = doubleval($datos_percepciones['descanso_trabajado']);
					$bonificacion = doubleval($datos_percepciones['bonificacion']);
					$bonos = $datos_percepciones['bonos'];
					
					//Sumar el Descanso Trabajado y la Bonificación al Sueldo Total
					$sueldoTotal += ($dt + $bonificacion);
					
				}//Cierre if($noiPrevia==1) ?>
				
				
					<td align="center" class="<?php echo $nom_clase; ?>" title="Sueldo de la <?php echo $periodo; ?>" rowspan="2">
						<input type="text" name="txt_sueldo<?php echo $cont; ?>" id="txt_sueldo<?php echo $cont; ?>" class="caja_de_texto" readonly="readonly" size="10" 
						maxlength="15" value="$<?php echo number_format($sueldo,2,".",","); ?>" />
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Tiempo Extra de la <?php echo $periodo; ?>" rowspan="2">
						<input type="text" name="txt_tiempoExtra<?php echo $cont; ?>" id="txt_tiempoExtra<?php echo $cont; ?>" class="caja_de_texto" size="10" maxlength="15"
						onkeypress="return permite(event,'num',2);" value="$<?php echo number_format($tiempoExtra,2,".",","); ?>" 
						onchange="formatCurrencySing(this.value,'txt_tiempoExtra<?php echo $cont; ?>'); calcularSueldoTotal(<?php echo $cont; ?>);" />
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Descanso Trabajado" rowspan="2">
						<input type="text" name="txt_descansoTrabajado<?php echo $cont; ?>" id="txt_descansoTrabajado<?php echo $cont; ?>" class="caja_de_texto" 
						onkeypress="return permite(event,'num',2);" size="10" maxlength="15" value="$<?php echo number_format($dt,2,".",","); ?>" 
						onchange="formatCurrencySing(this.value,'txt_descansoTrabajado<?php echo $cont; ?>'); calcularSueldoTotal(<?php echo $cont; ?>);" />
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Bonificaci&oacute;n de la <?php echo $periodo; ?>" rowspan="2">
						<input type="text" name="txt_bonificacion<?php echo $cont; ?>" id="txt_bonificacion<?php echo $cont; ?>" class="caja_de_texto" readonly="readonly" 
						size="10" maxlength="15" value="<?php echo number_format($bonificacion,2,".",","); ?>"
						onclick="abriVtnBonos('<?php echo $datosEmpleados['nombre']; ?>',<?php echo $cont; ?>,txt_sueldoTotal<?php echo $cont; ?>.value);" />
						<input type="hidden" name="hdn_idBonos<?php echo $cont; ?>" id="hdn_idBonos<?php echo $cont; ?>" value="<?php echo $bonos; ?>" />
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Sueldo Total de la <?php echo $periodo; ?>" rowspan="2">
						<input type="text" name="txt_sueldoTotal<?php echo $cont; ?>" id="txt_sueldoTotal<?php echo $cont; ?>" class="caja_de_texto" readonly="readonly" 
						size="12" maxlength="15" value="$<?php echo number_format($sueldoTotal,2,".",","); ?>" />	
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
			}while($datosEmpleados=mysql_fetch_array($rs_datosEmp));?>			
			
			</table><?php			
		}//Cierre if($datosEmpleaados=mysql_fetch_array($rs))		
		else{//Si no se encuentra ningun resultado desplegar un mensaje							
			echo "<label class='msje_correcto'>NO EXISTEN EMPLEADOS REGISTRADOS EN EL &Aacute;REA: <u><em>$area</em></u></label>";
		}				
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		
	}//Cierre de la funcion mostrarEmpleados()
	
	
	/*Esta función recupera los datos del Kardex del empleado indicado*/
	function obtenerKardexEmpleado($rfc_empleado,$fechaInicio,$fechaFin,$cantDias,$jornada){
		
		/*Este arreglo guardará los datos obtenidos del Kardex, colocando la fecha correspondiente a cada registro como clave y esta a su vez contendra por cada fecha
		  la Incidencia, las Horas Trabajados y las Horas Extra y como espacio fuera de fechas tendrá los días trabajados*/
		$kardex = array();
		
				
		//Definir Fecha de Inicio, como Fecha Actual
		$fechaActual = $fechaInicio;		
		//Rellenar el Arreglo con registros vacios, los cuales serán complementados con la consulta realizada a la BD
		for($i=0;$i<$cantDias;$i++){
			//Colocar en cada fecha un arreglo que contenga la incidencia del dia, las horas trabajadas y las horas extra que pueda tener en la fecha
			$kardex[$fechaActual] = array("incidencia"=>"","horasTrabajadas"=>"","horasExtra"=>"");
			
			//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
			$seccFecha = split("-",$fechaActual);
			//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
			$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + 1;
			//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
			$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
		}//Cierre for($i=0;$i<$cantDias;$i++)
		
		//Registrar Dias Trabajados
		$kardex['diasTrabajados'] = 0;
				
								
		//Variables para obtener la cantidad de Dias Trabajados
		$diasTrabajados = 0;
		$factorSeptimoDia = 1.1669;
														
		//Sentencia SQL para Obtener los datos del KARDEX
		$sql_stm = "SELECT * FROM checadas WHERE empleados_rfc_empleado = '$rfc_empleado' AND fecha_checada BETWEEN '$fechaInicio' AND '$fechaFin' 
					ORDER BY fecha_checada,hora_checada";
					
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
								
		//Verificar si hay datos registrados
		if($datos=mysql_fetch_array($rs)){
			//Procesar cada registro encontrada para el trabajador proporcionado en las fechas indicadas
			do{
				
				//Guardamos la fecha de la checada que esta siendo procesada como fecha actual
				$fechaActual = $datos['fecha_checada'];
			
			
				//Tomar en cuenta los registros que sean diferentes de SALIDA para obtener la Incidencia(Estado) del registro en la BD
				if($datos['estado']!="SALIDA"){
					$kardex[$fechaActual]["incidencia"] = $datos['estado'];
					//Contar las siguientes Incidencias como Asistencia, A=>Asistencia, V=>Vacaciones, r=>Retardo, F/j=>Falta justificada, P/G=>Permiso con Goce,
					//d=>Descanso ==> Esta incidencia no se debe contar como día trabajado
					if($datos['estado']=="A" || $datos['estado']=="V" || $datos['estado']=="r" || $datos['estado']=="F/j" || $datos['estado']=="P/G"){
						$diasTrabajados++;
					}
				}//Cierre if($datos['estado']!="SALIDA")
				
				
				
				//Calcular la cantidad de horas trabajadas por Jornada Laboral, así como las horas extra
				if($datos['estado']=="SALIDA"){
					//Obtener la checada de entrada de la misma fecha de la salida
					$rs_checadaEntrada = mysql_query("SELECT * FROM checadas WHERE empleados_rfc_empleado = '$rfc_empleado' 
													AND fecha_checada = '$fechaActual' AND estado != 'SALIDA' AND hora_checada < '$datos[hora_checada]'");
					
					//Sí hay datos una checada de entrada registrada en la misma fecha de la SALIDA procedemos a obtener la cantidad de horas trabajadas
					if($d_checEnt=mysql_fetch_array($rs_checadaEntrada)){
						
						/* Verificar que la incidencia encontrada pertenesca al grupo de las percepciones
						 * Asistencia, A=>Asistencia, V=>Vacaciones, r=>Retardo, F/j=>Falta justificada, P/G=>Permiso con Goce */
						if($d_checEnt['estado']=="A" || $d_checEnt['estado']=="V" || $d_checEnt['estado']=="r" || $d_checEnt['estado']=="F/j" || $d_checEnt['estado']=="P/G"){
							//Obtener la diferencia entre las Hora de SALIDA y la hora de ENTRADA con MySQL
							$datos_diff = mysql_fetch_array(mysql_query("SELECT TIMEDIFF(SUBSTRING('$datos[hora_checada]',1,5),SUBSTRING('$d_checEnt[hora_checada]',1,5)) AS diferencia"));
							$horasTrabajadas = intval(substr($datos_diff['diferencia'],0,2));
							
							//Guardar las Horas Trabjadas y las Horas Extra
							$kardex[$fechaActual]["horasTrabajadas"] = $horasTrabajadas;
							$hrsExtra = $horasTrabajadas - $jornada;
							if($hrsExtra<0) $hrsExtra = 0;
							$kardex[$fechaActual]["horasExtra"] = $hrsExtra;
						}
						else{
							/* Para las incidencias que pertenecen al grupo de las Deducciones, dejar el registro vacio
							 * F=>Falta, P=Permiso Sin Goce, E=>Incpacidad Enfermedad General, RT=>Incapacidad Accidente Trabajo, T=>Incapacidad en Trayecto, 
							 * D=>Sancion Disciplinaria, R=>Regresaron */
						}
											
					}//Cierre if($datos_checadaEntrada=mysql_fetch_array($rs_checadaEntrada))
					else{//Sí no hay checada de entrada registrada en el mismo día, procedemos a buscar en un día anterior
					
						//Almacenar la Fecha Actual como Fecha Anterior para restarle un día y buscar la entrada un día antes de la fecha de salida
						$fechaAnterior = $fechaActual;
					
						//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
						$seccFecha = split("-",$fechaAnterior);
						//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y restar 1 día
						$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) - 1;
						//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
						$fechaAnterior = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
						
						//Sentencia SQL para obtener la checada de entrada de un día anterior
						$rs_checadaEntrada = mysql_query("SELECT * FROM checadas WHERE empleados_rfc_empleado = '$rfc_empleado' 
						AND fecha_checada = '$fechaAnterior' AND estado != 'SALIDA'");
						
						//Verificar si la ENTRADA se encuentra en una fecha anterior a la de la SALIDA
						if($d_checEnt=mysql_fetch_array($rs_checadaEntrada)){
						
							/* Verificar que la incidencia encontrada pertenesca al grupo de las percepciones
							 * Asistencia, A=>Asistencia, V=>Vacaciones, r=>Retardo, F/j=>Falta justificada, P/G=>Permiso con Goce */
							if($d_checEnt['estado']=="A" || $d_checEnt['estado']=="V" || $d_checEnt['estado']=="r" || $d_checEnt['estado']=="F/j" || $d_checEnt['estado']=="P/G"){
								$horaChecada=substr($datos["hora_checada"],0,5);
								$horaEntrada=substr($d_checEnt["hora_checada"],0,5);
								//Obtener la diferencia entre las Hora de SALIDA y la hora de ENTRADA con MySQL, colocar tambien la fecha ya que son días ddiferentes
								$sql_stm_diff = "SELECT TIMEDIFF('$fechaActual $horaChecada','$fechaAnterior $horaEntrada') AS diferencia";
								$datos_diff = mysql_fetch_array(mysql_query($sql_stm_diff));
								$horasTrabajadas = intval(substr($datos_diff['diferencia'],0,2));
								
								//Guardar las Horas Trabjadas y las Horas Extra en la fecha en la que esta registrada la Entrada($fechaAnterior)
								$kardex[$fechaAnterior]["horasTrabajadas"] = $horasTrabajadas;
								$hrsExtra = $horasTrabajadas - $jornada;
								if($hrsExtra<0) $hrsExtra = 0;
								$kardex[$fechaAnterior]["horasExtra"] = $hrsExtra;							
							}
							else{
								/* Para las incidencias que pertenecen al grupo de las Deducciones, dejar el registro vacio
								 * F=>Falta, P=Permiso Sin Goce, E=>Incpacidad Enfermedad General, RT=>Incapacidad Accidente Trabajo, T=>Incapacidad en Trayecto, 
								 * D=>Sancion Disciplinaria, R=>Regresaron */
							}
														
						}//Cierre if($datos_checadaEntrada=mysql_fetch_array($rs_checadaEntrada))
						else{
						
							//Si no existe la entrada en la fecha de la SALIDA y no esta en un día anterior, entonces dejamps vacio el registro													
							
						}//Cierre ELSE
						
					}//Cierre ELSE if($datos_checadaEntrada=mysql_fetch_array($rs_checadaEntrada))
										
				}//Cierre if($datos['estado']=="SALIDA")
				
				
			}while($datos=mysql_fetch_array($rs));//Cierre for($i=0;$i<$cantDias;$i++)
			
			//Obtener la cantidad final de días trabajados
			$kardex['diasTrabajados'] = intval($diasTrabajados * $factorSeptimoDia);
														
		}//Cierre if($datos=mysql_fetch_array($rs))
		
								
		//Retornar el Arreglo con los datos encontrados (Incidencias, Horas Trabajadas, Horas Extra y Dias Trabajados)
		return $kardex;
				
	}//Cierre de la función obtenerKardexEmpleado($rfc_empleado,$fechaInicio,$fechaFin,$cantDias)
	
	
	//Esta función guardará los datos de la Nomina Interna calculada
	function guardarNominaInterna(){
		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		
		//Verificar si la nomina que será registrada ya existe en la BD, para borrar los datos previos y hacer una inserción nueva de datos
		$idNomina = "";
		if(isset($_POST['hdn_idNominaInterna'])){
			//Obtener el ID de la Nomina que será registrada
			$idNomina = $_POST['hdn_idNominaInterna'];
			//Borrar los datos de la tabla de Nomina Interna
			mysql_query("DELETE FROM nomina_interna WHERE id_nomina = '$idNomina'");
			//Borrar los datos de la tabla de Detalle Nomina Interna
			mysql_query("DELETE FROM det_nom_interna WHERE nomina_interna_id_nomina = '$idNomina'");
		}
		else{
			//Calcular el ID en base a los registros existentes en la Base de Datos
			$idNomina = obtenerIdNomina();				
		}
		
				
		//Recuperar datos generales para el registro de la Nomina
		$periodo = $_POST['hdn_periodo'];
		$fechaReg = date("Y-m-d");
		$fechaIni = modFecha($_POST['hdn_fechaIni'],3);
		$fechaFin = modFecha($_POST['hdn_fechaFin'],3);
		$area = $_POST['hdn_area'];
		
		//Extraer el Mes y el Año de la Fecha de Inicio del Periodo(Semanal o Quincenal) Seleccionadao
		$meses = array("01"=>"ENERO","02"=>"FEBRERO","03"=>"MARZO","04"=>"ABRIL","05"=>"MAYO","06"=>"JUNIO",
						"07"=>"JULIO","08"=>"AGOSTO","09"=>"SEPTIEMBRE","10"=>"OCTUBRE","11"=>"NOVIEMBRE","12"=>"DICIEMBRE");
		$partesFecha = explode("-",$fechaIni);
		$anio = $partesFecha[0];
		$mes = $meses[$partesFecha[1]];
		
		//Crear sentencia SQL para guardar los datos generales de la Nomina
		$sql_stm = "INSERT INTO nomina_interna(id_nomina,periodo,fecha_registro,fecha_inicio,fecha_fin,area,anio,mes)
					VALUES('$idNomina','$periodo','$fechaReg','$fechaIni','$fechaFin','$area','$anio','$mes')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		//Verificar resultado de la inserción
		if($rs){
			//Obtener la cantidad de registros a procesar en la Nomina
			$cantRegs = $_POST['hdn_cantRegistros'];
			
			for($i=1;$i<=$cantRegs;$i++){
				//Recuperar datos del POST
				$rfc = $_POST["hdn_rfcEmpleado".$i];
				$sueldoDiario = $_POST["hdn_sueldoDiario".$i];
				//Quitar coma ',' y signo de moneda '$' del Sueldo del Periodo
				$sueldoPerido = str_replace(",","",$_POST["txt_sueldo".$i]);
				$sueldoPerido = str_replace("$","",$sueldoPerido);
				//Quitar coma ',' y signo de moneda '$' del Tiempo Extra
				$tExtra = str_replace(",","",$_POST["txt_tiempoExtra".$i]);
				$tExtra = str_replace("$","",$tExtra);
				//Quitar coma ',' y signo de moneda '$' del Día de Descanso Trabajado
				$descanso = str_replace(",","",$_POST["txt_descansoTrabajado".$i]);
				$descanso = str_replace("$","",$descanso);
				//Quitar coma ',' y signo de moneda '$' de la Bonificacion
				$bonificacion = str_replace(",","",$_POST["txt_bonificacion".$i]);
				$bonificacion = str_replace("$","",$bonificacion);
				$bonos = $_POST["hdn_idBonos".$i];
				//Quitar coma ',' y signo de moneda '$' del Sueldo Total
				$sueldoTotal = str_replace(",","",$_POST["txt_sueldoTotal".$i]);
				$sueldoTotal = str_replace("$","",$sueldoTotal);
				
				//Crear la Sentencia SQL para guardar cada registro
				$sql_stm = "INSERT INTO det_nom_interna(nomina_interna_id_nomina,empleados_rfc_empleado,sueldo_diario,sueldo_periodo,tiempo_extra,descanso_trabajado,
				bonificacion,bonos,sueldo_total) VALUES('$idNomina','$rfc',$sueldoDiario,$sueldoPerido,$tExtra,$descanso,$bonificacion,'$bonos',$sueldoTotal)";
				
				//Ejecutar Sentencias SQL
				$rs = mysql_query($sql_stm);
				
			}//Cierre for($i=1;$i<=$cantRegs;$i++)
			
			//Registrar la operacion en la Bitácora de Movimientos
			registrarOperacion("bd_recursos","$idNomina","RegistrarNominaInterna",$_SESSION['usr_reg']);	
			//Redireccionar a la pagina de exito.
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			
		}//Cierre if($rs)
		else{
			//Cerrar la conexion con la BD		
			mysql_close($conn);			
			//Recuperar el Error emitido
			$error = mysql_error();
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}

	}//Cierre de la función guardarNominaInterna()
	
	
	//Esta función se encarga de generar el Id de la Nomina Interna que será registrada
	function obtenerIdNomina(){		
		
		//Definir las  letras en la Id de la Nomina Interna
		$id_cadena = "NOI";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las nominas del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Nominas registradas 
		$stm_sql = "SELECT COUNT(id_nomina) AS cant FROM nomina_interna WHERE id_nomina LIKE 'NOI$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}					
		
		//Regresar el ID obtenido
		return $id_cadena;
		
	}//Fin de la Funcion obtenerIdRequisicion()	
		
		
?>