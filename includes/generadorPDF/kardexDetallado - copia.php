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
	    $this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE “CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.” PROHIBIDA SU REPRODUCCIÓN TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		//$this->Cell(70,50,'F. 7.4.0 - 01 REQUISICIÓN DE COMPRAS',0,0,'C');
		$this->Cell(70,50,'DETALLE DE KARDEX',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
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
		//$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'Página '.$this->PageNo().' de {nb}',0,0,'C');
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
	$fechaI=modFecha($_GET["fechaI"],3);
	$fechaF=modFecha($_GET["fechaF"],3);
	$tipo=$_GET["tipo"];
	$criterio=$_GET["criterio"];
	$fechaRep=modFecha(date("Y-m-d"),7);
	
	if($tipo=="ind"){
		//Obtener la clave de empleado
		$claveTemp=obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$criterio);
		if($claveTemp<10)
			$clave[]="00".$claveTemp;
		if($claveTemp>=10 && $claveTemp<100)
			$clave[]="0".$claveTemp;
		if($claveTemp>=100)
			$clave[]=$claveTemp;
		//Obtener el RFC de empleado
		$rfc[]=obtenerDatoEmpleadoPorNombre("rfc_empleado",$criterio);
		//Obtener el nombre
		$nombre[]=$criterio;
	}
	else{
		//Obtener a los trabajadores del área seleccionada
		$conn=conecta("bd_recursos");
		if($criterio=="TODOS")
			$sql="SELECT rfc_empleado,id_empleados_empresa,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE id_empleados_empresa>0 ORDER BY area,nombre";
		else
			$sql="SELECT rfc_empleado,id_empleados_empresa,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE area='$criterio' ORDER BY area,nombre";
		$res=mysql_query($sql);
		if($row=mysql_fetch_array($res)){
			do{
				//Obtener la clave de empleado
				$claveTemp=$row["id_empleados_empresa"];
				if($claveTemp<10)
					$clave[]="00".$claveTemp;
				if($claveTemp>=10 && $claveTemp<100)
					$clave[]="0".$claveTemp;
				if($claveTemp>=100)
					$clave[]=$claveTemp;
				//Obtener el RFC de empleado
				$rfc[]=$row["rfc_empleado"];
				//Obtener el nombre
				$nombre[]=$row["nombre"];
			}while($row=mysql_fetch_array($res));
		}
		mysql_close($conn);
	}

	$reg=0;
	do{
		//Si es cambio de Trabajador, Agregar otra página
		if($reg>0)
			$pdf->AddPage();
			
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
		$pdf->Ln(4);//Salto de Linea
			
		//Encabezado de la Tabla
		$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
		//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
		$pdf->SetFillColor(217,217,217);
		$pdf->SetFont('Arial','B',11);//Para poner la linea y fecha en Negritas
		$pdf->Cell(60,6,'TRABAJADOR',1,0,'C',1);//Espacio Vacio para Sangria
		$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
		$pdf->Cell(130,6,$clave[$reg]." ".$nombre[$reg],1,1,'L');//Espacio Vacio para Sangria
		$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
		//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
		$pdf->SetFillColor(217,217,217);
		$pdf->SetFont('Arial','B',11);//Para poner la linea y fecha en Negritas
		$pdf->Cell(60,6,'FECHA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
		$pdf->Cell(35,6,'HORA CHECADA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
		$pdf->Cell(25,6,'INCIDENCIA',1,0,'C',1);//Clave del Empleado
		$pdf->Cell(70,6,'DESCRIPCIÓN',1,1,'C',1);//Clave del Empleado
		$pdf->SetFont('Arial','',11);//Para quitar la propiedad Negritas de los encabezados
		//Conectar a la BD
		$conn=conecta("bd_recursos");
		//Sentencia SQL
		$sql_stm="SELECT fecha_checada,hora_checada,estado FROM checadas WHERE empleados_rfc_empleado='$rfc[$reg]' AND fecha_checada BETWEEN '$fechaI' AND '$fechaF' ORDER BY fecha_checada,hora_checada";
		$rs=mysql_query($sql_stm);
		//Cerrar la conexion a la BD
		mysql_close($conn);
		if($datos=mysql_fetch_array($rs)){
			$cont=0;
			do{
				//Si se llega al registro 29, quiere decir cambio de pagina, mostrar de nuevo los encabezados
				if($cont!=0 && $cont%29==0){
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
					$pdf->Ln(4);//Salto de Linea
						
					//Encabezado de la Tabla
					$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
					//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
					$pdf->SetFillColor(217,217,217);
					$pdf->SetFont('Arial','B',11);//Para poner la linea y fecha en Negritas
					$pdf->Cell(60,6,'TRABAJADOR',1,0,'C',1);//Espacio Vacio para Sangria
					$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
					$pdf->Cell(130,6,$clave[$reg]." ".$nombre[$reg],1,1,'L');//Espacio Vacio para Sangria
					$pdf->SetFont('Arial','',11);//Para quitar la propiedad Subrayado de los encabezados
					//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
					$pdf->SetFillColor(217,217,217);
					$pdf->SetFont('Arial','B',11);//Para poner la linea y fecha en Negritas
					$pdf->Cell(60,6,'FECHA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
					$pdf->Cell(35,6,'HORA CHECADA',1,0,'C',1);//Etiqueta de la Clave en el encabezado
					$pdf->Cell(25,6,'INCIDENCIA',1,0,'C',1);//Clave del Empleado
					$pdf->Cell(70,6,'DESCRIPCIÓN',1,1,'C',1);//Clave del Empleado
					$pdf->SetFont('Arial','',11);//Para quitar la propiedad Negritas de los encabezados
				}
				
				//Comprobar el Color de Relleno del renglon
				if($cont%2==0)
					//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
					$pdf->SetFillColor(255,255,255);
				else
					//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
					$pdf->SetFillColor(182,221,232);
				$pdf->Cell(60,6,modFecha($datos["fecha_checada"],7),1,0,'C',1);//Etiqueta de la Clave en el encabezado
				$pdf->Cell(35,6,$datos["hora_checada"],1,0,'C',1);//Clave del Empleado
				$pdf->Cell(25,6,$datos["estado"],1,0,'C',1);//Clave del Empleado
				switch($datos["estado"]){
					case "A":
						$estado="Asistencia";
					break;
					case "F":
						$estado="Falta";
					break;
					case "d":
						$estado="Descanso";
					break;
					case "V":
						$estado="Vacaciones";
					break;
					case "r":
						$estado="Retardo";
					break;
					case "F/J":
						$estado="Falta/Justificada";
					break;
					case "P":
						$estado="Permiso Sin Goce de Sueldo";
					break;
					case "P/G":
						$estado="Permiso Con Goce de Sueldo";
					break;
					case "E":
						$estado="Incapacidad por Enfermedad General";
					break;
					case "RT":
						$estado="Incapacidad por Accidente de Trabajo";
					break;
					case "T":
						$estado="Incapacidad en Trayecto";
					break;
					case "D":
						$estado="Sanción Discplinaria";
					break;
					case "R":
						$estado="Regresaron";
					break;
					case "SALIDA":
						$estado="Salida";
					break;
				}
				$pdf->Cell(70,6,$estado,1,1,'C',1);//Clave del Empleado
				$cont++;
			}while($datos=mysql_fetch_array($rs));
		}
		else{
			$pdf->Ln(10);//Salto de Linea
			$pdf->SetFont('Arial','B',11);//Para poner la linea y fecha en Negritas
			$pdf->Cell(190,6,'El Trabajador NO Tiene Registro de Checadas en las Fechas del Reporte',1,1,'C',0);//Clave del Empleado
			$pdf->SetFont('Arial','',11);//Para poner la linea y fecha en Negritas
		}
		$reg++;
	}while($reg<count($clave));
	
	//Especificar Datos del Documento
	$pdf->SetAuthor("LIC. JOSE DE JESUS CARRILLO SANTACRUZ");
	$pdf->SetTitle("KARDEX");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("Kardex Detallado");
	$pdf->SetKeywords("QubicTech. Documento Generado a Partir de la Consulta de Kardex Recolectada del ".$_GET["fechaI"]." al ".$_GET["fechaF"]." en el SISAD");
	$num_req='KardexDetallado.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($num_req,"F");
	header('Location: '.$num_req);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	
	
				
	/***********************************************************************************************/
	/**************************FUNCIONES USADAS EN LA REQUISICION***********************************/
	/***********************************************************************************************/	
	//Esta función elimina los archivos PDF que se hayan generado anteriormente
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

?>