<?php
	/**
	  * Nombre del Módulo: Producción
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 21/Julio/2011
	  * Descripción: Este archivo contiene funciones para generar los 2 tipos de reportes de producción
	**/
	
	
	//Funcion para mostrar la informacion del reporte generado por fecha de la producción
	function mostrarRepoFechaProd(){

		//Arreglo que permitira llevar la consulta y el mensaje al frm_generarReporte para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arregloDatosPro = array();

		//Obtener la fecha en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$fechaSeleccionada = modFecha($_POST['txt_fecha'],3);
		
		//Obtener el volumen presupuestado diario
		$presupuestoDiario = obtenerDatoFechas("bd_produccion", "presupuesto", "vol_ppto_dia", "fecha_inicio", "fecha_fin","$fechaSeleccionada");		
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg =	"Reporte Diario de Producci&oacute;n en la Fecha <em><u>".modFecha($fechaSeleccionada,2)."</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Informaci&oacute;n de Producci&oacute;n en la Fecha <em><u>".modFecha($fechaSeleccionada,2)."</u></em></label><br/>";					
		
		
		
		
		
		//Ejecutar la Sentencia para obtener el total de volumen producido en la fecha dada
		$rs_volumen = mysql_query("SELECT vol_producido,observaciones FROM datos_bitacora WHERE bitacora_produccion_fecha = '$fechaSeleccionada'");
		$volProDiario = 0;
		$observaciones = "";
		if($datosVolumen=mysql_fetch_array($rs_volumen)){
			do{
				//Obtener el Volumen Total Producido
				$volProDiario += $datosVolumen['vol_producido'];
				$observaciones .= $datosVolumen['observaciones'].", ";
			}while($datosVolumen=mysql_fetch_array($rs_volumen));
		}
		//Retirar la ultima como y espacio en blanco de la cadena
		$observaciones = substr($observaciones,0,(strlen($observaciones)-2));
		
		
		
		
		
		if($presupuestoDiario!="" && $volProDiario!=0){
						
			//Calcular la diferencia entre ambos volumenes
			$difVolumen = $volProDiario - $presupuestoDiario;
		
			//Desplegar los resultados en una tabla
			echo "		
			<table cellpadding='5' width='100%'>
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg</td>								
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>VOL. PRESUPUESTADO</td>
					<td class='nombres_columnas' align='center'>VOL. PRODUCIDO</td>
					<td class='nombres_columnas' align='center'>DIFERENCIA</td>
					<td class='nombres_columnas' align='center'>ACUMULADO PRESUPUESTADO</td>
					<td class='nombres_columnas' align='center'>ACUMULADO REAL</td>
					<td class='nombres_columnas' align='center'>DIFERENCIA</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				</tr>";				
				
			
			//Obtener la fecha de inicio del periodo al que pertenece la fecha seleccionada
			$fechaIniPeriodo = mysql_fetch_array(mysql_query("SELECT fecha_inicio FROM presupuesto WHERE '$fechaSeleccionada'>=fecha_inicio AND '$fechaSeleccionada'<=fecha_fin;"));
			//Obtener el Volumen Real Acumulado del Inicio del Periodo a la Fecha Actual Seleccionada
			$rs_volRealTotal = mysql_query("SELECT SUM(vol_producido) AS volumenTotal FROM datos_bitacora WHERE bitacora_produccion_fecha>='$fechaIniPeriodo[fecha_inicio]' AND bitacora_produccion_fecha<='$fechaSeleccionada'");
			$volumenRealTotal = mysql_fetch_array($rs_volRealTotal);
						
			
			
			//CALCULAR EL VOLUMEN PRESUPUESTADO DE LA FECHA DE INICIO DEL PERIODO A LA FECHA SELECCIONADA
			//Obtener el dia, mes y año de inicio como actuales y el dia del mes de fin
			$diaActual = substr($fechaIniPeriodo['fecha_inicio'],-2);
			$mesActual = substr($fechaIniPeriodo['fecha_inicio'],5,2);
			$anioActual = substr($fechaIniPeriodo['fecha_inicio'],0,4);
			$diaFin = substr($fechaSeleccionada,-2);
			
			$diasMesInicio = diasMes($mesActual, $anioActual);//Obtener los dias del mes de Inicio del periodo			
			$totalDias = ($diasMesInicio - $diaActual + 1) + $diaFin;//Obtener la Catidad total de dias de la fecha de inicio del periodo a la fecha seleccionada
			
			//Variable para acumular el presupuesto dia a dia
			$presAnterior = 0;
			$presAcumulado = 0;
			for($i=0;$i<$totalDias;$i++){
				//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
				$fechaActual = $anioActual;
				if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
				if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
				
				//Comprobar si la Fecha Actual es Domingo
				$diaDomingo = false;
				if(obtenerNombreDia($fechaActual)=="Domingo")
					$diaDomingo = true;
			
				//Acumular el Presupuesto del Primer dia
				if($i==0)
					$presAcumulado = $presupuestoDiario;				
				else{
					//Cuando el dia actual sea diferente de domingo acumular el presupuesto, ya que los domingos no se acumula
					if(!$diaDomingo)
						$presAcumulado += $presupuestoDiario;											
				}
							
				//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
				if($diaActual==$diasMesInicio){
					$diaActual = 0;				
					$mesActual++;
					
					//Verificar el cambio de año
					if($mesActual==13){
						$mesActual = 1;
						$anioActual++;
					}
				}
				
				//Incrementar el dia
				$diaActual++;
			}//Cierre for($i=0;$i<$totalDias;$i++)
						
			//Obtener la Diferencia de los Volumenes Acumulados
			$difVolAcumulado = $volumenRealTotal['volumenTotal'] - $presAcumulado;
						
			//Manejo de colores en renglones y texto 
			$nom_clase = "renglon_gris";			
			//Revisar si la DIFERENCIA DEL DIA es positiva o negativa y determinar el color de los numeros a mostrar
			$estilo = "style='color:#0000CC; font-weight:bold;'";
			if($difVolumen<0)
				$estilo = "style='color:#FF0000; font-weight:bold;'";												
			//Revisar si la DIFERENCIA DEL PERIODO es positiva o negativa y determinar el color de los numeros a mostrar
			$estilo2 = "style='color:#0000CC; font-weight:bold;'";
			if($difVolAcumulado<0)
				$estilo2 = "style='color:#FF0000; font-weight:bold;'";															
			
			 				
			//Mostrar todos los registros que han sido completados
			echo "
				<tr>
					<td class='$nom_clase' align='center'>$_POST[txt_fecha]</td>
					<td class='$nom_clase' align='center'>".number_format($presupuestoDiario,2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($volProDiario,2,".",",")."</td>
					<td class='$nom_clase' align='center' $estilo>".number_format($difVolumen,2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($presAcumulado,2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($volumenRealTotal['volumenTotal'],2,".",",")."</td>
					<td class='$nom_clase' align='center' $estilo2>".number_format($difVolAcumulado,2,".",",")."</td>
					<td class='$nom_clase' align='center'>$observaciones</td>
				</tr>";
					
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			
			//regresar la informacion precargada en el arreglo;
			$arregloDatosPro[] = $msg;			
			$arregloDatosPro[] = $presupuestoDiario;
			$arregloDatosPro[] = $volProDiario;
			$arregloDatosPro[] = $presAcumulado;
			$arregloDatosPro[] = $volumenRealTotal['volumenTotal'];
			$arregloDatosPro[] = $observaciones;
			
			//Regresar el arreglo con los datos
			return $arregloDatosPro;
			
		}//FIN if($presupuestoDiario!="" && $volProDiario!=""){	
		else{
			//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;
			return $arregloDatosPro;
		}
	}//FIN function mostrarRepoFecha()
	
	
	//Funcion para mostrar la informacion del reporte generado por fecha de los equipos
	function mostrarRepoFechaEq(){
	
		//Arreglo que permitira llevar la consulta y el mensaje al frm_generarReporte para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arregloDatosEq = array();

		//Conectar a la BD de Producción
		$conn = conecta("bd_produccion");

		//Obtener la fecha en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$fecha = modFecha($_POST['txt_fecha'],3);

		//Crear sentencia SQL
		$sql_stm = "SELECT DISTINCT nom_equipo FROM equipos WHERE bitacora_produccion_fecha='$fecha'";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Equipos";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Equipo con Producci&oacute;n Registrada en la Fecha <em><u>".modFecha($fecha,2)."</u></em></label><br />";	
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='90%'>				
				<tr>
					<td colspan='4' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='20%'>EQUIPO</td>
					<td class='nombres_columnas' align='center' width='10%'>M&sup3;</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				
				//Obtener los datos acumulados por cada Equipo, sumar el volumen y concatenar las observaciones
				$volAcumulado = 0;
				$observaciones = "";
				$rs_equipo = mysql_query("SELECT vol_producido,observaciones FROM equipos WHERE bitacora_produccion_fecha='$fecha' AND nom_equipo = '$datos[nom_equipo]'");
				if($datos_equipo=mysql_fetch_array($rs_equipo)){
					do{
						$volAcumulado += $datos_equipo['vol_producido'];
						$observaciones .= $datos_equipo['observaciones'].", ";	
					}while($datos_equipo=mysql_fetch_array($rs_equipo));
				}//Cierre if($datos_equipo=mysql_fetch_array($rs_equipo))
				
				//Retirar la ultima como y espacio en blanco de la cadena
				$observaciones = substr($observaciones,0,(strlen($observaciones)-2));
								
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$datos[nom_equipo]</td>
						<td class='$nom_clase' align='right'>".number_format($volAcumulado,2,".",",")."</td>
						<td class='$nom_clase'>$observaciones</td>
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
			//regresar la informacion precargada en el arreglo;
			$arregloDatosEq[] = $msg;
			return $arregloDatosEq;

		}// fin  if($datos=mysql_fetch_array($rs))
		else{
			//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;
			return $arregloDatosEq;
		}
	}//FIN function mostrarRepoFechaEq()


	//Funcion para mostrar la informacion del reporte generado por fecha de seguridad
	function mostrarRepoFechaSeg(){
	
		//Arreglo que permitira llevar la consulta y el mensaje al frm_generarReporte para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arregloDatosSeg = array();

		//Conectar a la BD de Producción
		$conn = conecta("bd_produccion");

		//Obtener la fecha en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$fecha = modFecha($_POST['txt_fecha'],3);

		//Crear sentencias SQL y ejecutarlas para obtener el numero de incidentes y accidentes
		$stm_sql="SELECT COUNT(tipo) AS numIncidentes FROM seguridad WHERE bitacora_produccion_fecha='$fecha' AND tipo='INCIDENTE'";
		$rs = mysql_query($stm_sql);
		$info=mysql_fetch_array($rs);	
				
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM seguridad WHERE bitacora_produccion_fecha='$fecha' ORDER BY tipo";
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Seguridad";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro de Seguridad en la Fecha <em><u>".modFecha($fecha,2)."</u></em></label><br/>";	
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Obtener el numero de datos que arroja la consulta
		$numRegistros = mysql_num_rows($rs);

		//Sacar la diferencia entre la consulta de $stm_sql y la sql_stm para obtener el numero de accidentes
		$numAcc = ($numRegistros-$info['numIncidentes']);							
		
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){

			$nom_clase = "renglon_gris";
			$cont = 1;	
			//sumar los accidentes y los incidentes
			$totalAcc="";
			$totalAcc=($info['numIncidentes']+$numAcc);
			//Desplegar los resultados de la consulta en una tabla
			echo "			
			<table cellpadding='4' width='100%'>							
				<tr>
					<td colspan='9' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='10%'>INCIDENTES</td>
					<td class='nombres_columnas' align='center' width='10%'>ACCIDENTES</td>
					<td class='nombres_columnas' align='center' width='10%'>TOTAL MES</td>
				</tr>
				<tr>
					<td class='$nom_clase' align='center'>$info[numIncidentes]</td>
					<td class='$nom_clase' align='center'>$numAcc</td>
					<td class='$nom_clase' align='center'>$totalAcc</td>
				</tr>
			</table>
			
			<table cellpadding='5' width='100%'>				
				<tr>
					<td class='nombres_columnas' align='center' width='20%'>TIPO</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				</tr>";

			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$datos[tipo]</td>
						<td class='$nom_clase'>$datos[observaciones]</td>
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
			//regresar la informacion precargada en el arreglo;
			$arregloDatosSeg[] = $msg;			
			$arregloDatosSeg[] = $sql_stm;
			$arregloDatosSeg[] = $numAcc;
			$arregloDatosSeg[] = $numRegistros;
			return $arregloDatosSeg;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{
			//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return $arregloDatosSeg;
		}
	}//FIN function mostrarRepoFechaSeg()


	//Funcion para mostrar la informacion del reporte generado por fecha de los colados
	function mostrarRepoFechaCol(){
	
		//Arreglo que permitira llevar la consulta y el mensaje al frm_generarReporte para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arregloDatosCol = array();

		//Conectar a la BD de Producción
		$conn = conecta("bd_produccion");

		//Obtener la fecha en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$fecha = modFecha($_POST['txt_fecha'],3);

		//Crear sentencia SQL
		$sql_stm = "SELECT cliente,volumen,colado,observaciones FROM detalle_colados WHERE bitacora_produccion_fecha='$fecha'";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Colados";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro de Colados <em><u>".modFecha($fecha,2)."</u></em></label>";	
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='120%'>				
				<tr>
					<td colspan='4' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='30%'>CLIENTE</td>
					<td class='nombres_columnas' align='center' width='10%'>M&sup3;</td>
					<td class='nombres_columnas' align='center' width='20%'>COLADO</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$datos[cliente]</td>
						<td class='$nom_clase' align='right'>".number_format($datos['volumen'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[colado]</td>
						<td class='$nom_clase'>$datos[observaciones]</td>
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
			//regresar la informacion precargada en el arreglo;
			$arregloDatosCol[] = $msg;			
			$arregloDatosCol[] = $sql_stm;
			return $arregloDatosCol;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{
			//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return $arregloDatosCol;
		}
	}//FIN function mostrarRepoFechaCol()
	
	
	/*Esta funcion muestra el reporte por perido de la produccion realizada*/
	function mostrarRepoMensual(){
		
		//Conectarse a la Base de Datos de Produccion
		$conn = conecta("bd_produccion");
		
		//Recuperar datos del POST
		$periodo = $_POST['cmb_periodo'];
		
		//Crear y ejecutar la Sentencia SQL para obtener las fechas del Periodo Seleccionado
		$fechas = mysql_fetch_array(mysql_query("SELECT fecha_inicio, fecha_fin FROM presupuesto WHERE periodo = '$periodo'"));
				
		//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
		$anioInicio = substr($fechas['fecha_inicio'],0,4);
		$anioFin = substr($fechas['fecha_fin'],0,4);
		
		//Seperar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
		$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
		$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
		
		//Obtener los dias del mes de Inicio del periodo
		$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);
						
		
		//Obtener el ancho en dias de los meses que componen el periodo
		$anchoDiasInicio = $diasMesInicio - intval(substr($fechas['fecha_inicio'],-2)) + 1;
		$anchoDiasFin = intval(substr($fechas['fecha_fin'],-2));
		$totalDias = $anchoDiasInicio + $anchoDiasFin;
		
		//Arreglos para almacenar los totales
		$sumPorDia = array();//Arreglo que contendra la suma de todas las ubicaciones por día
		$prodRealPorDia = array();//Arreglo que contendra la produccion real por dia acumulada
		$prodPresPorDia = array();//Arreglo que contendra la produccion presupuestada por dia acumulada
																		
		//Crear la Sentencia para obtener las ubicaciones que tienen registros en el periodo seleccionado
		$sql_stm_ubicaciones = "SELECT DISTINCT id_destino, destino FROM catalogo_destino JOIN datos_bitacora ON id_destino=catalogo_destino_id_destino 
								WHERE bitacora_produccion_fecha>='$fechas[fecha_inicio]' && bitacora_produccion_fecha<='$fechas[fecha_fin]'";
		//Ejecutar la Senetencia para obtener las ubicaciones
		$rs_ubicaciones = mysql_query($sql_stm_ubicaciones);
		
		//Sibujar los registros de cada una de las ubicaciones encontradas, de a renglon por ubicación		
		if($ubicaciones=mysql_fetch_array($rs_ubicaciones)){			
			
			
			
			/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/?>
			<table border="0" cellpadding="5" class="tabla_frm">
			<caption class="titulo_etiqueta">Reporte de Producci&oacute;n en el Periodo <em><u><?php echo $periodo;?></u></em></caption>
			<tr>
				<td rowspan="2" class="nombres_columnas">CONCEPTO</td>
				<td colspan="<?php echo $anchoDiasInicio; ?>" class="nombres_columnas"><?php echo $nomMesInicio." ".$anioInicio; ?></td>	
				<td colspan="<?php echo $anchoDiasFin; ?>" class="nombres_columnas"><?php echo $nomMesFin." ".$anioFin; ?></td>	
				<td rowspan="2" class="nombres_columnas">TOTAL MES</td>
				<td rowspan="2" class="nombres_columnas">PROMEDIO</td>
			</tr>
			<tr><?php
			//Ciclo para Colocar los dias en el encabezado
			$diaActual = substr($fechas['fecha_inicio'],-2);
			for($i=0;$i<$totalDias;$i++){
				//Si el dia es menor a 10 colocar un cero a la izquierda
				if($diaActual<10){?>
					<td class="nombres_columnas">0<?php echo $diaActual; ?></td><?php
				}else{?>			
					<td class="nombres_columnas"><?php echo $diaActual; ?></td><?php
				}
				
				//Inicializar cada posición del arreglo que contandrá la suma por día de todas las ubicaciones
				$sumPorDia[$diaActual] = 0; 
					
				if($diaActual==$diasMesInicio)
					$diaActual = 0;
				
				//Incrementar el dia
				$diaActual++;
			}//Cierre for($i=0;$i<$totalDias;$i++)?>
			</tr><?php
		
		
		
			/***************COLOCAR POR RENGLON EL DETALLE DE CADA UBICACIÓN*****************/
			//Manipular el color de los renglones de cada ubicación
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{?>
				<tr>
					<td class="nombres_filas"><strong><?php echo $ubicaciones['destino'];?></strong></td><?php
					//Obtener el dia, mes y año de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$mesActual = substr($fechas['fecha_inicio'],5,2);
					$anioActual = $anioInicio;
					
					//Variables para calcular el total y el promedio de cada Concepto
					$sumTotal = 0;
					$sumPromedio = 0;
					$contRegs = 0;
					
					//Ciclo para colocar el valor de cada dia, en el caso de que no haya registro en el dia, se dejara vacio
					for($i=0;$i<$totalDias;$i++){
						//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
						$fechaActual = $anioActual;
						if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
						if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
						
						//Comprobar si la Fecha Actual es Domingo
						$colorFondo = "";
						if(obtenerNombreDia($fechaActual)=="Domingo")
							$colorFondo = "#FFFF00";
												
						//Ejecutar Sentencia SQL para obtener el Volumen del Dia y la Ubicacion Actuales
						$rs_volumen = mysql_query("SELECT SUM(vol_producido) AS vol_producido FROM datos_bitacora 
													WHERE bitacora_produccion_fecha = '$fechaActual' AND catalogo_destino_id_destino = $ubicaciones[id_destino]");
						$volumen = mysql_fetch_array($rs_volumen);
						//Si existe Volumen, imprimirlo
						if($volumen['vol_producido']!=""){?>
							<td<?php 
								if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique?> 
									class="<?php echo $nom_clase; ?>"<?php 								
									$sumPromedio += $volumen['vol_producido'];//Obtener la suma total para obtener el Promedio descartando los Domingos
									$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
								} 
								else {//Colocar fondo amarillo cuando sea domingo?> 
									bgcolor="#FFFF00" <?php 
								}?>
							><?php 
								//Imprimir el volumen de la fecha actual								
								echo $volumen['vol_producido']; ?>
							</td><?php
							
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$sumTotal += $volumen['vol_producido'];
							
							//Sumar los volumenes encontrados por dia
							$sumPorDia[$diaActual] += $volumen['vol_producido']; 
							
						}
						else{//Si no existe volumen colocar la celda vacia?>
							<td <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php
						}
						
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio){
							$diaActual = 0;
							$mesActual++;
							
							//Verificar el cambio de año
							if($mesActual==13){
								$mesActual = 1;
								$anioActual++;
							}
						}
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>
					<td class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal,2,".",","); ?></strong></td>
					<td class="<?php echo $nom_clase; ?>"><strong><?php echo number_format(floatval($sumPromedio/$contRegs),2,".",","); ?></strong></td>
				</tr><?php
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($ubicaciones=mysql_fetch_array($rs_ubicaciones));
			
			
			
			
			/*****************COLOCAR EL TOTAL DE CADA DIA DEL PERIODO*******************/?>
			<tr>
				<td class="nombres_filas"><strong>TOTAL D&Iacute;A</strong></td><?php
				//Obtener el dia, mes y año de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$mesActual = substr($fechas['fecha_inicio'],5,2);
				$anioActual = $anioInicio;
				
				//Variables para calcular el total y el promedio de cada Concepto
				$sumTotal = 0;
				$sumPromedio = 0;
				$contRegs = 0;
				for($i=0;$i<$totalDias;$i++){
					//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
					$fechaActual = $anioActual;
					if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
					if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
					
					//Comprobar si la Fecha Actual es Domingo
					$colorFondo = "";
					if(obtenerNombreDia($fechaActual)=="Domingo")
						$colorFondo = "#FFFF00";
						
					//Colocar la suma del dia Actual en el caso que exista
					if($sumPorDia[$diaActual]!=0){?>
						<td style="font-weight:bold; color:#FF0000;" <?php 
							if($colorFondo==""){?> 
								class="<?php echo $nom_clase; ?>"<?php 
								$sumPromedio += $sumPorDia[$diaActual];//Obtener la suma total para obtener el Promedio descartando los Domingos
								$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
							} 
							else {?> 
								bgcolor="#FFFF00" <?php 
							}?>
						><?php 
							echo round($sumPorDia[$diaActual],1); ?>
						</td><?php
						
						//Obtener la suma total de la ubicacion que esta siendo impresa
						$sumTotal += $sumPorDia[$diaActual];
					}
					else{//Si no existe suma del dia colocar un espacio vacio?>
						<td<?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php					
					}
															
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio){
						$diaActual = 0;				
						$mesActual++;
						
						//Verificar el cambio de año
						if($mesActual==13){
							$mesActual = 1;
							$anioActual++;
						}
					}	
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>
				<td class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal,2,".",","); ?></strong></td>
				<td class="<?php echo $nom_clase; ?>"><strong><?php echo number_format(floatval($sumPromedio/$contRegs),2,".",","); ?></strong></td>
			</tr><?php	
			
			
			
			/****************COLOCAR LA PRODUCCION REAL DEL PERIODO POR DIA*******************/
			//Hacer cambio del color de renglon
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";?>
			<tr>
				<td class="nombres_filas"><strong>REAL</strong></td><?php
				//Obtener el dia y mes de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$valDiaAnterior = 0;
				for($i=0;$i<$totalDias;$i++){
					if($i==0){?>
						<td class="<?php echo $nom_clase; ?>"><strong><?php echo round($sumPorDia[$diaActual],1); ?></strong></td><?php
						$valDiaAnterior = $sumPorDia[$diaActual];
						//Almacenar la Produccion Real Diaria Acumulada
						$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual];
					}
					else{?>
						<td class="<?php echo $nom_clase; ?>"><strong><?php echo round($sumPorDia[$diaActual]+$valDiaAnterior,1); ?></strong></td><?php
						//Almacenar la Produccion Real Diaria Acumulada
						$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual] + $valDiaAnterior;
						
						//La suma del dia actual se convierte en el valor del dia anterior
						$valDiaAnterior = $sumPorDia[$diaActual] + $valDiaAnterior;						
					}
								
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio)
						$diaActual = 0;															
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>				
				<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
				<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
			</tr><?php
			
			
			
			/****************COLOCAR LA PRODUCCION PRESUPUESTADA DEL PERIODO*******************/
			//Hacer cambio del color de renglon			
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
			
			//Obtener el Presupusto diario del periodoh
			$datos_pres = mysql_fetch_array(mysql_query("SELECT SUM(vol_ppto_dia) as vol_ppto_dia FROM presupuesto WHERE periodo = '$periodo'"));
			$presupuesto = $datos_pres['vol_ppto_dia']; ?>			
			<tr>
				<td class="nombres_filas"><strong>PRESUPUESTO</strong></td><?php
				//Obtener el dia, mes y año de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$mesActual = substr($fechas['fecha_inicio'],5,2);
				$anioActual = $anioInicio;
				
				//Variable para acumular el presupuesto dia a dia
				$presAnterior = 0;
				for($i=0;$i<$totalDias;$i++){
					//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
					$fechaActual = $anioActual;
					if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
					if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
					
					//Comprobar si la Fecha Actual es Domingo
					$diaDomingo = false;
					if(obtenerNombreDia($fechaActual)=="Domingo")
						$diaDomingo = true;
				
					//Colocar el presupuesto en el dia inicial
					if($i==0){?>
						<td class="<?php echo $nom_clase; ?>"><strong><?php echo round($presupuesto,1); ?></strong></td><?php
						$presAnterior = $presupuesto;
						//Almacenar la produccion presupuestada por dia acumulada
						$prodPresPorDia[$diaActual] = $presupuesto;
					}
					else{
						//Verificar si la fecha actual es domingo y colocar directamente el presupuesto anterior
						if($diaDomingo){?>
							<td class="<?php echo $nom_clase; ?>"><strong><?php echo round($presAnterior,1); ?></strong></td><?php
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presAnterior;
						}
						else{?>						
							<td class="<?php echo $nom_clase; ?>"><strong><?php echo round($presupuesto+$presAnterior,1); ?></strong></td><?php
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presupuesto + $presAnterior;
							
							//La suma del dia actual se convierte en el valor del dia anterior
							$presAnterior = $presupuesto + $presAnterior;
							
						}
					}
								
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio){
						$diaActual = 0;				
						$mesActual++;
						
						//Verificar el cambio de año
						if($mesActual==13){
							$mesActual = 1;
							$anioActual++;
						}
					}
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>				
				<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
				<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
			</tr><?php
			
			
			
			/****************COLOCAR LA DIFERENCIA ENTRE LA PRODUCCION REAL Y LA PRESUPUESTADA*******************/
			//Hacer cambio del color de renglon			
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";?>			
			<tr>
				<td class="nombres_filas"><strong>DIFERENCIA</strong></td><?php
				//Obtener el dia y mes de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				for($i=0;$i<$totalDias;$i++){
					//Hacer la resta de la Produccion Real menos la Presupuestada, cuando se llega aqui existe un registro de producción real por cada registro de Produccion presupuestada?>
					<td class="<?php echo $nom_clase; ?>"><strong><?php echo round($prodRealPorDia[$diaActual]-$prodPresPorDia[$diaActual],1);?></strong></td><?php					
					
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio)
						$diaActual = 0;
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>
				<td class="<?php echo $nom_clase; ?>">&nbsp;</td>
				<td class="<?php echo $nom_clase; ?>">&nbsp;</td>				
			</tr>
			</table><?php
			
			
			//Regresar el periodo para colocarlo en el boton de Exportar Datos
			return $periodo;
			
		}//Cierre if($ubicaciones=mysql_fetch_array($rs_ubicaciones))
		else{
			echo "<label class='msje_correcto'>No hay Registros de Producci&oacute;n para Mostrar en el Periodo <em><u>$periodo</u></em></label>";
			//Regresar una cadena vacia para indicar que no existen datos para exportar
			return "";
		}				
	}//Cierre de la funcion mostrarRepoMensual()
	
	function mostrarRepoCliente1(){
		//Abrir la conexion
		$conn=conecta("bd_produccion");
		$cliente=$_POST["cmb_cliente"];
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Sentencia sin Agrupar datos
		$sql="	SELECT bitacora_produccion_fecha,volumen,colado,observaciones,factura,tipo_colado,no_remision,pagado,costo FROM detalle_colados 
				WHERE bitacora_produccion_fecha BETWEEN '$fechaI' AND '$fechaF' AND cliente='$cliente' ORDER BY bitacora_produccion_fecha,no_remision";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='9' align='center' class='titulo_etiqueta'>Cliente: <em><u>$cliente</u></em> del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>NO. REMISI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>TIPO COLADO</td>
					<td class='nombres_columnas' align='center'>NOMBRE COLADO</td>
					<td class='nombres_columnas' align='center'>VOLUMEN<br>M&sup3;</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					<td class='nombres_columnas' align='center'>FACTURADO</td>
					<td class='nombres_columnas' align='center'>PAGADO</td>
					<td class='nombres_columnas' align='center'>COSTO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase' align='right'>".modFecha($datos['bitacora_produccion_fecha'],1)."</td>
						<td class='$nom_clase'>$datos[no_remision]</td>
						<td class='$nom_clase'>$datos[tipo_colado]</td>
						<td class='$nom_clase'>$datos[colado]</td>
						<td class='$nom_clase' align='right'>".number_format($datos['volumen'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[observaciones]</td>
						<td class='$nom_clase'>$datos[factura]</td>
						<td class='$nom_clase'>$datos[pagado]</td>
						<td class='$nom_clase'>$".number_format($datos['costo'],2,".",",")."</td>
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
			mysql_close($conn);
			return $cliente."¬".$fechaI."¬".$fechaF."¬1";
		}else{
			echo "
			<br><br><br><br><br><br><br><br><br><br>
			<label class='msje_correcto'>No hay Registros de Producci&oacute;n para Mostrar del Cliente <em><u>$cliente</u></em> del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";
			mysql_close($conn);
			return "";
		}
	}
	
	
	function mostrarRepoCliente2(){
		//Abrir la conexion
		$conn=conecta("bd_produccion");
		$cliente=$_POST["cmb_cliente"];
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Sentencia agrupando el volumen por Fecha, Colado y Observaciones
		$sql="	SELECT bitacora_produccion_fecha,SUM(volumen) AS volumen,SUM(costo) AS total,colado,observaciones FROM detalle_colados WHERE bitacora_produccion_fecha BETWEEN '$fechaI' AND '$fechaF' 
				AND cliente='$cliente' GROUP BY bitacora_produccion_fecha,colado,observaciones ORDER BY bitacora_produccion_fecha,colado";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='4' align='center' class='titulo_etiqueta'>Cliente: <em><u>$cliente</u></em> del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='10%'>FECHA</td>
					<td class='nombres_columnas' align='center' width='40%'>NOMBRE COLADO</td>
					<td class='nombres_columnas' align='center' width='20%'>VOLUMEN M&sup3;</td>
					<td class='nombres_columnas' align='center' width='30%'>OBSERVACIONES</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$colado=$datos['colado'];
			$cantXColado=0;
			$costoXColado=0;
			$volTotal=0;
			$costoTotal=0;
			do{	
				if($colado!=$datos['colado']){
					echo "
						<tr>
							<td colspan='2'>&nbsp;</td>
							<td class='nombres_filas' align='Right'>VOLUMEN</td>
							<td class='nombres_columnas' align='Right'>".number_format($cantXColado,2,".",",")." M&sup3;</td>
						</tr>
						<tr>
							<td colspan='2'>&nbsp;</td>
							<td class='nombres_filas' align='Right'>COSTO</td>
							<td class='nombres_columnas' align='Right'>$".number_format($costoXColado,2,".",",")."</td>
						</tr>
					";
					$colado=$datos['colado'];
					$cantXColado=0;
					$costoXColado=0;
				}
				//Extraer informacion por Colado y Fecha
				$titulo=obtenerInformacionDia($datos['bitacora_produccion_fecha'],$datos['colado'],$cliente);
				//Mostrar los registros
				echo "
					<tr>
						<td class='$nom_clase' align='right'>".modFecha($datos['bitacora_produccion_fecha'],1)."</td>
						<td class='$nom_clase'>$datos[colado]</td>
						<td class='$nom_clase' align='right' onClick=\"mostrarDetalles('$titulo')\" style='cursor:pointer' title='Haga Click para ver Detalles'>".number_format($datos['volumen'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[observaciones]</td>
					</tr>";
				$cantXColado+=$datos["volumen"];
				$costoXColado+=$datos["total"];
				$volTotal+=$datos["volumen"];
				$costoTotal+=$datos["total"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			$iva=$costoTotal*0.16;
			$total=$iva+$costoTotal;
			echo "
				<tr>
					<td colspan='2'>&nbsp;</td>
					<td class='nombres_filas' align='Right'>VOLUMEN</td>
					<td class='nombres_columnas' align='Right'>".number_format($cantXColado,2,".",",")." M&sup3;</td>
				</tr>
				<tr>
					<td colspan='2'>&nbsp;</td>
					<td class='nombres_filas' align='Right'>COSTO</td>
					<td class='nombres_columnas' align='Right'>$".number_format($costoXColado,2,".",",")."</td>
				</tr>
				
				<tr><td colspan='4'>&nbsp;</td></tr>
				
				<tr>
					<td colspan='2'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>VOLUMEN TOTAL</td>
					<td class='nombres_columnas' align='center'>".number_format($volTotal,2,".",",")." M&sup3;</td>
				</tr>
				<tr>
					<td colspan='2'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>SUBTOTAL</td>
					<td class='nombres_columnas' align='center'>$".number_format($costoTotal,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='2'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>IVA (16%)</td>
					<td class='nombres_columnas' align='center'>$".number_format($iva,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='2'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>TOTAL</td>
					<td class='nombres_columnas' align='center'>$".number_format($total,2,".",",")."</td>
				</tr>
			";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			mysql_close($conn);
			return $cliente."¬".$fechaI."¬".$fechaF."¬2";
		}else{
			echo "
			<br><br><br><br><br><br><br><br><br><br>
			<label class='msje_correcto'>No hay Registros de Producci&oacute;n para Mostrar del Cliente <em><u>$cliente</u></em> del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";
			mysql_close($conn);
			return "";
		}
	}
	
	function obtenerInformacionDia($fecha,$colado,$cliente){
		$titulo="";
		$sql="SELECT factura,tipo_colado,no_remision,pagado,costo,volumen FROM detalle_colados WHERE bitacora_produccion_fecha='$fecha' AND colado='$colado' AND cliente='$cliente'";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			$titulo="DETALLE AL ".modFecha($fecha,1)." PARA $cliente.<br>COLADO $colado:<br><br>";
			$cont=1;
			$costoTotal=0;
			do{
				//Verificar si esta facturado
				if($datos["factura"]!="NO")
					$factura=$datos["factura"];
				else
					$factura="NO FACTURADO";
				//Obtener el tipo de colado
				$tipoColado=$datos["tipo_colado"];
				//Verificar si hay una remision asociada
				if($datos["no_remision"]!="")
					$remision=$datos["no_remision"];
				else
					$remision="Sin Remisión Registrada";
				//Verificar si esta pagado o no
				if($datos["pagado"]=="")
					$pago="Sin Pago Registrado";
				else
					$pago=$datos["pagado"];
				//Verificar si tiene costo asociado (Diferente de 0)
				if($datos["costo"]!=0){
					$costo="$".number_format($datos["costo"],2,".",",");
					$costoTotal+=$datos["costo"];
				}
				else
					$costo="Sin Costo Registrado";
				//Obtener el Volumen
				$volumen=$datos["volumen"];
				//Ensamblar el titulo
				$titulo.="Registro $cont:<br>";
				$titulo.="-Remisión: $remision<br>";
				$titulo.="-Factura: $factura<br>";
				$titulo.="-Tipo Colado: $tipoColado<br>";
				$titulo.="-Volumen Colado: $volumen M³<br>";
				$titulo.="-Pagado: $pago<br>";
				$titulo.="-Costo: $costo<br>";
				$titulo.="<br>";
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			//Si el CostoTotal es diferente de 0, agregarlo al Msje
			if($costoTotal!=0)
				$titulo.="COSTO TOTAL: $".number_format($costoTotal,2,".",",");
		}
		//Retornar el titulo tal cual se ensamblo o quedo
		return $titulo;
	}
?>