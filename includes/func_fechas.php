<?php	
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 29/Septiembre/2010                                      			
	  * Descripci�n: Este archivo contiene multiples funciones para el manejo de fechas 
	  **/
	
	
	//Esta funcion muestra la fecha en formato opc1: "Lunes 01 de Enero de 2000", opc2:01/Enero/2000, opc3: 2000-01-01(aaaa-mm-dd), opc4:01/01/2000(dd/mm/aaaa)
	function verFecha($opc){
		ini_set("date.timezone","America/Mexico_City");		
		
		//Las Opciones 1, 2 y 5 necesitan del Dia con letra y el mes para ser devueltas en el formato indicado
		if($opc==1 || $opc==2 || $opc==5){
			$date = getdate();
			$dia = "";
			//Identificar dia
			switch($date['weekday']){
				case "Monday":		$dia = "Lunes";		break;
				case "Tuesday":		$dia = "Martes";	break;
				case "Wednesday":	$dia = "Mi�rcoles";	break;
				case "Thursday":	$dia = "Jueves";	break; 
				case "Friday":		$dia = "Viernes";	break;
				case "Saturday":	$dia = "S�bado";	break;
				case "Sunday":		$dia = "Domingo";	break;			
			}				
		
			$mes = "";
			//Identificar mes
			switch($date['month']){
				case "January":		$mes="Enero";		break;
				case "February":	$mes="Febrero";		break;
				case "March":		$mes="Marzo";		break;			
				case "April":		$mes="Abril";		break;
				case "May":			$mes="Mayo";		break;
				case "June":		$mes="Junio";		break;
				case "July":		$mes="Julio";		break;
				case "August":		$mes="Agosto";		break;
				case "September":	$mes="Septiembre";	break;
				case "October":		$mes="Octubre";		break;
				case "November":	$mes="Noviembre"; 	break;
				case "December": 	$mes="Diciembre";	break;
			}
		}
		
		switch($opc){
			case 1://opc1: Lunes 01 de Enero de 2000
				return $fecha = $dia." ".$date['mday']." de ".$mes." de ".$date['year'];
			break;
			case 2://opc2: 01/Enero/2000
				return $fecha = $date['mday']."/".$mes."/".$date['year'];
			break;
			case 3://opc3: 2000-01-01(aaaa-mm-dd)
				return date("Y-m-d");
			break;
			case 4://opc4:01/01/2000(dd/mm/aaaa)
				return date("d/m/Y");
			break;
			case 5://opc5: 01 de Enero de 2000
				return $fecha = $date['mday']." de ".$mes." de ".$date['year'];
			break;						
		}
	}
	
	//Modificar la fecha del formato (2000-12-24 y 24/12/2000) a los formatos 24/12/2010, 24 de Diciembre de 2000 y 2000-12-24
	function modFecha($fecha,$opc){
		ini_set("date.timezone","America/Mexico_City");		
		$car = "-";
		if($opc==3)
			$car = "/";
								
		$arrFecha = split($car,$fecha);		
		switch($opc){
			case 1://La opcion 1 regresa la fecha en el formato dd/mm/aaaa (24/12/2010) a partir del formato aaaa-mm-dd (2000-12-24)
				return $arrFecha[2]."/".$arrFecha[1]."/".$arrFecha[0];
			break;
			case 2://Regresa la fecha en el formato  24 de Diciembre de 2000 a partir de aaaa-mm-dd (2000-12-24)
				$mes = "";
				//Identificar mes
				switch($arrFecha[1]){
					case "01":		$mes="Enero";		break;
					case "02":		$mes="Febrero";		break;
					case "03":		$mes="Marzo";		break;			
					case "04":		$mes="Abril";		break;
					case "05":		$mes="Mayo";		break;
					case "06":		$mes="Junio";		break;
					case "07":		$mes="Julio";		break;
					case "08":		$mes="Agosto";		break;
					case "09":		$mes="Septiembre";	break;
					case "10":		$mes="Octubre";		break;
					case "11":		$mes="Noviembre"; 	break;
					case "12": 		$mes="Diciembre";	break;
				}
				return $arrFecha[2]." de ".$mes." de ".$arrFecha[0];
			break;
			case 3://Regresa la fecha en el formato aaaa-mm-dd (2000-12-24) a partir de dd/mm/aaaa (24/12/2000)
				return $arrFecha[2]."-".$arrFecha[1]."-".$arrFecha[0];
			break;
			case 4://Regresa la fecha en el formato dd/mm/aa (24/12/10) a partir del formato aaaa-mm-dd (2000-12-24)
				return $arrFecha[2]."/".$arrFecha[1]."/".substr($arrFecha[0],2,2);
			break;
			case 5://Regresa la fecha en el formato dd y 3 letas del mes sin a�o
				$mes = "";
				//Identificar mes
				switch($arrFecha[1]){
					case "01":		$mes="Ene";		break;
					case "02":		$mes="Feb";		break;
					case "03":		$mes="Mar";		break;			
					case "04":		$mes="Abr";		break;
					case "05":		$mes="May";		break;
					case "06":		$mes="Jun";		break;
					case "07":		$mes="Jul";		break;
					case "08":		$mes="Ago";		break;
					case "09":		$mes="Sep";		break;
					case "10":		$mes="Oct";		break;
					case "11":		$mes="Nov"; 	break;
					case "12": 		$mes="Dic";		break;
				}
				return $arrFecha[2]."-".$mes;
			break;	
			case 6://Regresa la fecha en el formato MES/A�O=>> ENERO/2000
				$mes = "";
				//Identificar mes
				switch($arrFecha[1]){
					case "01":		$mes="ENERO";		break;
					case "02":		$mes="FEBRERO";		break;
					case "03":		$mes="MARZO";		break;			
					case "04":		$mes="ABRIL";		break;
					case "05":		$mes="MAYO";		break;
					case "06":		$mes="JUNIO";		break;
					case "07":		$mes="JULIO";		break;
					case "08":		$mes="AGOSTO";		break;
					case "09":		$mes="SEPTIEMBRE";	break;
					case "10":		$mes="OCTUBRE";		break;
					case "11":		$mes="NOVIEMBRE"; 	break;
					case "12": 		$mes="DICIEMBRE";	break;
				}
				return $mes."/".$arrFecha[0];
			break;
			case 7://Regresa la Fecha en Formato 01/Ene/2012 a partir del formato aaaa-mm-dd
				$mes = "";
				//Identificar mes
				switch($arrFecha[1]){
					case "01":		$mes="Enero";		break;
					case "02":		$mes="Febrero";		break;
					case "03":		$mes="Marzo";		break;			
					case "04":		$mes="Abril";		break;
					case "05":		$mes="Mayo";		break;
					case "06":		$mes="Junio";		break;
					case "07":		$mes="Julio";		break;
					case "08":		$mes="Agosto";		break;
					case "09":		$mes="Septiembre";	break;
					case "10":		$mes="Octubre";		break;
					case "11":		$mes="Noviembre"; 	break;
					case "12": 		$mes="Diciembre";	break;
				}
				return $arrFecha[2]."/".$mes."/".$arrFecha[0];
			break;
			case 8://Regresa la fecha en el formato dia letra dd y 3 letas del mes sin a�o
				$mes = "";
				//Identificar mes
				switch($arrFecha[1]){
					case "01":		$mes="Ene";		break;
					case "02":		$mes="Feb";		break;
					case "03":		$mes="Mar";		break;			
					case "04":		$mes="Abr";		break;
					case "05":		$mes="May";		break;
					case "06":		$mes="Jun";		break;
					case "07":		$mes="Jul";		break;
					case "08":		$mes="Ago";		break;
					case "09":		$mes="Sep";		break;
					case "10":		$mes="Oct";		break;
					case "11":		$mes="Nov"; 	break;
					case "12": 		$mes="Dic";		break;
				}
				return obtenerNombreDia($fecha)." ".$arrFecha[2]." ".$mes;
			break;
			case 9://Regresa la fecha en el formato DIA_SEMANA 24 de Diciembre a partir de aaaa-mm-dd (2000-12-24)
				$mes = "";
				//Identificar mes
				switch($arrFecha[1]){
					case "01":		$mes="Enero";		break;
					case "02":		$mes="Febrero";		break;
					case "03":		$mes="Marzo";		break;			
					case "04":		$mes="Abril";		break;
					case "05":		$mes="Mayo";		break;
					case "06":		$mes="Junio";		break;
					case "07":		$mes="Julio";		break;
					case "08":		$mes="Agosto";		break;
					case "09":		$mes="Septiembre";	break;
					case "10":		$mes="Octubre";		break;
					case "11":		$mes="Noviembre"; 	break;
					case "12": 		$mes="Diciembre";	break;
				}
				return obtenerNombreDia($fecha)." ".$arrFecha[2]." de ".$mes;
			break;
		}
	}//Fin de la funcion modFecha($fecha,$opc)		
	
	
	//Esta funcion recibe la hora en formato de 24 horas H:m:s y la regresa en formato de 12 horas h:m:s am/pm
	function modHora($time){
		ini_set("date.timezone","America/Mexico_City");		
		//Separar las horas, los minutos y los segundos
		$hora_separada = split(":",$time);
		$meridiano = "";
		//Convertir la hora en tipo numerico
		$horas = intval($hora_separada[0]);
		$no_hora;
		switch($horas){
			case 0:
				$no_hora = 12;
				$meridiano = "am";
			break;
			case ($horas>0 && $horas<12):
				$no_hora = $horas;
				$meridiano = "am"; 
			break;
			case $horas==12:
				$no_hora = 12;
				$meridiano = "pm"; 
			break; 
			case ($horas>12 && $horas<23):
				$no_hora = $horas - 12;
				$meridiano = "pm"; 
			break;
			case $horas==23:
				$no_hora = 11;
				$meridiano = "pm"; 
			break;
		}
		
		return $no_hora.":".$hora_separada[1].":".$hora_separada[2]." ".$meridiano;
	}//Fin de la funcion modHora($time)
	
	
	//Esta funcion recibe la hora en formato de 12 horas hh:mm AM/PM y la regresa en formato de 24 horas hh:mm:ss
	function modHora24($time){
		ini_set("date.timezone","America/Mexico_City");		
		//Separar la hora del meridiano(AM/PM) y obtener el meridiano
		$hora = substr($time,0,5); $meridiano = substr($time,6,2);
		$hora_formateada = "";
		switch($meridiano){
			case "AM":
				if(substr($hora,0,2)=="12")
					$hora_formateada = "00:".substr($hora,3,2).":00";
				else
					$hora_formateada = $hora.":00";
			break;
			case "PM":
				if(substr($hora,0,2)=="12")
					$hora_formateada = $hora.":00";
				else{
					$dig_hora = intval(substr($hora,0,2))+12;
					$hora_formateada = $dig_hora.":".substr($hora,3,2).":00";					
				}
			break;
		}
		
		return $hora_formateada;
		
	}//Fin de la funcion modHora24($time)
	
	//Esta funci�n regresa el mes actual en Espa�ol
	function obtenerMesActual(){
		ini_set("date.timezone","America/Mexico_City");		
		//Obtener la Fecha Actual
		$date = getdate();
		$mes = "";
		//Identificar mes
		switch($date['month']){
			case "January":		$mes="Enero";		break;
			case "February":	$mes="Febrero";		break;
			case "March":		$mes="Marzo";		break;			
			case "April":		$mes="Abril";		break;
			case "May":			$mes="Mayo";		break;
			case "June":		$mes="Junio";		break;
			case "July":		$mes="Julio";		break;
			case "August":		$mes="Agosto";		break;
			case "September":	$mes="Septiembre";	break;
			case "October":		$mes="Octubre";		break;
			case "November":	$mes="Noviembre"; 	break;
			case "December": 	$mes="Diciembre";	break;
		}
		return $mes;
	}
	
	
	//Esta funci�n regresa el Numero del mes correspondiente al nombre del mes pasado como parametro
	function obtenerNumMes($nomMes){
		$mes = "";
		//Identificar mes
		switch($nomMes){
			case "ENERO":		$mes="01";		break;
			case "FEBRERO":		$mes="02";		break;
			case "MARZO":		$mes="03";		break;			
			case "ABRIL":		$mes="04";		break;
			case "MAYO":		$mes="05";		break;
			case "JUNIO":		$mes="06";		break;
			case "JULIO":		$mes="07";		break;
			case "AGOSTO":		$mes="08";		break;
			case "SEPTIEMBRE":	$mes="09";		break;
			case "OCTUBRE":		$mes="10";		break;
			case "NOVIEMBRE":	$mes="11"; 		break;
			case "DICIEMBRE": 	$mes="12";		break;
		}
		return $mes;
	}
	
	
	//Esta funcion regresa la diferencia de dias que hay entre 2 fechas, las recibe en formato YYYY-mm-dd
	function restarFechas($fecha1,$fecha2){
		//defino fecha 1
		$ano1 = substr($fecha1,0,4);
		$mes1 = substr($fecha1,5,2);
		$dia1 = substr($fecha1,8,2);
		
		//defino fecha 2
		$ano2 = substr($fecha2,0,4);
		$mes2 = substr($fecha2,5,2);
		$dia2 = substr($fecha2,8,2);
		
		//calculo timestamp de las dos fechas
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
		$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);
		
		//resto a una fecha la otra
		$segundos_diferencia = $timestamp1 - $timestamp2;
		//echo $segundos_diferencia;
		
		//convierto segundos en d�as
		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		
		//obtengo el valor absoulto de los d�as (quito el posible signo negativo)
		$dias_diferencia = abs($dias_diferencia);
		
		//quito los decimales a los d�as de diferencia
		$dias_diferencia = floor($dias_diferencia);
		
		return $dias_diferencia;
	}
	
	
	/*Esta funcion Ordena los meses incluidos en el arreglo que se recibe como Parametro*/
	function ordenarMeses($meses){
		//Arreglo para Almacenar los meses ordenados
		$mesesOrdenados = array();
		
		//convertir los Meses a Mayusculas
		foreach($meses as $ind => $mes)
			$meses[$ind] = strtoupper($mes);
	
		
		foreach($meses as $ind => $mes){
			switch($mes){		
				case "ENERO": 		$mesesOrdenados[0] = $mes; 		break;
				case "FEBRERO": 	$mesesOrdenados[1] = $mes; 		break;
				case "MARZO": 		$mesesOrdenados[2] = $mes; 		break;
				case "ABRIL": 		$mesesOrdenados[3] = $mes; 		break;
				case "MAYO": 		$mesesOrdenados[4] = $mes; 		break;
				case "JUNIO": 		$mesesOrdenados[5] = $mes; 		break;
				case "JULIO": 		$mesesOrdenados[6] = $mes; 		break;
				case "AGOSTO": 		$mesesOrdenados[7] = $mes; 		break;
				case "SEPTIEMBRE": 	$mesesOrdenados[8] = $mes; 		break;
				case "OCTUBRE": 	$mesesOrdenados[9] = $mes; 		break;
				case "NOVIEMBRE": 	$mesesOrdenados[10] = $mes; 	break;
				case "DICIEMBRE": 	$mesesOrdenados[11] = $mes; 	break;
			}
		}
		
		//Convertir la Primera letra de Cada Mes en May�scula
		foreach($mesesOrdenados as $ind => $mes)
			$mesesOrdenados[$ind] = ucfirst(strtolower($mes)); 
			
		//Ordenar el Arreglo de Acuerdo a las Claves
		ksort($mesesOrdenados);
		
		//Retornar un arreglo con los meses ordenados
		return $mesesOrdenados;
	}//Cierre de la funcion ordenarMeses
	
	
	/*Esta funcion Ordena los meses incluidos en el arreglo que se recibe como Parametro*/
	function ordenarMesesClaves($meses){
		//Arreglo para Almacenar los meses ordenados
		$mesesOrdenados = array();	
		
		foreach($meses as $mes => $valor){
			switch($mes){		
				case "ENERO": 		$mesesOrdenados["ENERO"] = 0; 		break;
				case "FEBRERO": 	$mesesOrdenados["FEBRERO"] = 1; 	break;
				case "MARZO": 		$mesesOrdenados["MARZO"] = 2; 		break;
				case "ABRIL": 		$mesesOrdenados["ABRIL"] = 3; 		break;
				case "MAYO": 		$mesesOrdenados["MAYO"] = 4; 		break;
				case "JUNIO": 		$mesesOrdenados["JUNIO"] = 5; 		break;
				case "JULIO": 		$mesesOrdenados["JULIO"] = 6; 		break;
				case "AGOSTO": 		$mesesOrdenados["AGOSTO"] = 7; 		break;
				case "SEPTIEMBRE": 	$mesesOrdenados["SEPTIEMBRE"] = 8; 	break;
				case "OCTUBRE": 	$mesesOrdenados["OCTUBRE"] = 9; 	break;
				case "NOVIEMBRE": 	$mesesOrdenados["NOVIEMBRE"] = 10; 	break;
				case "DICIEMBRE": 	$mesesOrdenados["DICIEMBRE"] = 11; 	break;
			}
		}
					
		//Ordenar el arreglo de acuerdo a los valores manteniendo la relacion con las claves
		asort($mesesOrdenados);
		
		//Regresar el contenido original del arreglo antes de ser ordenado
		foreach($mesesOrdenados as $mes => $valor)
			$mesesOrdenados[$mes] = array();
		
		//Retornar un arreglo con los meses ordenados
		return $mesesOrdenados;
	}//Cierre de la funcion ordenarMeses
	
	
	/*Ordenar un Arreglo con Fechas*/
	function ordenarArregloFechas($arrFechas){						
		$tempFechas = array();
		//Convertir cada fecha en dias
		foreach($arrFechas as $key => $value){
			$seccFecha = split("/",$value);
			//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,a�o)
			$fecha_enDias=gregoriantojd ($seccFecha[1], $seccFecha[0], $seccFecha[2]);
			$tempFechas[] = $fecha_enDias;
		}
		//Ordenar el Arreglo convertido en dias
		sort($tempFechas);
		//Convertir los dias en fecha en el formato dd/mm/aaaa
		$arrFechas = array();
		foreach($tempFechas as $key => $value){
			//Cambiar la fecha Juliana a Gregoriana dd/mm/aaaa
			$arrFechas[] = formatFecha(jdtogregorian($value));
		}			
		return $arrFechas;
	}			
	
	
	//Esta funcion recibe la fecha en formato m/d/aaaa y la regresa eb formato dd/mm/aaaa
	function formatFecha($fecha){
		$partes = split("/",$fecha);
		if(intval($partes[0])<10) $partes[0] = "0".$partes[0];
		if(intval($partes[1])<10) $partes[1] = "0".$partes[1];		
		return $partes[1]."/".$partes[0]."/".$partes[2];
	}
	
	
	//Funci�m que permite saber el numero de dias que contiene cada mes, el mes en 2 digitos y el a�o en 4
	function diasMes($mes, $anio){
		$dias = 0;
		
		//Identificar mes y asignar numero de dias perteneciente a el
		switch($mes){
			case "01": $dias = 31;	break;	
			case "02"://Verificamos si el a�o es bisiesto para enviar el numero de dias correspondiente			
				if((($anio%100!=0)&&($anio%400==0))||(($anio%100)&&($anio%4==0)))
					$dias = 29;			
				else
					$dias = 28;			
			break;
			case "03": $dias = 31;	break;	
			case "04": $dias = 30;	break;	
			case "05": $dias = 31;	break;	
			case "06": $dias = 30;	break;	
			case "07": $dias = 31;	break;	
			case "08": $dias = 31;	break;	
			case "09": $dias = 30;	break;	
			case "10": $dias = 31;	break;	
			case "11": $dias = 30;	break;	
			case "12": $dias = 31;	break;			
		}
		
		return $dias;
	}//Cierre diasMes($mes, $anio)
	
	
	/*Esta funcion regresa el nombre del dia de la Semana que corresponde a la fecha proporcionada en formato aaaa-mm-dd*/
	function obtenerNombreDia($fecha){
		//Separar la fecha mediante el guion '-'
		$fechaSeparada = split("-",$fecha);
		//Obtener el nombre del Dia de la semana correspondiente a la Fecha proporcionada
		$diaIngles = date("l", mktime(00, 00, 00, $fechaSeparada[1], $fechaSeparada[2], $fechaSeparada[0]));
		
		$diaEsp = "";
		//Obtener el d�a en Espa�ol
		switch($diaIngles){
			case "Monday":
				$diaEsp = "Lunes";
			break;
			case "Tuesday":
				$diaEsp = "Martes";
			break;
			case "Wednesday":
				$diaEsp = "Mi&eacute;rcoles";
			break;
			case "Thursday":
				$diaEsp = "Jueves";
			break;
			case "Friday":
				$diaEsp = "Viernes";
			break;
			case "Saturday":
				$diaEsp = "S&aacute;bado";
			break;
			case "Sunday":
				$diaEsp = "Domingo";
			break;
		}
		
		//Regresar el d�a correspondiente a la fecha en Espa�ol
		return $diaEsp;
	}//Cierre de la funcion obtenerNombreDia($fecha)
	
	/*Esta funcion regresa el nombre del dia de la Semana que corresponde a la fecha proporcionada en formato aaaa-mm-dd*/
	function obtenerNombreDia2($fecha){
		//Separar la fecha mediante el guion '-'
		$fechaSeparada = split("-",$fecha);
		//Obtener el nombre del Dia de la semana correspondiente a la Fecha proporcionada
		$diaIngles = date("l", mktime(00, 00, 00, $fechaSeparada[1], $fechaSeparada[2], $fechaSeparada[0]));
		
		$diaEsp = "";
		//Obtener el d�a en Espa�ol
		switch($diaIngles){
			case "Monday":
				$diaEsp = "Lunes";
			break;
			case "Tuesday":
				$diaEsp = "Martes";
			break;
			case "Wednesday":
				$diaEsp = "Miercoles";
			break;
			case "Thursday":
				$diaEsp = "Jueves";
			break;
			case "Friday":
				$diaEsp = "Viernes";
			break;
			case "Saturday":
				$diaEsp = "Sabado";
			break;
			case "Sunday":
				$diaEsp = "Domingo";
			break;
		}
		
		//Regresar el d�a correspondiente a la fecha en Espa�ol
		return $diaEsp;
	}//Cierre de la funcion obtenerNombreDia($fecha)
	
	/*Esta funcion regresa el nombre completo del mes a partir de sus primeras tres letras*/
	function obtenerNombreCompletoMes($siglas){
		
		$nomMes = "";		
		switch($siglas){
			case "ENE":		$nomMes = "ENERO";		break;
			case "FEB":		$nomMes = "FEBRERO";	break;
			case "MAR":		$nomMes = "MARZO";		break;
			case "ABR":		$nomMes = "ABRIL";		break;
			case "MAY":		$nomMes = "MAYO";		break;
			case "JUN":		$nomMes = "JUNIO";		break;
			case "JUL":		$nomMes = "JULIO";		break;
			case "AGO":		$nomMes = "AGOSTO";		break;
			case "SEP":		$nomMes = "SEPTIEMBRE";	break;
			case "OCT":		$nomMes = "OCTUBRE";	break;
			case "NOV":		$nomMes = "NOVIEMBRE";	break;
			case "DIC":		$nomMes = "DICIEMBRE";	break;
		}		
		//Regresar el mes correspondiente
		return $nomMes;
	}//Cierre de la funcion obtenerNombreCompletoMes($siglas)
	
	//Funcion que permite obtener el mes anterior al seleccionado
	function obtenerMesAnterior($mes){
		
		$mesAnt = "";		
		switch($mes){
			case "ENERO":		$mesAnt = "DICIEMBRE";		break;
			case "FEBRERO":		$mesAnt = "ENERO";			break;
			case "MARZO":		$mesAnt = "FEBRERO";		break;
			case "ABRIL":		$mesAnt = "MARZO";			break;
			case "MAYO":		$mesAnt = "ABRIL";			break;
			case "JUNIO":		$mesAnt = "MAYO";			break;
			case "JULIO":		$mesAnt = "JUNIO";			break;
			case "AGOSTO":		$mesAnt = "JULIO";			break;
			case "SEPTIEMBRE":	$mesAnt = "AGOSTO";			break;
			case "OCTUBRE":		$mesAnt = "SEPTIEMBRE";		break;
			case "NOVIEMBRE":	$mesAnt = "OCTUBRE";		break;
			case "DICIEMBRE":	$mesAnt = "NOVIEMBRE";		break;
		}		
		//Regresar el mes correspondiente
		return $mesAnt;
	}//Cierre de la funcion obtenerMesAnterior($s

	//Funcion que regresa una Fecha con X dias sumados
	function sumarDiasFecha($fecha,$dias){
		//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
		$seccFecha = split("-",$fecha);
		//Obtenerla en Dias
		$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + $dias;
		//Convertirla de Fecha Juliana a Gregoriana
		$fecha=jdtogregorian($fecha_enDias);
		//Formatear la Fecha a dd/mm/yyyy
		$fecha=formatFecha($fecha);
		//Regresarla a formato legible por MySQL
		$fecha=modFecha($fecha,3);
		//Regresar la fecha
		return $fecha;
	}//function sumarDiasFecha($fecha,$dias)
	
	//Funcion que calcular la fecha de nacimiento registrada en el RFC de los trabajadores
	function calcularFecha($aammdd){
		$anio=substr($aammdd,0,2);
		$mes=substr($aammdd,2,2);
		$dia=substr($aammdd,4,2);
		
		if ($anio == 0 || $anio == '0' || ($anio > 0 && $anio < 40)) {
			return "20".$anio."-".$mes."-".$dia;
		} else {
			return "19".$anio."-".$mes."-".$dia;
		}
		
		
	}
	
	function obtenerNombreMeses($numMes){
		$mes = "";
		//Identificar mes
		switch($numMes){
			case "01":		$mes="ENERO";			break;
			case "02":		$mes="FEBRERO";			break;
			case "03":		$mes="MARZO";			break;			
			case "04":		$mes="ABRIL";			break;
			case "05":		$mes="MAYO";			break;
			case "06":		$mes="JUNIO";			break;
			case "07":		$mes="JULIO";			break;
			case "08":		$mes="AGOSTO";			break;
			case "09":		$mes="SEPTIEMBRE";		break;
			case "10":		$mes="OCTUBRE";			break;
			case "11":		$mes="NOVIEMBRE"; 		break;
			case "12": 		$mes="DICIEMBRE";		break;
		}
		return $mes;
	}
?>