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
	    $this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE CONCRETO LANZADO DE FRESNILLO MARCA, PROHIBIDA SU REPRODUCCION TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		//$this->Cell(70,50,'F. 7.4.0 - 01 REQUISICI�N DE COMPRAS',0,0,'C');
		$this->Cell(70,50,'CONTROL DIARIO DE ASISTENCIA',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,utf8_decode('Avenida Enrique Estrada #75, Col. Las Americas, Fresnillo Zac.'),0,0,'C');
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
		$this->Cell(-20,15,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
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
	$id_cuenta = "";

	if( isset($_GET['txt_cuenta']) ){
		if( $_GET['txt_cuenta'] == "MTTO" ){
			$id_cuenta = "LIKE 'CUEN002'";
		}

		else if( $_GET['txt_cuenta'] == "PRODUCCION" ){
			$id_cuenta = "NOT LIKE 'CUEN002'";
		}
	}
	
	$fechaI = sumarDiaFecha($_GET["fechaI"],0);
	//Sumar dias a la fecha de Fin y usar el formato correspondiente para la consulta
	$fechaF = sumarDiaFecha($_GET["fechaF"],1);
	//Recuperar el �rea seleccionada siempre y cuando este definida
	if($tipo == "area"){
		$area=$criterio;
		//Verificar si el �rea es una en especifico o se refiere a todas
		if ($area=="TODOS"){
			$sql = "SELECT T1. * , CONCAT( T2.ape_pat, ' ', T2.ape_mat, ' ', T2.nombre ) AS nombre, T2.id_control_costos, T2.jornada
					FROM `detalle_checador` AS T1
					JOIN empleados AS T2 ON T1.id_empleado = T2.id_empleados_empresa
					WHERE fecha
					BETWEEN '$fechaIini'
					AND '$fechaFin'
					ORDER BY T2.id_empleados_empresa, T1.fecha, T1.hora";
		}
		else{
			$sql = "SELECT T1. * , CONCAT( T2.ape_pat, ' ', T2.ape_mat, ' ', T2.nombre ) AS nombre, T2.id_control_costos, T2.jornada
					FROM `detalle_checador` AS T1
					JOIN empleados AS T2 ON T1.id_empleado = T2.id_empleados_empresa
					WHERE fecha
					BETWEEN '$fechaIini'
					AND '$fechaFin'
					AND T2.id_control_costos LIKE '$area'";
			
			if( $id_cuenta != "" ){
				//$sql .= " AND T2.id_cuentas $id_cuenta";
			}
			
			$sql .=	" ORDER BY T2.id_empleados_empresa, T1.fecha, T1.hora";
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
			$sql = "SELECT T1. * , CONCAT( T2.ape_pat, ' ', T2.ape_mat, ' ', T2.nombre ) AS nombre, T2.id_control_costos, T2.jornada
					FROM `detalle_checador` AS T1
					JOIN empleados AS T2 ON T1.id_empleado = T2.id_empleados_empresa
					WHERE fecha
					BETWEEN '$fechaIini'
					AND '$fechaFin'
					AND T1.id_empleado = '$datos[id_empleados_empresa]'
					ORDER BY T2.id_empleados_empresa, T1.fecha, T1.hora";
		}
		mysql_close($conn);
	}
	
	$fecha_temp = "0";
	$id_emp = "A";
	$hora_ini = "00:00:00";
	$hora_fin = "00:00:00";
	
	//Conectarse a la BD de Recursos
	$conn=conecta("bd_recursos");
	
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
	if($tipo == "area"){
		if($criterio == "TODOS")
			$pdf->Cell(190,6,strtoupper("DEPARTAMENTO: TODOS"),0,0,'C');//Espacio Vacio para Sangria
		else
			$pdf->Cell(190,6,strtoupper("DEPARTAMENTO: ".obtenerDepto($area)),0,0,'C');//Espacio Vacio para Sangria
	}
	
	else
		$pdf->Cell(190,6,strtoupper("EMPLEADO: ".$criterio),0,0,'C');//Espacio Vacio para Sangria
	
	if($rs = mysql_query ($sql)){
		$cont = 1;
		$total_ht = 0;
		$total_he = 0;
		
		$num_reg = 0;
		while($datos = mysql_fetch_array($rs)){
			$empleados[] = $datos;
			$num_reg++;
		}
		
		for($i=0; $i<$num_reg; $i+=2){
			$aux = $i;
			if($id_emp != $empleados[$i]["id_empleado"]){
				/*

				if($empleados[$i]["id_control_costos"] == "CONT005")
					$jornada = "12:00:00";
				else
					$jornada = "08:00:00";

				*/

				//$jornada = $empleados[$i]["jornada"];

				if($empleados[$i]["jornada"] == "10"){
					$jornada = "10:00:00";
				} else if($empleados[$i]["jornada"] == "12") {
					$jornada = "12:00:00";
				} else if($empleados[$i]["jornada"] == "8") {
					$jornada = "08:00:00";
				} else if($empleados[$i]["jornada"] == "9") {
					$jornada = "09:00:00";
				}


				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->SetFont('Arial','B',10);//Para poner la linea y fecha en Negritas
				$pdf->Cell(20,6,"Empleado: ",'',0,'L');//Fecha del Reporte
				$pdf->Cell(100,6,$empleados[$i]["nombre"],'',0,'L');//Fecha del Reporte
				$pdf->Cell(25,6,"ID Empleado: ",'',0,'L');//Fecha del Reporte
				$pdf->Cell(45,6,$empleados[$i]["id_empleado"],'',0,'L');//Fecha del Reporte
				
				//Encabezado de la Tabla
				//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$pdf->SetFillColor(217,217,217);
				$pdf->SetFont('Arial','B',7);//Para poner la linea y fecha en Negritas
				$pdf->Cell(22,5,'DIA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'FECHA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'ENTRADA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,'DIA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'FECHA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,'SALIDA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(30,5,'HORAS TRABAJADAS',1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(20,5,'TIEMPO EXTRA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
			}
			
			$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
			$pdf->SetFont('Arial','',7);//Definir el tipo y tama�o de la letra
			$pdf->Cell(22,5,obtenerNombreDia2($empleados[$i]["fecha"]),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			$pdf->Cell(25,5,$empleados[$i]["fecha"],1,0,'C',0);//Etiqueta de la Clave en el encabezado
			$pdf->Cell(25,5,$empleados[$i]["hora"],1,0,'C',0);//Etiqueta de la Clave en el encabezado

			$hora_ini = $empleados[$i]["hora"];

			$horas_dia =diferenciaHoras($empleados[$i]["fecha"],$empleados[$i]["hora"],$empleados[$i+1]["fecha"],$empleados[$i+1]["hora"]);
				
			if($horas_dia < 14 && $empleados[$i+1]["id_empleado"] == $empleados[$i]["id_empleado"]){
				$hora_fin = $empleados[$i+1]["hora"];
				$pdf->Cell(22,5,obtenerNombreDia2($empleados[$i+1]["fecha"]),1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,$empleados[$i+1]["fecha"],1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,$hora_fin,1,0,'C',0);//Etiqueta de la Clave en el encabezado
			} else {
				$hora_fin = "00:00:00";
				$pdf->Cell(22,5,'',1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(25,5,'',1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(22,5,'',1,0,'C',0);//Etiqueta de la Clave en el encabezado
				$i--;
			}
			
			if($hora_fin == "00:00:00"){
				$dif = "00:00:00";
			}
			else{
				$dif = diferenciaHoras($empleados[$i]["fecha"],$hora_ini,$empleados[$i+1]["fecha"],$hora_fin);
			}

			if($dif > $jornada){
				$dif = convertirDecimalHoras($dif);
				$extras = diferenciaHoras(date("Y-m-d"),$jornada,date("Y-m-d"),$dif);
				$horas_trab = $jornada;
			}
			else{
				$extras = "00:00:00";
				$horas_trab = $dif;
			}
			$pdf->Cell(30,5,number_format($horas_trab,2,".",","),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			$pdf->Cell(20,5,number_format($extras,2,".",","),1,0,'C',0);//Etiqueta de la Clave en el encabezado
			
			$total_ht += number_format($horas_trab,2,".",",");
			$total_he += number_format($extras,2,".",",");
			
			$id_emp = $empleados[$aux]["id_empleado"];
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
	
	function sumarDiaFecha($fecha,$dias){
		list($day,$mon,$year) = explode('/',$fecha);
		return date('m/d/Y',mktime(0,0,0,$mon,$day+$dias,$year));
	}
	
	function obtenerDepto($id_depto){
		$sql2 = "SELECT *
				FROM `control_costos`
				WHERE `id_control_costos` LIKE '$id_depto'";
		if($rs2 = mysql_query ($sql2)){
			$datos2 = mysql_fetch_array($rs2);
			return $datos2["descripcion"];
		}
	}
	
	function diferenciaHoras($f_ini,$h_ini,$f_fin,$h_fin){
		$ts_ini = strtotime($f_ini." ".$h_ini);
		$ts_fin = strtotime($f_fin." ".$h_fin);
		
    	
		$diff = ($ts_fin-$ts_ini)/3600;
		
		return $diff;
	}
	
	function horaDecimal($hora){
		$dec = substr($hora,0,2) + (substr($hora,3,2) / 60);
		return $dec;
	}

	function convertirDecimalHoras($decimal){
		$segundos = $decimal*3600;
		$horas = floor($segundos/3600);
		$minutos = floor(($segundos - ($horas*3600)) / 60);
		$segundos = floor($segundos - ($horas * 3600) - ($minutos*60));

		if(strlen($horas) == 1)
			$horas = "0".$horas;
		if(strlen($minutos) == 1)
			$minutos = "0".$minutos;
		if(strlen($segundos) == 1)
			$segundos = "0".$segundos;
		
		$valor = $horas.":".$minutos.":".$segundos;

		return $valor;
	}
?>