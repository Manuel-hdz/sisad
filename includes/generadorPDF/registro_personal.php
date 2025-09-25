<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include("../op_operacionesBD.php");

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
		$this->SetTextColor(78, 97, 50);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,15,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'F. 6.2.2 -05 REGISTRO DE PERSONAL',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(55);
		parent::Header();
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-17);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//Numero de Pagina
		$this->Cell(176,15,'        Rev.01'.'                                                                                                   '.'Abril, 2009    Forma: 5.5.1-01',0,0,'L');
		$this->Cell(20,15,'Página '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-27);
		$this->Cell(0,25,'F. 6.2.2 - 05 / Rev. 01, Abril/09',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->SetY(-16);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	

	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$id_empl = $_GET["id_empl"];

	$conn = conecta("bd_recursos");
	$stm_sql = "SELECT * 
				FROM  `empleados` 
				WHERE  `id_empleados_empresa` = $id_empl";
	$rs = mysql_query($stm_sql);
	$empleado = mysql_fetch_array($rs);

	$pdf->SetFont('Arial','B',8);
	$pdf->SetLineWidth(0.5);
	$pdf->Cell(16,5,"NOMBRE:","LT",0,"L");
	$pdf->Cell(74,5,strtoupper($empleado["nombre"]." ".$empleado["ape_pat"]." ".$empleado["ape_mat"]),"RT",0,"L");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(35,5,"FECHA DE INGRESO:","LTB",0,"C");
	$pdf->Cell(45,5,strtoupper(modFecha($empleado["fecha_ingreso"],2)),1,1,"C");

	$pdf->Cell(25,5,"NACIONALIDAD:","LT",0,"L");
	$pdf->Cell(65,5,strtoupper($empleado["nacionalidad"]),"RT",1,"L");

	$pdf->Cell(38,5,"EDAD:","LT",0,"L");
	$pdf->Cell(52,5,"","RT",1,"L");

	$pdf->Cell(23,5,"ESTADO CIVIL:","LT",0,"L");
	$pdf->Cell(67,5,strtoupper($empleado["edo_civil"]),"RT",1,"L");

	$pdf->Cell(9,5,"NSS:","LT",0,"L");
	$pdf->Cell(81,5,$empleado["no_ss"],"RT",1,"L");
	
	$pdf->Cell(11,5,"R.F.C.:","LT",0,"L");
	$pdf->Cell(79,5,strtoupper($empleado["rfc_empleado"]),"RT",1,"L");

	$pdf->Cell(11,5,"CURP:","LT",0,"L");
	$pdf->Cell(79,5,strtoupper($empleado["curp"]),"RT",1,"L");

	$pdf->Cell(36,5,"LUGAR DE NACIMIENTO:","LT",0,"L");
	$pdf->Cell(54,5,strtoupper($empleado["lugar_nacimiento"]),"RT",1,"L");

	$pdf->Cell(20,5,"CATEGORIA:","LT",0,"L");
	$pdf->Cell(70,5,strtoupper($empleado["puesto"]),"RT",1,"L");

	$pdf->Cell(30,5,"CENTRO DE COSTO:","LT",0,"L");
	$pdf->Cell(60,5,strtoupper(obtenerDato("bd_recursos","control_costos","descripcion","id_control_costos",$empleado["id_control_costos"])),"RT",1,"L");

	$pdf->Cell(37,5,"NUMERO DE EMPLEADO:","LT",0,"L");
	$pdf->Cell(53,5,$id_empl,"RT",1,"L");

	$pdf->Cell(50,5,"SUELDO SEMANAL:   $".number_format($empleado["sueldo_diario"]*7,2,".",","),"LT",0,"L");
	$pdf->Cell(40,5,"DIARIO:   $".number_format($empleado["sueldo_diario"],2,".",","),"RT",1,"L");

	$pdf->Cell(33,5,"NUMERO DE CUENTA:","LTB",0,"L");
	$pdf->Cell(57,5,$empleado["no_cta"],"RTB",1,"L");

	$pdf->Ln();

	$pdf->Cell(19,5,"DIRECCION:","LT",0,"L");
	$pdf->Cell(71,5,strtoupper($empleado["calle"]." ".$empleado["num_ext"]." ".$empleado["num_int"]." ".$empleado["colonia"]),"RT",1,"L");

	$pdf->Cell(45,5,"ESTADO:   ".strtoupper($empleado["estado"]),"LT",0,"L");
	$pdf->Cell(45,5,"C.P.   ".$empleado["cp"],"RT",1,"L");

	$pdf->Cell(45,5,"TEL.   ".$empleado["telefono"],"LT",0,"L");
	$pdf->Cell(45,5,"TEL. CEL.   ","RT",1,"L");

	$pdf->Cell(27,5,"TIPO DE SANGRE:","LT",0,"L");
	$pdf->Cell(63,5,strtoupper($empleado["tipo_sangre"]),"RT",0,"L");
	$pdf->Cell(5,5,"",0,0,"C");
	$pdf->Cell(50,5,"ALERGIA ALGUN MEDICAMENTO:","LT",0,"L");
	$pdf->Cell(7,5,"","LT",0,"L");
	$pdf->Cell(28,5,"","LRT",1,"L");

	$pdf->Cell(180,5,"EN CASO DE ACCIDENTE AVISAR A: ".$empleado["nom_accidente"].",   TEL. ".$empleado["tel_accidente"].",   TEL. CEL. ". $empleado["cel_accidente"],1,1,"L");
	
	$pdf->SetFillColor(255,220,0);
	$pdf->Cell(90,5,"DOCUMENTOS QUE PRESENTO","LRB",0,"L",1);
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"ACTA DE NACIMIENTO:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"COMPROBANTE DE DOMICILIO:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"CURP:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"CARTILLA MILITAR:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"CARTA DE NO ANTECEDENTES PENALES:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"LICENCIA DE MANEJO:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Cell(90,5,"COMPROBANTE DE ESTUDIOS:","LRB",0,"L");
	$pdf->Cell(5,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",0,"C");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(10,5,"","LRB",1,"C");

	$pdf->Ln(20);

	$pdf->Cell(0,5,"SOLICITO: ".strtoupper($_GET["solicito"]),0,1,"L");

	$pdf->Ln(8);

	$pdf->Cell(90,5,"ELABORO: ".obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_GET["usuario"]),0,0,"L");
	$pdf->Cell(10,5,"",0,0,"L");
	$pdf->Cell(90,5,"AUTORIZO: BLANCA MARTINEZ CARRILLO",0,1,"L");

	$pdf->SetAuthor("RECURSOS HUMANOS");
	$pdf->SetTitle("REGISTRO DE PERSONAL");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("REGISTRO DE POERSONAL");
	$pdf->SetKeywords("CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.\n REGISTRO DE PERSONAL");
	$num_req ='registro_personal.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($num_req,"F");
	header('Location: '.$num_req);
	//Borrar todos los PDF ya creados
	borrarArchivos();

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
?>
	