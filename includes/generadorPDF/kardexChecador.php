<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include("../op_operacionesBD.php");

class PDF extends FPDF{

	function Header(){
		//Logo
	    $this->Image('logo-clf.jpg',12,6,30);
	    //Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		$this->SetTextColor(0, 0, 255);
	    //Move to the right
	    $this->Cell(60);
	    //Title
	    $this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE �CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.� PROHIBIDA SU REPRODUCCI�N TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		//$this->Cell(70,50,'F. 7.4.0 - 01 REQUISICI�N DE COMPRAS',0,0,'C');
		$this->Cell(70,50,'CONTROL DIARIO DE ASISTENCIA',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Bele�a, Fresnillo Zac.',0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
		//Line break
	    $this->Ln(45);
		parent::Header();
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//SUB TI
		//$this->Cell(0,15,'       Fecha Emisi�n:                                               No. de Revisi�n:                                               Fecha de Revisi�n:',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'P�gina '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-17);
		//$this->Cell(0,15,'            Abr - 09'.'                                                                '.'02'.'                                                                 '.'   May - 10',0,0,'L');
		$this->SetY(-20);
		//$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 01',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	//Recuperar las variables que vienen como parametros en el GET
	$fechaIini=modFecha($_GET["fechaI"],3);
	$fechaFin=modFecha($_GET["fechaF"],3);
	$tipo=$_GET["tipo"];
	$criterio=$_GET["criterio"];
	$fechaRep=modFecha(date("Y-m-d"),7);
	$cantDias=0;
	
	$fechaI = sumarDiaFecha($_GET["fechaI"],0);
	//Sumar dias a la fecha de Fin y usar el formato correspondiente para la consulta
	$fechaF = sumarDiaFecha($_GET["fechaF"],1);
	//Recuperar el �rea seleccionada siempre y cuando este definida
	if($tipo == "area"){
		$area=$criterio;
		//Verificar si el �rea es una en especifico o se refiere a todas
		if ($area=="TODOS"){
			//Sentencia SQL para extraer a los Trabajadores del �rea Seleccionada
			$sql = "SELECT T1.Userid, T1.name, T2.CheckTime, T2.Sensorid 
					FROM Userinfo AS T1 
					INNER JOIN Checkinout AS T2 
					ON T1.Userid = T2.Userid 
					WHERE T2.CheckTime BETWEEN #$fechaI#
					AND #$fechaF#
					ORDER BY T1.name, T2.CheckTime";
		}
		else{
			//Sentencia SQL para extraer a los Trabajadores del �rea Seleccionada
			$sql = "SELECT T1.Userid, T1.name, T2.CheckTime, T2.Sensorid 
					FROM Userinfo AS T1 
					INNER JOIN Checkinout AS T2 
					ON T1.Userid = T2.Userid 
					WHERE T2.CheckTime BETWEEN #$fechaI#
					AND #$fechaF# 
					AND T1.DeptID = $area
					ORDER BY T1.name, T2.CheckTime";
		}
	}
	else{//Consulta usando Filtro por Nombre de Trabajador
		$nombre=$criterio;
		//Conectar a la BD de Recursos
		$conn=conecta("bd_recursos");
		//Sentencia SQL para extraer a los Trabajadores del �rea Seleccionada
		$stm_sql = "SELECT id_empleados_empresa FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nombre'";
		//Cuando exista el filtro de trabajador, modificar el valor de la variable de $incidenciaFechas para poder asignar la incidencia por todo el periodo
		$rs=mysql_query($stm_sql);
		//Verificar que existan registos
		if($datos=mysql_fetch_array($rs)){
			$sql = "SELECT T1.Userid, T1.name, T2.CheckTime, T2.Sensorid 
					FROM Userinfo AS T1 
					INNER JOIN Checkinout AS T2 
					ON T1.Userid = T2.Userid 
					WHERE T2.CheckTime BETWEEN #$fechaI#
					AND #$fechaF# 
					AND T1.Userid = '$datos[id_empleados_empresa]'
					ORDER BY T1.name, T2.CheckTime";
		}
		mysql_close($conn);
	}
	
	$fecha_temp = "0";
	$id_emp = "A";
	$hora_ini = "00:00:00";
	$hora_fin = "00:00:00";
	
	//Conectarse a la BD de Recursos
	$conn=conecta("bd_recursos");
	$reg=0;
	$conn_access = odbc_connect("EasyClocking","","");
	/**************************************************************************************************************/
	/***************************************DATOS GENERALES DEL FORMATO********************************************/
	/**************************************************************************************************************/
	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	$pdf->SetFont('Arial','',11);//Tipo de Letra
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->SetDrawColor(0, 0, 255);//Color de los Bordes
	$pdf->Cell(150,6,'Fecha: ',0,0,'R');//Etiqueta Fecha
	$pdf->SetFont('Arial','B',11);//Para poner la linea y fecha en Negritas
	$pdf->Cell(40,6,$fechaRep,'B',1,'R');//Fecha del Reporte
	//Encabezado General
	$pdf->SetFont('Arial','',11);//Para quitar la propiedad Negritas de los encabezados
	$pdf->Cell(85,6,'Reporte del ',0,0,'R');//Espacio Vacio para Sangria	
	$pdf->SetFont('Arial','U',11);//Para quitar la propiedad Subrayado de los encabezados
	$pdf->Cell(20,6,$_GET['fechaI'],0,0,'C');//Espacio Vacio para Sangria
	$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
	$pdf->Cell(5,6,' al ',0,0,'C');//Espacio Vacio para Sangria
	$pdf->SetFont('Arial','U',11);//Para quitar la propiedad Subrayado de los encabezados
	$pdf->Cell(80,6,$_GET['fechaF'],0,1,'L');//Espacio Vacio para Sangria
	$pdf->SetFont('Arial','B',11);//Para quitar la propiedad Negritas de los encabezados
	if($tipo == "area")
		$pdf->Cell(190,6,strtoupper("DEPARTAMENTO: ".obtenerDepto($criterio, $conn_access)),0,0,'C');//Espacio Vacio para Sangria
	else
		$pdf->Cell(190,6,strtoupper("EMPLEADO: ".$criterio),0,0,'C');//Espacio Vacio para Sangria
	
	if($rs_access = odbc_exec ($conn_access,$sql)){
		$cont = 1;
		$total_ht = 0;
		$total_he = 0;
		
		$num_reg = 0;
		while(odbc_fetch_array($rs_access)){
			$num_reg++;
		}
		
		for($i=1; $i<=$num_reg; $i+=2){
			$datos = odbc_fetch_array($rs_access,$i);
			
			if($id_emp != $datos["Userid"]){
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->SetFont('Arial','B',10);//Para poner la linea y fecha en Negritas
				$pdf->Cell(20,6,"Empleado: ",'',0,'L');//Fecha del Reporte
				$pdf->Cell(100,6,$datos["name"],'',0,'L');//Fecha del Reporte
				$pdf->Cell(25,6,"ID Empleado: ",'',0,'L');//Fecha del Reporte
				$pdf->Cell(45,6,$datos["Userid"],'',0,'L');//Fecha del Reporte
				
				//Encabezado de la Tabla
				//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->SetFillColor(217,217,217);
				$pdf->SetFont('Arial','B',7);//Para poner la linea y fecha en Negritas
				$pdf->Cell(22,5,'DIA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'FECHA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'ENTRADA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,'LUGAR',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'SALIDA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,'LUGAR',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(30,5,'HORAS TRABAJADAS',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(20,5,'TIEMPO EXTRA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
			}
			
			$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
			$pdf->SetFont('Arial','',7);//Definir el tipo y tama�o de la letra
			$pdf->Cell(22,5,obtenerNombreDia2(substr($datos['CheckTime'],0,10)),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			$pdf->Cell(25,5,substr($datos['CheckTime'],0,10),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			$pdf->Cell(25,5,substr($datos['CheckTime'],-8),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			
			if($datos["Sensorid"] == "34002474"){
				$pdf->Cell(22,5,"Caseta 2",1,0,'C',0);//Etiqueta de la Clave en el encabezado
			}
			else if($datos["Sensorid"] == "34002473"){
				$pdf->Cell(22,5,"Caseta 1",1,0,'C',0);//Etiqueta de la Clave en el encabezado
			}
			
			$hora_ini = substr($datos['CheckTime'],-8);
				
			$datos_temp = odbc_fetch_array($rs_access,$i+1);
				
			if(substr($datos['CheckTime'],0,10) == substr($datos_temp['CheckTime'],0,10) && $datos_temp["Userid"] == $datos["Userid"]){
				$hora_fin = substr($datos_temp['CheckTime'],-8);
				$pdf->Cell(25,5,substr($datos_temp['CheckTime'],-8),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			
				if($datos_temp["Sensorid"] == "34002474"){
					$pdf->Cell(22,5,"Caseta 2",1,0,'C',0);//Etiqueta de la Clave en el encabezado
				}
				else if($datos_temp["Sensorid"] == "34002473"){
					$pdf->Cell(22,5,"Caseta 1",1,0,'C',0);//Etiqueta de la Clave en el encabezado
				} else {
					$pdf->Cell(22,5,"",1,0,'C',0);//Etiqueta de la Clave en el encabezado
				}
			} else {
				$hora_fin = "00:00:00";
				$pdf->Cell(25,5,"",1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,"",1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$i--;
			}
			
			if($hora_fin == "00:00:00"){
				$dif = "00:00:00";
			}
			else{
				$dif = diferenciaHoras($hora_ini,$hora_fin);
			}
			if($dif > "08:00:00"){
				$extras = diferenciaHoras("08:00:00",$dif);
				$horas_trab = "08:00:00";
			}
			else{
				$extras = "00:00:00";
				$horas_trab = $dif;
			}
			$pdf->Cell(30,5,number_format(horaDecimal($horas_trab),2,".",","),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			$pdf->Cell(20,5,number_format(horaDecimal($extras),2,".",","),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			
			$total_ht += number_format(horaDecimal($horas_trab),2,".",",");
			$total_he += number_format(horaDecimal($extras),2,".",",");
			
			/*if($datos["Userid"] != $datos_temp["Userid"]){
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->SetFont('Arial','B',10);//Para poner la linea y fecha en Negritas
				$pdf->Cell(23,6,"ID Empleado: ",'',0,'L');//Fecha del Reporte
				$pdf->Cell(96,6,$datos["Userid"],'',0,'L');//Fecha del Reporte
				$pdf->Cell(22,6,"TOTALES: ","",0,'R');//Fecha del Reporte
				$pdf->Cell(30,6,$total_ht,"",0,'C');//Fecha del Reporte
				$pdf->Cell(20,6,$total_he,"",0,'C');//Fecha del Reporte
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
			}*/
			$id_emp = $datos["Userid"];
		}
	}
	
	$pdf->SetAuthor("CLF Fresnillo");
	$pdf->SetTitle("KARDEX");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("Kardex Detallado");
	$pdf->SetKeywords("CLF. \nDocumento Generado a Partir de la Consulta de Kardex Recolectada del ".$_GET["fechaI"]." al ".$_GET["fechaF"]." en el SISAD");
	$num_req='KardexDetallado.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($num_req,"F");
	header('Location: '.$num_req);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	/***********************************************************************************************/
	/**************************FUNCIONES USADAS EN LA REQUISICION***********************************/
	/***********************************************************************************************/	
	//Esta funci�n elimina los archivos PDF que se hayan generado anteriormente
	function borrarArchivos(){
		//Borrar los ficheros temporales
		$t=time();
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.pdf'){
				if($t-filemtime($file)>1)
					@unlink($file);
			}
		}
		closedir($h);
	}

	//Segun el valor de $cont, obtener el nombre de cada mes
	function obtenerNombreMes($cont){
		switch($cont){
			case 1:
				$mes="ENERO";
				break;
			case 2:
				$mes="FEBRERO";
				break;
			case 3:
				$mes="MARZO";
				break;
			case 4:
				$mes="ABRIL";
				break;
			case 5:
				$mes="MAYO";
				break;
			case 6:
				$mes="JUNIO";
				break;
			case 7:
				$mes="JULIO";
				break;
			case 8:
				$mes="AGOSTO";
				break;
			case 9:
				$mes="SEPTIEMBRE";
				break;
			case 10:
				$mes="OCTUBRE";
				break;
			case 11:
				$mes="NOVIEMBRE";
				break;
			case 12:
				$mes="DICIEMBRE";
				break;
		}
		return $mes;
	}
	
	//Funcion que obtiene las 3 primeras letras de los nombres de los D�as de las fechas seleccionadas
	function obtenerDia($fecha,$contDias){
		//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
		$seccFecha = split("-",$fecha);
		//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,a�o) y sumar 1 d�a
		$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + $contDias;
		//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
		$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
		//Obtener el nombre del Dia actual en Mayusculas
		$dia=strtoupper(obtenerNombreDia($fechaActual));
		$dia=str_replace("&AACUTE;","A",$dia);
		$dia=str_replace("&EACUTE;","E",$dia);
		return substr($dia,0,3);
	}//Fin de function obtenerDia($fecha,$contDias)
	
	//Funcion que obtiene la hora de checada en la fecha seleccionada segun la Entrada o Salida correspondiente
	function obtenerChecada($fecha,$contDias,$rfc,$tipo){
		//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
		$seccFecha = split("-",$fecha);
		//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,a�o) y sumar 1 d�a
		$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + $contDias;
		//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
		$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
		if($tipo=="in")
			//Sentencia SQL
			$sql="SELECT hora_checada FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada='$fechaActual' AND estado!='SALIDA' ORDER BY hora_checada";
		else
			//Sentencia SQL
			$sql="SELECT hora_checada FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada='$fechaActual' AND estado='SALIDA' ORDER BY hora_checada";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar el resultado
		if($datos=mysql_fetch_array($rs))
			$hora=$datos["hora_checada"];
		else
			$hora="";
		//Retornar el valor de la Hora
		return $hora;
	}//Fin de obtenerChecada($fecha,$contDias,$rfc,$tipo)
		
	/*Esta funci�n recupera los datos del Kardex del empleado indicado*/
	/*FUNCION REUTILIZADA SEGUN EL C�DIGO EN op_registrarNominaInterna.php*/
	function obtenerKardexEmpleado($rfc_empleado,$fechaInicio,$fechaFin,$cantDias,$jornada){
		/*Este arreglo guardar� los datos obtenidos del Kardex, colocando la fecha correspondiente a cada registro como clave y esta a su vez contendra por cada fecha
		  la Incidencia, las Horas Trabajados y las Horas Extra y como espacio fuera de fechas tendr� los d�as trabajados*/
		$kardex = array();
		//Definir Fecha de Inicio, como Fecha Actual
		$fechaActual = $fechaInicio;		
		//Rellenar el Arreglo con registros vacios, los cuales ser�n complementados con la consulta realizada a la BD
		for($i=0;$i<$cantDias;$i++){
			//Colocar en cada fecha un arreglo que contenga la incidencia del dia, las horas trabajadas y las horas extra que pueda tener en la fecha
			$kardex[$fechaActual] = array("incidencia"=>"","horasTrabajadas"=>"","horasExtra"=>"");
			//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
			$seccFecha = split("-",$fechaActual);
			//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,a�o) y sumar 1 d�a
			$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + 1;
			//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
			$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
		}//Cierre for($i=0;$i<$cantDias;$i++)
		//Registrar Dias Trabajados
		$kardex['diasTrabajados'] = 0;						
		//Variables para obtener la cantidad de Dias Trabajados
		$diasTrabajados = 0;
		//$factorSeptimoDia = 1.1669;
		$factorSeptimoDia = 1;
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
					$diasTrabajados++;
				}//Cierre if($datos['estado']!="SALIDA")
				//Calcular la cantidad de horas trabajadas por Jornada Laboral, as� como las horas extra
				if($datos['estado']=="SALIDA"){
					//Obtener la checada de entrada de la misma fecha de la salida
					$rs_checadaEntrada = mysql_query("SELECT * FROM checadas WHERE empleados_rfc_empleado = '$rfc_empleado' 
													AND fecha_checada = '$fechaActual' AND estado != 'SALIDA' AND hora_checada < '$datos[hora_checada]'");
					//S� hay datos una checada de entrada registrada en la misma fecha de la SALIDA procedemos a obtener la cantidad de horas trabajadas
					if($d_checEnt=mysql_fetch_array($rs_checadaEntrada)){
						//Obtener la diferencia entre las Hora de SALIDA y la hora de ENTRADA con MySQL
						$datos_diff = mysql_fetch_array(mysql_query("SELECT TIMEDIFF(SUBSTRING('$datos[hora_checada]',1,5),SUBSTRING('$d_checEnt[hora_checada]',1,5)) AS diferencia"));
						$horasTrabajadas = intval(substr($datos_diff['diferencia'],0,2));
						//Guardar las Horas Trabjadas y las Horas Extra
						$kardex[$fechaActual]["horasTrabajadas"] = $horasTrabajadas;
						$hrsExtra = $horasTrabajadas - $jornada;
						if($hrsExtra<0) $hrsExtra = 0;
						$kardex[$fechaActual]["horasExtra"] = $hrsExtra;
					}//Cierre if($datos_checadaEntrada=mysql_fetch_array($rs_checadaEntrada))
					else{//S� no hay checada de entrada registrada en el mismo d�a, procedemos a buscar en un d�a anterior
						//Almacenar la Fecha Actual como Fecha Anterior para restarle un d�a y buscar la entrada un d�a antes de la fecha de salida
						$fechaAnterior = $fechaActual;
						//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
						$seccFecha = split("-",$fechaAnterior);
						//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,a�o) y restar 1 d�a
						$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) - 1;
						//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
						$fechaAnterior = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
						//Sentencia SQL para obtener la checada de entrada de un d�a anterior
						$rs_checadaEntrada = mysql_query("SELECT * FROM checadas WHERE empleados_rfc_empleado = '$rfc_empleado' 
						AND fecha_checada = '$fechaAnterior' AND estado != 'SALIDA'");
						//Verificar si la ENTRADA se encuentra en una fecha anterior a la de la SALIDA
						if($d_checEnt=mysql_fetch_array($rs_checadaEntrada)){
							$horaChecada=substr($datos["hora_checada"],0,5);
							$horaEntrada=substr($d_checEnt["hora_checada"],0,5);
							//Obtener la diferencia entre las Hora de SALIDA y la hora de ENTRADA con MySQL, colocar tambien la fecha ya que son d�as diferentes
							$sql_stm_diff = "SELECT TIMEDIFF('$fechaActual $horaChecada','$fechaAnterior $horaEntrada') AS diferencia";
							$datos_diff = mysql_fetch_array(mysql_query($sql_stm_diff));
							$horasTrabajadas = intval(substr($datos_diff['diferencia'],0,2));
							//Guardar las Horas Trabjadas y las Horas Extra en la fecha en la que esta registrada la Entrada($fechaAnterior)
							$kardex[$fechaAnterior]["horasTrabajadas"] = $horasTrabajadas;
							$hrsExtra = $horasTrabajadas - $jornada;
							if($hrsExtra<0) $hrsExtra = 0;
							$kardex[$fechaAnterior]["horasExtra"] = $hrsExtra;
						}//Cierre if($datos_checadaEntrada=mysql_fetch_array($rs_checadaEntrada))
					}//Cierre ELSE if($datos_checadaEntrada=mysql_fetch_array($rs_checadaEntrada))
				}//Cierre if($datos['estado']=="SALIDA")
			}while($datos=mysql_fetch_array($rs));//Cierre for($i=0;$i<$cantDias;$i++)
			//Obtener la cantidad final de d�as trabajados
			$kardex['diasTrabajados'] = intval($diasTrabajados * $factorSeptimoDia);
		}//Cierre if($datos=mysql_fetch_array($rs))
		//Retornar el Arreglo con los datos encontrados (Incidencias, Horas Trabajadas, Horas Extra y Dias Trabajados)
		return $kardex;
	}//Cierre de la funci�n obtenerKardexEmpleado($rfc_empleado,$fechaInicio,$fechaFin,$cantDias)
	
	/*Esta funcion divide una cadena en la cantidad exacta de Caracteres o menor de acuerdo al acomodo de palabras*/
	function cortarCadena($cadena,$carsPorLinea){	
		//Variable para Almacenar la Nueva Cadena
		$datosCadena = array("cantRenglones"=>0);
		//Obtener el Tama�o de la Cadena Original
		$tamCadena = strlen($cadena);
		//Separar la Cadena Original en un Arreglo de Caracteres, donde cada posici�n del Arreglo contiene un solo caracter de la cadena original
		$caracteres = str_split($cadena);
			
		//Si el tama�o de la Cadena excede la cantidad de caracteres especificada, proceder a separarla
		if($tamCadena>$carsPorLinea){
			//Variables para controlar el recorrido de la cadena caracter x caracter
			$cantCaracteres = 0;
			$carInicial = 0;
			$posBlank = 0;
			for($i=0;$i<$tamCadena;$i++){
				//Incremenetar el contado de caracteres
				$cantCaracteres++;				
				
				//Obtener cada Caracter de la cadena
				$carActual = $caracteres[$i];
				
				//Guardar la Posicion del ultimo espacio en blanco encontrado
				if($carActual==" ")
					$posBlank = $i;
				
				//Si la cantidad de caracteres recorridos es igual a la cantidad de caracteres por linea, proceder a cortarla
				if($cantCaracteres==$carsPorLinea){					
					//Si el caracter actual es un espacio en blanco cambiar el valor de la variable '$posBlank'
					if($carActual==" ")
						$posBlank = $i;
					
					//Incrementar el contador de renglones
					$datosCadena["cantRenglones"]++;
					//Guardar cada Trozo de la cadena en una posicion dada del arreglo
					$datosCadena[] = substr($cadena,$carInicial,($posBlank-$carInicial));																				
					
					//El siguiente caracter de la posici�n del ultimo espacio en blanco encontrado se vuelve la posici�n inicial
					$carInicial = $posBlank+1;
					
					//Colocar el contador del Ciclo for a la posici�n del ultimo espacio en blanco encontrado
					$i = $posBlank;
									
					//Resetear el contador de caracteres para no exceder el tama�o del renglon
					$cantCaracteres = 0;										
				}				
						
			}//Cierre for($i=0;$i<$tamCadena;$i++)
			
			//Termindao el Ciclo, verificar si la cantidad de caracteres es mayor a cero y agregar el ultimo Fragmento a al cadena
			if($cantCaracteres>1){
				//Incrementar el contador de renglones
				$datosCadena["cantRenglones"]++;
				//Guardar el ultimo Trozo de la cadena en una posicion dada del arreglo
				$datosCadena[] = substr($cadena,$carInicial,strlen($cadena)-1);
			}			
			
		}//Cierre if($tamCadena>$carsPorLinea)
		else{//La cadena original queda intacta			
			$datosCadena["cantRenglones"]++;
			//Guardar la cadena origial tal cual en la posicion del arreglo
			$datosCadena[] = $cadena;
		}
		
		
		return $datosCadena;			
	}//Cierre cortarCadena($cadena,$carsPorLinea)
	
	function sumarDiaFecha($fecha,$dias){
		list($day,$mon,$year) = explode('/',$fecha);
		return date('m/d/Y',mktime(0,0,0,$mon,$day+$dias,$year));
	}
	
	function numRegEmpleado($id_emp,$fecha_ini,$fecha_fin,$conexion){
		$sql2 = "SELECT T2.CheckTime 
				FROM Userinfo AS T1 
				INNER JOIN Checkinout AS T2 
				ON T1.Userid = T2.Userid 
				WHERE T2.CheckTime BETWEEN #$fecha_ini#
				AND #$fecha_fin# 
				AND T1.Userid = '$id_emp'";
				
		if($rs_access2 = odbc_exec ($conexion,$sql2)){
			$reg = 0;
			while($datos2 = odbc_fetch_array($rs_access2)){
				$reg++;
			}
			return $reg;
		}
	}
	
	function obtenerDepto($id_depto, $conexion){
		$sql2 = "SELECT DeptName
				FROM Dept
				WHERE Deptid = $id_depto";
		if($rs_access2 = odbc_exec ($conexion,$sql2)){
			$datos2 = odbc_fetch_array($rs_access2);
			return $datos2["DeptName"];
		}
	}
	
	function diferenciaHoras($inicio,$fin){
		$dif = date("H:i:s", strtotime("00:00:00") + strtotime($fin) - strtotime($inicio));
		return $dif;
	}
	
	function horaDecimal($hora){
		$dec = substr($hora,0,2) + (substr($hora,3,2) / 60);
		return $dec;
	}

?>