<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include ("../../includes/op_operacionesBD.php");


class PDF extends FPDF{ 

	function Header(){
		//Logo
	    $this->Image('logo-clf.jpg',10,6,30);
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
		$this->Cell(70,50,'F. 5 5.0 - 01 NOMBRAMIENTO OFICIAL',0,0,'C');
		$this->SetFont('Arial','B',10);
		//$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		//$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
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
		$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
	    //Numero de Pagina
		$this->Cell(0,15,'Página '.$this->PageNo().' de {nb}',0,0,'R');
		$this->SetY(-17);
		$this->Cell(0,15,'            Abril - 09'.'                                                                '.'02'.'                                                                 '.'    Abril - 11',0,0,'L');
		$this->SetY(-20);
		$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 02',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}
}

	//Conectar con la base de datos de recursos humanos
	$conn=conecta('bd_recursos');

	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);
	$pdf->SetTextColor(51, 51, 153);
	
	//Obtener el rfc del empleado
	$id_nombramiento = $_GET['id'];

	//Obtener los datos del nombramiento
	$stm_sql = ("SELECT fecha, empleados_rfc_empleado, area, puesto, objetivo FROM nombramientos WHERE id = '$id_nombramiento' ");
	$rs = mysql_query($stm_sql);
	$datos = mysql_fetch_array($rs);

	$fecha= strtoupper (modFecha($datos["fecha"], 2));
	
	//Obtener nombre Recursos Humanos
	$nombre=obtenerNombreEmpleado($datos["empleados_rfc_empleado"]);
	$rfc_empleado=($datos["empleados_rfc_empleado"]);
	
	//Obtener la feha de ingreso del empleado
	$stm_sql1 = ("SELECT fecha_ingreso FROM empleados 	WHERE rfc_empleado='$datos[empleados_rfc_empleado]'");
	$rs1 = mysql_query($stm_sql1);
	$datos1 = mysql_fetch_array($rs1);

	$fecha_ingreso= strtoupper (modFecha($datos1["fecha_ingreso"], 2));
	
	//Cerrar la conexion con la BD	
	mysql_close($conn);
	
	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	// Lo necesario para la fecha
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	//Move to 8 cm to the right
	$pdf->Cell(135);
	$pdf->Cell(0,-20,'Fecha:' ,'',1,'L',0);
	//Move to
	$pdf->Cell(150);
	$pdf->SetFont('Arial','',10);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(180,20,$fecha,'',1,'L',0);
	$pdf->Cell(180,7,'','',1,'L',0);

	// Lo necesario para el nombre
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	//Move to 8 cm to the right
	$pdf->Cell(10);
	$pdf->Cell(140,-4,'Nombre:' ,'',1,'L',0);
	//Move to
	$pdf->Cell(29.5);
	$pdf->SetFont('Arial','',10);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(160,4,$nombre,'B',1,'L',0);
	$pdf->Cell(180,5,'','',1,'L',0); // $pdf->Cell(90,7,'Puesto','LTR',0,'L',0); L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos, COLOR, ALINEACION

	// Lo necesario para el área
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	//Move to 8 cm to the right
	$pdf->Cell(10);
	$pdf->Cell(140,0,'Área:' ,'',1,'L',0);
	//Move to
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,-2,'','',1,'L',0); // $pdf->Cell(90,7,'Puesto','LTR',0,'L',0); L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos, COLOR, ALINEACION
	$pdf->Cell(29.5);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(160,4,$datos["area"],'B',1,'L',0);
	$pdf->Cell(180,5,'','',1,'L',0);
	
	// Lo necesario para el puesto
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	//Move to 8 cm to the right
	$pdf->Cell(10);
	$pdf->Cell(140,0,'Puesto:' ,'',1,'L',0);
	//Move to
	$pdf->Cell(180,-2,'','',1,'L',0); // $pdf->Cell(90,7,'Puesto','LTR',0,'L',0); L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos, COLOR, ALINEACION
	$pdf->Cell(29.5);
	$pdf->SetFont('Arial','',10);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(160,4,$datos["puesto"],'B',1,'L',0);
	$pdf->Cell(180,10,'','',1,'L',0);
	
	//Lo necesario para la descipción principal del nombramiento
	$pdf->SetFont('Arial','',10);
	$pdf->SetFillColor(255,255,255);
	//Move to 8 cm to the right
	$pdf->Cell(10);
	$pdf->MultiCell(180,7,'CON EFECTIVIDAD '.  $fecha .', EL (LA) SR. (A) '. $nombre .' HA SIDO DESIGNADO (A) '. $datos["puesto"] .' DEL AREA DE '. $datos["area"] .' EN LA PLANTA DE CONCERTO LANZADO DE FRESNILLO SA. DE CV. REPORTANDO DIRECTAMENETE AL QUE SUSCRIBE.' ,'','J',0);
	$pdf->Cell(180,5,'',0,'J',0);
	
	//Lo necesario para el objetivo básico del puesto
	$pdf->SetFont('Arial','',10);
	$pdf->SetFillColor(243,243,243);
	$pdf->Cell(180,5,'','',1,'L',0);
	//Move to 8 cm to the right
	$pdf->Cell(10);
	$pdf->Cell(180,7,'EL OBJETIVO BASICO DE ESTE PUESTO ES:','',1,'J',0);
	$pdf->Cell(10);
	$pdf->SetFillColor(255,255,255);
	$pdf->MultiCell(180,7, $datos['objetivo'],0,'J',0);
	
	//************************************************************************************//
	//**********VERIFICAR EL TAMAÑO DEL OBJETIVO PARA DECIDIR LA COLOCACION***************//
	//************************************************************************************//
	
	// Verificar si el tamaño del objetivo es mayor a  1074
	$objetivo= ($datos['objetivo']);
	$tam= strlen($objetivo);

	if($tam<=1074){
		//Lo necesario para la fecha de ingreso del empleado
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(180,5,'','',1,'L',0);
		//Move to 8 cm to the right
		$pdf->Cell(10);
		$pdf->SetFillColor(255,255,255);
		$pdf->MultiCell(180,7, 'EL(LA) SR(A). '. $nombre .' INGRESO A CONCRETO LANZADO DE FRESNILLO SA. DE CV. EL '.  $fecha_ingreso .'.',0,'J',0);
		
		//Lo necesari para el nombre y firma de cada uno de los interesados
		//Move to 8 cm to the right
		$pdf->Cell(9);
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->Cell(180,20,' ','',1,'C',0);
		$pdf->Cell(0,0,'',0,1);
		$pdf->SetDrawColor(0, 0, 255);
		$pdf->SetTextColor(51, 51, 153);
		$pdf->SetFont('Arial','',7);
		//$pdf->Cell(10);
		$pdf->Cell(-1);
		$pdf->Cell(110,0,'_________________________________','',1,'C',0);
		$pdf->Cell(290,0,'_________________________________','',1,'C',0);
		
		//Move to 8 cm to the right
		$pdf->Cell(9);
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->Cell(180,4,' ','',1,'C',0);
		$pdf->Cell(0,0,'',0,1);
		$pdf->SetDrawColor(0, 0, 255);
		$pdf->SetTextColor(51, 51, 153);
		$pdf->SetFont('Arial','',7);
		//$pdf->Cell(10);
		$pdf->Cell(-1);
		$pdf->Cell(110,0,'Sr (a) '. $nombre, '',1,'C',0);
		$pdf->Cell(290,0,'LAE. BLANCA MARTÍNEZ CARRILLO','',1,'C',0); 
	}// Fin if($tam<=1074)
	else {
		//Lo necesario para la fecha de ingreso del empleado
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(180,45,'','',1,'L',0);
		//Move to 8 cm to the right
		$pdf->Cell(10);
		$pdf->SetFillColor(255,255,255);
		$pdf->MultiCell(180,7, 'EL(LA) SR(A). '. $nombre .' INGRESO A CONCRETO LANZADO DE FRESNILLO SA. DE CV. EL '.  $fecha_ingreso .'.',0,'J',0);
		
		//Lo necesari para el nombre y firma de cada uno de los interesados
		//Move to 8 cm to the right
		$pdf->Cell(9);
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->Cell(180,20,' ','',1,'C',0);
		$pdf->Cell(0,0,'',0,1);
		$pdf->SetDrawColor(0, 0, 255);
		$pdf->SetTextColor(51, 51, 153);
		$pdf->SetFont('Arial','',7);
		//$pdf->Cell(10);
		$pdf->Cell(-1);
		$pdf->Cell(110,0,'_________________________________','',1,'C',0);
		$pdf->Cell(290,0,'_________________________________','',1,'C',0);
		
		//Move to 8 cm to the right
		$pdf->Cell(9);
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->Cell(180,4,' ','',1,'C',0);
		$pdf->Cell(0,0,'',0,1);
		$pdf->SetDrawColor(0, 0, 255);
		$pdf->SetTextColor(51, 51, 153);
		$pdf->SetFont('Arial','',7);
		//$pdf->Cell(10);
		$pdf->Cell(-1);
		$pdf->Cell(110,0,'Sr (a) '. $nombre, '',1,'C',0);
		$pdf->Cell(290,0,'ING. GUILLERMO MARTÍNEZ ROMÁN','',1,'C',0); 

	
	}// Fin else
	
	//**************************************************************//
	//*********************Fin de las tablas************************//
	//**************************************************************//
	
	//****************************************
	//Especificar Datos del Documento
	$pdf->SetAuthor("");
	$pdf->SetTitle("NOMBRAMIENOS".$rfc_empleado);
	$pdf->SetCreator($nombre);
	$pdf->SetSubject("Nombramiento");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Nombramiento Oficial de ".$rfc_empleado." - ".$nombre." en el SISAD");
	$rfc_empleado.='.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($rfc_empleado,"F");
	header('Location: '.$rfc_empleado);
	//Borrar todos los PDF ya creados
	borrarArchivos();
		
	//Esta función elimina los archivos PDF que se hayan generado anteriormente
	function borrarArchivos(){
		//Borrar los ficheros temporales
		$t=time();
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.pdf')
			{
				if($t-filemtime($file)>1)
					@unlink($file);
			}
		}
		closedir($h);
	}?>