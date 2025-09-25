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
		//Recuperar datos del POST
		$periodo = $_POST['cmb_periodo'];
		//Ensamblar Titulo
		$titulo="Reporte de Producci&oacute;n en el Periodo $periodo";
		//Conectarse a la Base de Datos de Produccion
		$conn = conecta("bd_produccion");
		//Verificar si viene el numero de empleados en el post para agregar la Seccion de Productividad al reporte que será exportado
		$noEmpleados = "";
		if(isset($_POST['hdn_numEmpleados']))
			$noEmpleados = $_POST['hdn_numEmpleados'];
		
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
							
			/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/
			$tabla="<table border='0' cellpadding='5'>
			<caption class='titulo_tabla'><strong>Reporte de Producción en el Periodo <em><u>$periodo</u></em></strong></caption>
			<tr>
				<td rowspan='2' class='nombres_columnas'>CONCEPTO</td>
				<td colspan='$anchoDiasInicio' class='nombres_columnas' align='center'>$nomMesInicio $anioInicio</td>	
				<td colspan='$anchoDiasFin' class='nombres_columnas' align='center'>$nomMesFin $anioFin</td>	
				<td rowspan='2' class='nombres_columnas' align='center'>TOTAL MES</td>
				<td rowspan='2' class='nombres_columnas' align='center'>PROMEDIO</td>
			</tr>
			<tr>";
			//Ciclo para Colocar los dias en el encabezado
			$diaActual = substr($fechas['fecha_inicio'],-2);
			for($i=0;$i<$totalDias;$i++){
				//Si el dia es menor a 10 colocar un cero a la izquierda
				if($diaActual<10){
					$tabla.="<td class='nombres_columnas' align='center'>0$diaActual</td>";
				}else{
					$tabla.="<td class='nombres_columnas' align='center'>$diaActual</td>";
				}
				//Inicializar cada posición del arreglo que contandrá la suma por día de todas las ubicaciones
				$sumPorDia[$diaActual] = 0; 	
				if($diaActual==$diasMesInicio)
					$diaActual = 0;
				//Incrementar el dia
				$diaActual++;
			}//Cierre for($i=0;$i<$totalDias;$i++)
			$tabla.="</tr>";
			/***************COLOCAR POR RENGLON EL DETALLE DE CADA UBICACIÓN*****************/
			//Manipular el color de los renglones de cada ubicación
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				$tabla.="<tr>";
					$tabla.="<td class=nombres_filas><strong>$ubicaciones[destino]</strong></td>";
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
						if($volumen['vol_producido']!=""){
							$tabla.="<td align='center' ";
								if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique
									$tabla.="class='$nom_clase'";
									$sumPromedio += $volumen['vol_producido'];//Obtener la suma total para obtener el Promedio descartando los Domingos
									$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
								} 
								else {//Colocar fondo amarillo cuando sea domingo
									$tabla.="bgcolor='#FFFF00' style='font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;'";
								}
							$tabla.=">";
								//Imprimir el volumen de la fecha actual								
								$tabla.="$volumen[vol_producido]";
							$tabla.="</td>";
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$sumTotal += $volumen['vol_producido'];
							//Sumar los volumenes encontrados por dia
							$sumPorDia[$diaActual] += $volumen['vol_producido']; 
						}
						else{//Si no existe volumen colocar la celda vacia
							$tabla.="<td align='center' ";
							if($colorFondo==""){
								$tabla.="class='$nom_clase'";
							}else{
								$tabla.="bgcolor='#FFFF00'";
							}
							$tabla.="></td>";
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
					$tabla.="<td align='center' class='$nom_clase'><strong>".number_format($sumTotal,2,".",",")."</strong></td>
							<td align='center' class='$nom_clase'><strong>".number_format(floatval($sumPromedio/$contRegs),2,".",",")."</strong></td>
							</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($ubicaciones=mysql_fetch_array($rs_ubicaciones));
			/****************COLOCAR EL TOTAL DE CADA DIA DEL PERIODO*******************/
			$tabla.="<tr><td class='nombres_filas'><strong>TOTAL DÍA</strong></td>";
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
					if($sumPorDia[$diaActual]!=0){
						$tabla.="<td align='center' style='font-weight:bold;color:#FF0000;'";
							if($colorFondo==""){
								$tabla.="class='$nom_clase'";
								$sumPromedio += $sumPorDia[$diaActual];//Obtener la suma total para obtener el Promedio descartando los Domingos
								$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
							} 
							else{
								$tabla.="bgcolor='#FFFF00' style='font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;'";
							}
						$tabla.=">";
							$tabla.=round($sumPorDia[$diaActual],1);
						$tabla.="</td>";					
						//Obtener la suma total de la ubicacion que esta siendo impresa
						$sumTotal += $sumPorDia[$diaActual];
					}
					else{//Si no existe suma del dia colocar un espacio vacio
						$tabla.="<td align='center' ";
							if($colorFondo==""){
								$tabla.="class='$nom_clase'";
							}else{
								$tabla.="bgcolor='#FFFF00'";
							}
							$tabla.="></td>";
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
				$tabla.="<td align='center' class='$nom_clase'><strong>".number_format($sumTotal,2,".",",")."</strong></td>
						<td align='center' class='$nom_clase'><strong>".number_format(floatval($sumPromedio/$contRegs),2,".",",")."</strong></td>
						</tr>";
			/****************COLOCAR LA PRODUCCION REAL DEL PERIODO POR DIA*******************/
			//Hacer cambio del color de renglon
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
				$tabla.="<tr><td class='nombres_filas'><strong>REAL</strong></td>";
				//Obtener el dia y mes de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$valDiaAnterior = 0;
				for($i=0;$i<$totalDias;$i++){
					if($i==0){
						$tabla.="<td align='center' class='$nom_clase'><strong>".round($sumPorDia[$diaActual],1)."</strong></td>";
						$valDiaAnterior = $sumPorDia[$diaActual];
						//Almacenar la Produccion Real Diaria Acumulada
						$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual];
					}
					else{
						$tabla.="<td align='center' class='$nom_clase'><strong>".round($sumPorDia[$diaActual]+$valDiaAnterior,1)."</strong></td>";
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
				}//Cierre for($i=0;$i<$totalDias;$i++)
				$tabla.="<td></td><td></td></tr>";
			/****************COLOCAR LA PRODUCCION PRESUPUESTADA DEL PERIODO*******************/
			//Hacer cambio del color de renglon			
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
			//Obtener el Presupusto diario del periodo
			$presupuesto = obtenerDato("bd_produccion", "presupuesto", "vol_ppto_dia", "periodo", $periodo);
			$tabla.="<tr><td class='nombres_filas'><strong>PRESUPUESTO</strong></td>";
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
					if($i==0){
						$tabla.="<td align='center' class='$nom_clase'><strong>".round($presupuesto,1)."</strong></td>";
						$presAnterior = $presupuesto;
						//Almacenar la produccion presupuestada por dia acumulada
						$prodPresPorDia[$diaActual] = $presupuesto;
					}
					else{
						//Verificar si la fecha actual es domingo y colocar directamente el presupuesto anterior
						if($diaDomingo){
							$tabla.="<td align='center' class='$nom_clase'><strong>".round($presAnterior,1)."</strong></td>";
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presAnterior;
						}
						else{
							$tabla.="<td align='center' class='$nom_clase'><strong>".round($presupuesto+$presAnterior,1)."</strong></td>";
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
				}//Cierre for($i=0;$i<$totalDias;$i++)
				$tabla.="<td></td><td></td></tr>";

			/****************COLOCAR LA DIFERENCIA ENTRE LA PRODUCCION REAL Y LA PRESUPUESTADA*******************/
			//Hacer cambio del color de renglon			
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
				$tabla.="<tr><td class='nombres_filas'><strong>DIFERENCIA</strong></td>";
				//Obtener el dia y mes de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				for($i=0;$i<$totalDias;$i++){
					//Hacer la resta de la Produccion Real menos la Presupuestada, cuando se llega aqui existe un registro de producción real por cada registro de Produccion presupuestada
					$tabla.="<td align='center' class='$nom_clase'><strong>".round($prodRealPorDia[$diaActual]-$prodPresPorDia[$diaActual],1)."</strong></td>";
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio)
						$diaActual = 0;
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)
				$tabla.="<td></td><td></td></tr>";
			/****************COLOCAR LA PRODUCTIVIDAD DE CADA DIA*******************/			
			if($noEmpleados!=""){
				$tabla.="<tr>
					<td colspan='31' align='right'></td>
					<td colspan='2' align='center' class='nombres_columnas'>PRODUCTIVIDAD PROMEDIO</td>
				</tr>
				<tr>
					<td class='nombres_filas'><strong>PRODUCTIVIDAD</strong></td>";
					//Obtener la Suma Total de la Productividad para sacar el Promedio de la misma
					$totalProductividad = 0;
					$contDias = 0;
					foreach($sumPorDia as $ind => $totalDia){
						//Colocar la suma del dia Actual en el caso que exista
						if($totalDia!=0){
							$productividad = $totalDia/$noEmpleados;
							$tabla.="<td align='center' class='$nom_clase'>".number_format($productividad,2,".",".")."</td>";
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$totalProductividad += $productividad;
							$contDias++;
						}
						else{//Si no existe suma del dia colocar un espacio vacio
							$tabla.="<td class='$nom_clase'></td>";
						}																				
					}//Cierre foreach($sumPorDia as $ind => $totalDia)
					$tabla="<td colspan='2' class='$nom_clase' align='center'><strong>".number_format($totalProductividad/$contDias,2,".",",")."</strong></td></tr>";
			}//Cierre if($noEmpleados!="")
			$tabla.="</table>";
			echo $tabla;
			//mostrarTabla($tabla);
			$grafica=dibujarGrafica1($prodPresPorDia,$prodRealPorDia,$titulo);
			return $grafica."¬".$periodo;
		}//Cierre if($ubicaciones=mysql_fetch_array($rs_ubicaciones))				
	}//Cierre de la funcion mostrarRepoMensual()
	
	//Grafica que es incluida en el reporte de Agregados
	function dibujarGrafica1($datosPreupuesto,$datosProduccion,$msg){	
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_plotline.php');
		
		$msg=str_replace("&oacute;","ó",$msg);
		
		//Obtener las fechas para ser colocados en la Grafica
		$fechas = array_keys($datosPreupuesto);	
		
		$dias = array();
		//Solo dejar los digitos del dia de cada fecha para ser colocados en la grafica
		foreach($fechas as $ind => $fecha)
			$dias[] = substr($fecha,-2);
		
		//Redeondear los valores del presupuesto y colocarlos en otro Arreglo
		$preupuesto = array();
		foreach($datosPreupuesto as $ind => $valor)
			$preupuesto[] = round($valor);
		
		//Redeondear los valores del presupuesto y colocarlos en otro Arreglo
		$produccion = array();
		foreach($datosProduccion as $ind => $valor)
			$produccion[] = round($valor);
		
		//Crear el Grafico, se deben hacer dos llamadas a los metodos Graph() y SetScale()
		$graph = new Graph(940,450);
		$graph->SetScale('textlin');
		$graph->title->Set($msg);
		//Colocar los Margenes del Grafico(Izq,Der,Arriba,Abajo)
		$graph->SetMargin(60,120,40,60);				
		//Colocar el Color del Margen
		$graph->SetMarginColor('white@0.5');
		
		//Colocar los Titulos a los Ejes
		$graph->yaxis->title->Set('METROS CUBICOS');//Eje Y
		$graph->yaxis->title->SetMargin(20);
		$graph->xaxis->title->Set('DIAS');//Eje X
			
		//Crear la primera linea del Grafico con los Datos del Presupuesto
		$lineplot=new LinePlot($preupuesto);
		$lineplot->SetColor('red');
		$lineplot->SetLegend('Presupuesto');
		//Muestra y formatea los valores de los datos en la linea correspondiente
		$lineplot->mark->SetType(MARK_FILLEDCIRCLE);
		$lineplot->mark->SetFillColor("black");
		$lineplot->mark->SetWidth(2);
		$lineplot->value->Show();
		
		//Crear la segunda linea del Grafico con los Datos de la Produccion		
		$lineplot2=new LinePlot($produccion);
		$lineplot2->SetColor('blue');
		$lineplot2->SetLegend('Producción Real');	
		//Muestra los valores de los datos en la linea correspondiente
		//$lineplot2->value->Show();					
		
		//Agregar Nombres de los rotulos del eje X
		$graph->xaxis->SetTickLabels($dias);
		//Establecer el margen separación entre etiquetas del Eje X
		$graph->xaxis->SetTextLabelInterval(1);
		
		//Agregar las lineas de datos a la grafica
		$graph->Add($lineplot);
		$graph->Add($lineplot2);
		
		//Alinear los rotulos de la leyenda
		$graph->legend->SetPos(0.05,0.5,'right','center');
		
		//Crea un nombre oara la grafica que sera guardada en un archivo temporal
		$rnd=rand(0,1000);		
		$grafica= "tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Retornar el directorio y nombre de la grafica creada temporalmente para ser mostrada en una pagina HTML
		return $grafica;
	}//Cierre dibujarGrafica($msg,$datosPreupuesto,$datosProduccion)
?>